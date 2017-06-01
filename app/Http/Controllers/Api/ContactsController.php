<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contact;
use App\Job;
use Auth;
use App\JobContact;
use App\JobNotification;

class ContactsController extends Controller
{

	/**
	 * Gets a list of contacts for a single job.
	 */
	public function getListByJob($job_id)
	{
		$job = Job::findOrFail($job_id);
		$this->ensureUserIsInstallerFor($job);

		$contacts = [];

		foreach ($job->contacts as $contact) {
			$contacts[] = [
				'id'         => $contact->contact->id,
				'first_name' => $contact->contact->first_name,
				'last_name'  => $contact->contact->last_name,
				'email'      => $contact->contact->email,
				'phone'      => $contact->contact->phone,
				'position'   => $contact->contact->position,
				'type'       => $contact->contact->type
			];
		}

		return response()->json($contacts);
	}

	/**
	 * Returns a list of all contacts for the current installer's agent,
	 * optionally filtered by name or email.
	 */
	public function getSearch(Request $request, $job_id)
	{
		$job = Job::findOrFail($job_id);
		$this->ensureUserIsPrimaryInstallerFor($job);

		$query = Contact::where('agent_id', $job->agent_id);

		$query->where(function ($query) use ($request) {
			$query->orWhere('first_name', 'like', '%' . $request->phrase . '%');
			$query->orWhere('last_name', 'like', '%' . $request->phrase . '%');
			$query->orWhere('email', 'like', '%' . $request->phrase . '%');
		});

		$results = $query->orderBy('first_name')->orderBy('last_name')->get();

		$contacts = [];

		foreach ($results as $contact) {
			$contacts[] = [
				'id'         => $contact->id,
				'first_name' => $contact->first_name,
				'last_name'  => $contact->last_name,
				'email'      => $contact->email,
				'phone'      => $contact->phone,
				'position'   => $contact->position,
				'type'       => $contact->type
			];
		}

		return $contacts;
	}

	/**
	 * Creates a new contact.
	 */
	public function postCreate(Request $request, $job_id)
	{
		$this->validate($request, [
			'first_name' => 'required',
			'last_name'  => 'required',
			'type'       => 'required|in:Client,Supplier,Other',
		]);

		$job = Job::findOrFail($job_id);
		$this->ensureUserIsPrimaryInstallerFor($job);

		$contact = new Contact;
		$contact->agent_id = $job->agent_id;
		$contact->client_id = $request->client_id;
		$contact->first_name = $request->first_name;
		$contact->last_name = $request->last_name;
		$contact->phone = $request->phone;
		$contact->email = $request->email;
		$contact->position = $request->position;
		$contact->type = $request->type;
		$contact->save();

		$jobContact = new JobContact;
		$jobContact->job_id = $job->id;
		$jobContact->contact_id = $contact->id;
		$jobContact->save();

		$job->addLog('Added contact: '.ucwords($contact->first_name.' '.$contact->last_name));

		return response()->json([
			'contact_id' => $contact->id,
		]);
	}

	/**
	 * Edits an existing contact.
	 */
	public function postEdit(Request $request, $contact_id)
	{
		$this->validate($request, [
			'name' => 'required',
			'type' => 'in:Client,Supplier,Other',
		]);

		$contact = Contact::findOrFail($contact_id);

		$this->ensureUserIsInstallerForAgent($contact->agent_id);

		$names = explode(' ', $request->name, 2);

		$contact->update([
			'client_id'  => $request->client_id,
			'first_name' => $names[0],
			'last_name'  => $names[1],
			'phone'      => $request->phone,
			'email'      => $request->email,
			'position'   => $request->position,
			'type'       => $request->type,
		]);
	}

	/**
	 * Adds multiple client contacts to a job.
	 */
	public function postAdd(Request $request, $job_id)
	{
		$this->validate($request, [
			'job_id'      => 'required',
			'contact_ids' => 'required|array',
		]);

		$job = Job::findOrFail($job_id);
		$this->ensureUserIsInstallerFor($job);

		foreach ($request->contact_ids as $contact_id) {
			if (JobContact::where('job_id', $job->id)->where('contact_id', $contact_id)->exists()) {
				continue;
			}

			$contact = JobContact::create([
				'job_id'     => $job->id,
				'contact_id' => $contact_id,
			]);

			$job->addLog('Added contact : '.ucwords($contact->contact->first_name.' '.$contact->contact->last_name));

			JobNotification::create([
				'user_id'  => $this->user->id,
				'agent_id' => $job->agent_id,
				'job_id'   => $job->id,
				'message'  => 'Added a job contact: '.ucwords($contact->contact->first_name.' '.$contact->contact->last_name),
				'title'    => 'Added a Job contact',
				'type'     => 'info',
			]);
		}
	}

}
