<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Job;
use App\JobInstaller;
use App\JobNotification;
use App\User;
use Auth;
use App\Exceptions\ServiceValidationException;

class InstallersController extends Controller
{

	/**
	 * Gets the list of installers for a particular job.
	 */
	public function getListByJob($job_id)
	{
		$job = Job::findOrFail($job_id);
		$this->ensureUserIsInstallerFor($job);

		$installers = [];

		foreach ($job->installers as $item) {
			$installers[] = [
				'id'           => $item->installer->id,
				'first_name'   => $item->installer->first_name,
				'last_name'    => $item->installer->last_name,
				'email'        => $item->installer->email,
				'phone'        => $item->installer->phone_main,
				'has_signed'   => $item->form_signed_at ? true : false,
			];
		}

		return response()->json($installers);
	}

	/**
	 * Returns a list of all installers for the given agent,
	 * optionally filtered by name or email.
	 */
	public function getSearch(Request $request, $job_id)
	{
		$job = Job::findOrFail($job_id);
		$this->ensureUserIsPrimaryInstallerFor($job);

		$query = User::where('type', 'installer')
			->join('agent_installers AS ai', 'ai.installer_id', '=', 'users.id')
			->where('ai.agent_id', $job->agent_id)
			->whereNotIn('users.id', $job->installers()->pluck('installer_id'));

		$query->where(function ($query) use ($request) {
			$query->orWhere('users.first_name', 'like', '%' . $request->phrase . '%');
			$query->orWhere('users.last_name', 'like', '%' . $request->phrase . '%');
			$query->orWhere('users.email', 'like', '%' . $request->phrase . '%');
		});

		$results = $query->orderBy('users.first_name')
			->orderBy('users.last_name')
			->select(['users.*'])
			->get();

		$installers = [];

		foreach ($results as $installer) {
			$installers[] = [
				'id'         => $installer->id,
				'first_name' => $installer->first_name,
				'last_name'  => $installer->last_name,
				'email'      => $installer->email,
				'phone'      => $installer->phone_main,
			];
		}

		return response()->json($installers);
	}

	/**
	 * Adds an installer to a job.
	 */
	public function postAdd(Request $request, $job_id)
	{
		$job = Job::findOrFail($job_id);
		$this->ensureUserIsPrimaryInstallerFor($job);

		foreach ($request->installer_ids as $installer_id) {
			$exists = JobInstaller::where('job_id', $request->job_id)
								  ->where('installer_id', $installer_id)
								  ->exists();

			if ($exists) {
				continue;
			}

			$job_installer = new JobInstaller;
			$job_installer->job_id = $job->id;
			$job_installer->installer_id = $installer_id;
			$job_installer->save();

			$job_installer->installer->queuePushNotification(
				'A new Job is assigned to you. Please accept OR decline the job.',
				0,
				'titan://job/invite/'.$request->job_id
			);

			JobNotification::create([
				'user_id'  => $this->user->id,
				'agent_id' => $this->user->agent_id,
				'job_id'   => $request->job_id,
				'message'  => 'Added a job installer '.ucwords($job_installer->installer->first_name.' '.$job_installer->installer->last_name),
				'title'    => 'Added a Job installer',
				'type'     => 'info'
			]);
		}
	}

	/**
	 * Removes an installer from a job.
	 */
	public function postRemove($job_id, $installer_id)
	{
		$job = Job::findOrFail($job_id);
		$this->ensureUserIsPrimaryInstallerFor($job);

		if ($installer_id == $this->user->id) {
			throw new ServiceValidationException('You cannot remove yourself from the job.');
		}

		JobInstaller::where('job_id', $job_id)
					->where('installer_id', $installer_id)
					->delete();
	}

}
