<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use App\Job;
use App\JobInstaller;
use Auth;
use DB;

class Controller extends BaseController
{
	use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

	protected $user = null;

	public function __construct()
	{
		$this->user = request()->api_token ? Auth::guard('api')->user() : Auth::user();
	}

	protected function ensureUserIsInstallerFor(Job $job)
	{
		$is_installer = JobInstaller::where('job_id', $job->id)->where('installer_id', $this->user->id)->exists();

		if (!$is_installer) {
			abort(403);
		}
	}

	protected function ensureUserIsPrimaryInstallerFor(Job $job)
	{
		if ($this->user->id != $job->primary_installer_id) {
			abort(403);
		}
	}

	protected function ensureJobBelongsToAgent(Job $job)
	{
		if ($job->agent_id != $this->user->agent_id) {
			abort(403);
		}
	}

	protected function ensureUserIsInstallerForAgent($agent_id)
	{
		$is_installer = DB::table('agent_installers')
			->where('agent_id', $agent_id)
			->where('installer_id', $this->user->id)
			->exists();

		if (!$is_installer) {
			abort(403);
		}
	}

}
