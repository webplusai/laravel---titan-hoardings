<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\JobNotification;
use App\Http\Controllers\Controller;
use Auth;

class DashboardController extends Controller
{

	public function getIndex()
	{
		$job_notifications = JobNotification::where('agent_id', Auth::user()->agent_id)
			->orderBy('created_at', 'DESC')
			->paginate(25);

		return view('pages.agent.dashboard')
			->with('job_notifications', $job_notifications);
	}

}
