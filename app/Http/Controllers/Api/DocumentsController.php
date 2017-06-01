<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Job;
use App\JobDocument;
use App\JobNotification;
use Auth;

class DocumentsController extends Controller
{

	/**
	 * Lists the documents for a particular job.
	 */
	public function getListByJob($job_id)
	{
		$job = Job::findOrFail($job_id);
		$this->ensureUserIsInstallerFor($job);

		$documents = [];
		$timezone = Auth::guard('api')->user()->timezone;

		foreach ($job->documents as $document) {
			$documents[] = [
				'id'         => $document->id,
				'name'       => $document->name,
				'file'       => $document->getUrl(),
				'created_at' => $document->created_at->setTimezone($timezone)->format('c'),
			];
		}

		return response()->json($documents);
	}

	/**
	 * Creates a document for a job.
	 */
	public function postCreate(Request $request, $job_id)
	{
		$this->validate($request, [
			'file' => 'required|file',
		]);

		$job = Job::findOrFail($job_id);
		$this->ensureUserIsInstallerFor($job);

		$document = $job->addDocument($request->file('file'));

		JobNotification::create([
			'user_id'  => $this->user->id,
			'agent_id' => $this->user->agent_id,
			'job_id'   => $job->id,
			'message'  => 'Uploaded a job document '.ucwords($document->name),
			'title'    => 'Uploaded a Job document',
			'type'     => 'info'
		]);

		return response()->json(['document_id' => $document->id]);
	}

	/**
	 * Deletes a document.
	 */
	public function postDelete(Request $request, $job_id, $document_id)
	{
		$document = JobDocument::findOrFail($document_id);
		$this->ensureUserIsInstallerFor($document->job);

		$document->delete();

		JobNotification::create([
			'user_id'  => $this->user->id,
			'agent_id' => $this->user->agent_id,
			'job_id'   => $document->job_id,
			'message'  => 'Deleted a job document '.ucwords($document->name),
			'title'    => 'Deleted a Job document',
			'type'     => 'info'
		]);
	}

}
