<?php

namespace App\Http\Controllers;

use App\Client;
use App\Contact;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Agent;
use App\JobContact;
use App\Job;
use Auth;

class ContactsController extends Controller
{

	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'name'      => 'required',
			'type'      => 'in:Client,Supplier,Other',
		]);

		$names = explode(' ', $request->name, 2);
		$user = Auth::user();

		$contact = new Contact;
		$contact->agent_id   = $user->agent_id;
		$contact->first_name = $names[0];
		$contact->last_name  = isset($names[1]) ? $names[1] : '';
		$contact->phone      = $request->phone;
		$contact->email      = $request->email;
		$contact->position   = $request->position;
		$contact->type       = $request->type;

		if ($request->job_id && !$request->client_associated) {
			$contact->client_id = null;
		} else {
			$contact->client_id = $request->client_id;
		}

		$contact->save();

		if (isset($request->job_id)) {
			$job = Job::findOrFail($request->job_id);

			$jobContact = new JobContact;
			$jobContact->job_id = $job->id;
			$jobContact->contact_id = $contact->id;
			$jobContact->save();

			$job->addLog('Added contact : ' . ucwords($contact->first_name . ' ' . $contact->last_name));
		}
	}

	public function postEdit(Request $request, $contact_id)
	{
		$this->validate($request, [
			'name' => 'required',
			'type' => 'in:Client,Supplier,Other',
		]);

		$names = explode(' ', $request->name, 2);

		$user = $request->api_token ? Auth::guard('api')->user() : Auth::user();

		Contact::whereId($contact_id)
			   ->whereAgentId($user->agent_id)
			   ->update([
				  'client_id'  => $request->client_id,
				  'first_name' => $names[0],
				  'last_name'  => $names[1],
				  'phone'      => $request->phone,
				  'email'      => $request->email,
				  'position'   => $request->position,
				  'type'       => $request->type,
			   ]);

		return response()->json(true);
	}

	public function postDelete($contact_id)
	{
		Contact::whereId($contact_id)
			   ->whereAgentId(Auth::user()->agent_id)
			   ->delete();

		return response()->json(true);
	}

}

