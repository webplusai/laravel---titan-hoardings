<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Image;
use App\Job;
use App\JobInstaller;
use App\JobDocument;
use App\ProductPrice;
use App\JobNote;
use Storage;
use DB;
use App\Exceptions\ServiceValidationException;
use App\User;
use App\CheckIn;
use Hash;
use InterventionImage;

class JobsController extends Controller
{

	public function getIndex(Request $request)
	{
		$this->validate($request, [
			'installer_status' => 'in:pending,accepted,declined',
			'from_date'        => 'date_format:Y-m-d',
			'to_date'          => 'date_format:Y-m-d',
		]);

		// Get jobs list
		$query = DB::table('jobs AS j')
			->join('clients AS c', 'j.client_id', '=', 'c.id')
			->join('job_installers AS i', 'i.job_id', '=', 'j.id')
			->leftJoin('job_contacts AS contacts', 'contacts.job_id', '=', 'j.id')
			->leftJoin('job_documents AS d', 'd.job_id', '=', 'j.id')
			->leftJoin('images AS img', 'img.job_id', '=', 'j.id')
			->leftJoin('job_installers AS i2', 'i2.job_id', '=', 'j.id')
			->leftJoin('job_notes AS n', 'n.job_id', '=', 'j.id')
			->leftJoin('job_products AS p', 'p.job_id', '=', 'j.id')
			->where('i.installer_id', $this->user->id)
			->groupBy('j.id')
			->orderBy('j.start_time', 'asc');

		if ($request->installer_status) {
			$query->where('i.status', $request->installer_status);
		}

		if ($request->from_date) {
			$date = new Carbon($request->from_date, $this->user->timezone);
			$date->startOfDay();
			$date->setTimezone('UTC');
			$query->where('j.start_time', '>=', $date);
		}

		if ($request->to_date) {
			$date = new Carbon($request->to_date, $this->user->timezone);
			$date->endOfDay();
			$date->setTimezone('UTC');
			$query->where('j.start_time', '<=', $date);
		}

		$results = $query->get([
			'j.*',
			'c.name AS client_name',
			'i.status AS installer_status',
			DB::raw('COUNT(DISTINCT(contacts.id)) AS num_contacts'),
			DB::raw('COUNT(DISTINCT(d.id)) AS num_documents'),
			DB::raw('COUNT(DISTINCT(img.id)) AS num_images'),
			DB::raw('COUNT(DISTINCT(i2.id)) AS num_installers'),
			DB::raw('COUNT(DISTINCT(n.id)) AS num_notes'),
			DB::raw('COUNT(DISTINCT(p.id)) AS num_products'),
		]);

		$jobs = [];

		foreach ($results as $result) {
			$jobs[] = [
				'id'                => $result->id,
				'internal_job_id'   => $result->internal_job_id,
				'title'             => ucfirst($result->type) . ' for ' . $result->client_name,
				'type'              => $result->type,
				'status'            => $result->status,
				'installer_status'  => $result->installer_status,
				'shop_name'         => $result->shop_name,
				'address'           => $result->address,
				'suburb'            => $result->suburb,
				'state'             => $result->state,
				'postcode'          => $result->postcode,
				'comments'          => $result->comments,
				'num_doors'         => $result->num_doors,
				'dust_panel_height' => $result->dust_panel_height / 1000,
				'total_length'      => $result->total_length / 1000,
				'total_height'      => $result->total_height / 1000,
				'return_size'       => $result->return_size / 1000,
				'start_time'        => (new Carbon($result->start_time))->setTimezone($this->user->timezone)->format('c'),
				'form_completed_at' => $result->form_completed_at ? (new Carbon($result->form_completed_at))->setTimezone($this->user->timezone)->format('c') : null,
				'num_contacts'      => $result->num_contacts,
				'num_documents'     => $result->num_documents,
				'num_images'        => $result->num_images,
				'num_installers'    => $result->num_installers,
				'num_notes'         => $result->num_notes,
				'num_products'      => $result->num_products,
			];
		}

		// Get num pending jobs
		$num_pending = DB::table('jobs AS j')
			->join('job_installers AS i', 'i.job_id', '=', 'j.id')
			->where('i.installer_id', $this->user->id)
			->where('i.status', 'pending')
			->count();

		return response()->json([
			'jobs'        => $jobs,
			'num_pending' => $num_pending,
		]);
	}

	public function getView($job_id)
	{
		$job = Job::findOrFail($job_id);
		$this->ensureUserIsInstallerFor($job);

		return response()->json([
			'id'                   => $job->id,
			'agent_id'             => $job->agent_id,
			'internal_job_id'      => $job->internal_job_id,
			'client_id'            => $job->client_id,
			'primary_installer_id' => $job->primary_installer_id,
			'related_job_id'       => $job->related_job_id,
			'type'                 => $job->type,
			'status'               => $job->status,
			'installer_status'     => $job->installers()->where('installer_id', $this->user->id)->value('status'),
			'shop_name'            => $job->shop_name,
			'address'              => $job->address,
			'suburb'               => $job->suburb,
			'state'                => $job->state,
			'postcode'             => $job->postcode,
			'num_doors'            => $job->num_doors,
			'dust_panel_height'    => $job->dust_panel_height / 1000,
			'total_length'         => $job->total_length / 1000,
			'total_height'         => $job->total_height / 1000,
			'return_size'          => $job->return_size / 1000,
			'start_time'           => (new Carbon($job->start_time))->setTimezone($this->user->timezone)->format('c'),
			'form_completed_at'    => $job->form_completed_at ? $job->form_completed_at->setTimezone($this->user->timezone)->format('c') : null,
			'num_contacts'         => $job->contacts->count(),
			'num_documents'        => $job->documents->count(),
			'num_images'           => $job->images->count(),
			'num_installers'       => $job->installers->count(),
			'num_notes'            => $job->notes->count(),
			'num_products'         => $job->products->count(),
			'client_name'          => $job->client->name,
			'hoarding_type'        => $job->hoarding_type_id ? $job->hoardingType->name : $job->hoarding_type_other,
			'material_name'        => $job->material_id ? $job->material->name : $job->material_other,
			'comments'             => $job->comments,
		]);
	}

