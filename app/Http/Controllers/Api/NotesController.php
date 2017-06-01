<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Job;
use App\JobNote;
use Auth;

class NotesController extends Controller
{

	/**
	 * Gets a list of notes for a single job.
	 */
	public function getListByJob($job_id)
	{
		$job = Job::findOrFail($job_id);
		$this->ensureUserIsInstallerFor($job);

		$notes = [];

		foreach ($job->notes()->orderby('created_at', 'DESC')->get() as $note) {
			$notes[] = [
				'id'         => $note->id,
				'first_name' => $note->user->first_name,
				'last_name'  => $note->user->last_name,
				'message'    => $note->message,
				'created_at' => $note->created_at->setTimezone(Auth::guard('api')->user()->timezone)->format('c'),
			];
		}

		return response()->json($notes);
	}

	/**
	 * Adds a note to a job.
	 */
	public function postCreate(Request $request, $job_id)
	{
		$this->validate($request, [
			'message' => 'required',
		]);

		$job = Job::findOrFail($job_id);
		$this->ensureUserIsInstallerFor($job);

		$note = JobNote::create([
			'job_id'  => $job->id,
			'user_id' => Auth::guard('api')->id(),
			'message' => $request->message,
		]);

		return response()->json(['note_id' => $note->id]);
	}

}