	public function postImage(Request $request, $job_id)
	{
		$this->validate($request, [
			'image' => 'required|image',
			'type'  => 'in:pre-installation,post-installation',
		]);

		$job = Job::findOrFail($job_id);
		$this->ensureUserIsInstallerFor($job);

		$extension = $request->file('image')->getClientOriginalExtension();
		$filename = $job->id . '-' . substr(md5(microtime()), 0, 10);

		Storage::disk('public')->put('job-images/' . $filename . '.' . $extension, file_get_contents($request->file('image')));

		$image = InterventionImage::make(file_get_contents($request->file('image')));
		$image->resize(50, null, function ($constraint) {
			$constraint->aspectRatio();
		});

		Storage::disk('public')->put('job-images/' . $filename . '-thumbnail.' . $extension, $image->stream('jpg', 80));

		$image = new Image();
		$image->user_id = $this->user->id;
		$image->job_id = $job->id;
		$image->filename = $filename;
		$image->extension = $extension;
		$image->caption = $request->get('caption', '');
		$image->type = $request->get('type', null);
		$image->save();

		return response()->json('success', 200);
	}

	public function getImages($job_id)
	{
		$job = Job::findOrFail($job_id);
		$this->ensureUserIsInstallerFor($job);

		foreach ($job->images as $image) {
			$image->url = $image->getUrl();
			$image->thumbnail_url = $image->getThumbnailUrl();
		}

		return response()->json($job->images);
	}

	/**
	 * Endpoint for an installer to accept a job.
	 */
	public function postAccept($job_id)
	{
		$this->setInstallerStatus($job_id, 'accepted');

		$job = Job::findOrFail($job_id);

		if ($job->status == 'pending') {
			$job->status = 'accepted';
			$job->save();
		}
	}

	/**
	 * Endpoint for an installer to decline a job.
	 */
	public function postDecline($job_id)
	{
		$this->setInstallerStatus($job_id, 'declined');
	}

	/**
	 * Private function to handle an installer accepting or declining a job.
	 */
	private function setInstallerStatus($job_id, $status)
	{
		$job = Job::findOrFail($job_id);
		$this->ensureUserIsInstallerFor($job);

		JobInstaller::where('installer_id', $this->user->id)
					->where('job_id', $job_id)
					->update(['status' => $status]);
	}

	/**
	 * Endpoint for a primary installer setting another installer as the
	 * primary.
	 */
	public function postSetPrimaryInstaller(Request $request, $job_id)
	{
		$job = Job::findOrFail($job_id);
		$this->ensureUserIsPrimaryInstallerFor($job);

		$candidate = User::find($request->installer_id);

		$is_installer = JobInstaller::where('job_id', $job->id)
									->where('installer_id', $candidate->id)
									->exists();

		if (!$is_installer) {
			abort(404);
		}

		$job->primary_installer_id = $candidate->id;
		$job->save();

		$job->addLog('Set '.$candidate->email.' as primary installer');
	}

	/**
	 * Endpoint for an installer to mark themselves as working on a job.
	 */
	public function postCheckin($job_id)
	{
		$job = Job::findOrFail($job_id);
		$this->ensureUserIsInstallerFor($job);

		$checkin = new CheckIn;
		$checkin->job_id = $job_id;
		$checkin->installer_id = $this->user->id;
		$checkin->check_in_time = Carbon::now();
		$checkin->save();

		return response()->json(['id' => $checkin->id]);
	}

	/**
	 * Endpoint for an installer to mark themselves as no longer working on a
	 * job.
	 */
	public function postCheckout($job_id, $checkin_id)
	{
		$checkin = CheckIn::findOrFail($checkin_id);

		if ($checkin->installer_id != $this->user->id) {
			abort(404);
		}

		if ($checkin->job_id != $job_id) {
			abort(404);
		}

		$checkin->update(['check_out_time' => Carbon::now()]);
	}

	public function postUpdateStatus(Request $request, $job_id)
	{
		$this->validate($request, [
			'status' => 'required|in:in-progress,jsra-complete,works-completed,installation-certificate-complete,qc-pending',
		]);

		$job = Job::FindOrFail($job_id);

		if ($request->status == 'jsra-complete' && $this->installers()->whereNull('form_signed_at')->exists()) {
			throw new ServiceValidationException('All installers must sign the JSRA before the job can progress. If an installer is no longer part of the job, please remove them before continuing.');
		}

		if ($request->status == 'in-progress' && $job->images()->whereType('pre-installation')->count() < 2) {
			throw new ServiceValidationException('At least 2 site photos are required.');
		}

		if ($request->status == 'qc-pending' && $job->images()->whereType('post-installation')->count() < 4) {
			throw new ServiceValidationException('All 4 post installation images are required.');
		}

		$job->status = $request->status;
		$job->save();
	}

}
