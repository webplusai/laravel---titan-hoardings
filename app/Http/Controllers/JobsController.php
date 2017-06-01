<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Product;
use App\ProductPrice;
use App\User;
use App\Job;
use App\JobInstaller;
use App\JobProduct;
use App\JobDocument;
use App\JobNotification;
use App\Material;
use App\Agent;
use App\Client;
use App\JobContact;
use App\JobNote;
use App\HoardingType;
use Carbon\Carbon;
use Auth;
use DateTime;
use App\Image;
use DB;
use Log;

class JobsController extends Controller
{

	/**
	 * Show list of all jobs
	 * @return view
	 */
	public function getIndex()
	{
		if ($this->user->isInstaller()) {
			$query = $this->user->jobs();
		} else {
			$query = Job::where('agent_id', $this->user->agent_id);
		}

		$jobs = $query->orderBy('id', 'desc')
			->paginate(25);

		return view('pages.jobs-list')
			->with('jobs', $jobs)
			->with('title', 'Jobs List');
	}

	/**
	 * Show create job form
	 * @return view
	 */
	public function getView($job_id)
	{
		$job = Job::findOrFail($job_id);

		if (!$job->isViewableBy($this->user)) {
			abort(404);
		}

		$hoarding_type = $job->hoardingType ? $job->hoardingType->name : $job->hoarding_type_other;
		$title = ucfirst($job->type) . ' of ' . $hoarding_type . ' for ' . $job->client->name;

		// $installers = join agent_installers where agent_id = job.agent_id

		$installers = DB::table('agent_installers')
			->join('users', 'users.id', '=', 'agent_installers.installer_id')
			->where('agent_installers.agent_id', $job->agent_id)
			->whereNotIn('users.id', $job->installers()->lists('id'))
			->get();

		$product_prices = ProductPrice::whereAgentId($job->agent_id)
			->where('type', '!=', 'N')
			->get();

		$related_jobs = Job::where('related_job_id', $job->related_job_id)
			->orderBy('start_time', 'asc')
			->get();

		return view('pages.jobs-view')
			->with('title', $title)
			->with('job', $job)
			->with('installers', $installers)
			->with('related_jobs', $related_jobs)
			->with('job_id', $job_id)
			->with('product_prices', $product_prices);
	}

	/**
	 * Show create job form
	 * @return view
	 */
	public function getCreate()
	{
		if (Auth::user()->isInstaller()) {
			abort(403);
		}

		$materials = Material::orderBy('name')->get();
		$hoarding_types = HoardingType::orderBy('name')->get();

		return view('pages.jobs-create')
			->with('title', 'Create Job')
			->with('materials', $materials)
			->with('hoarding_types', $hoarding_types);
	}

	/**
	 * Handle create job data
	 * @param  Request $request
	 * @return redirect
	 */
	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'type'                => 'required',
			'client'              => 'required',
			'num_doors'           => 'required|numeric',
			'dust_panel_height'   => 'required|numeric',
			'total_height'        => 'required|numeric',
			'total_length'        => 'required|numeric',
			'return_size'         => 'required|numeric',
			'material'            => 'required',
			'material_other'      => 'required_if:material,other',
			'hoarding_type'       => 'required',
			'hoarding_type_other' => 'required_if:hoarding_type,other',
			'start_date'          => 'date_format:d/m/Y',
			'start_time'          => 'date_format:h:iA',
		]);

		$job = new Job;
		$job->agent_id = $this->user->agent_id;
		$job->client_id = $request->get('client', '');
		$job->user_id = Auth::user()->id;
		$job->hoarding_type_id = $request->hoarding_type == 'other' ? null : $request->hoarding_type;
		$job->material_id = $request->material == 'other' ? null : $request->material;
		$job->type = $request->get('type', '');
		$job->status = 'pending';
		$job->shop_name = $request->get('shop_name', '');
		$job->address = $request->get('address', '');
		$job->suburb = $request->get('suburb', '');
		$job->state = $request->get('state', '');
		$job->postcode = $request->get('postcode', '');
		$job->num_doors = $request->num_doors;

		$job->dust_panel_height = $request->dust_panel_height * 1000;
		$job->total_length = $request->total_length * 1000;
		$job->total_height = $request->total_height * 1000;
		$job->return_size = $request->return_size * 1000;

		$job->hoarding_type_other = $request->get('hoarding_type_other', '');
		$job->material_other = $request->get('material_other', '');
		$job->internal_job_id = $request->get('internal_job_id');

		$job->comments = $request->get('comments', '');

		// These times are not timezone aware. They're stored as, say, 1pm UTC
		// which means 1pm in the location's timezone.
		if ($request->get('start_date') && $request->get('start_time')) {
			$job->start_time = DateTime::createFromFormat('d/m/Y h:iA', $request->get('start_date') . ' ' . $request->get('start_time'));
		}

		$job->save();
		$job->addLog('Created job');

		//Make related job id into its own id
		$job->related_job_id = $job->id;
		$job->save();

		$job->jsra()->create([]);
		$job->qc()->create([]);

		return redirect('/jobs/view/' . $job->id);
	}

	/**
	 * Show edit job page
	 * @param  int $job_id
	 * @return view
	 */
	public function getEdit($job_id)
	{
		$job = Job::findOrFail($job_id);

		if (!Auth::user()->canManageJob($job)) {
			abort(403);
		}

		$materials = Material::orderBy('name')->get();
		$hoarding_types = HoardingType::orderBy('name')->get();

		return view('pages.jobs-edit')
			->with('title', 'Edit Job')
			->with('job', $job)
			->with('materials', $materials)
			->with('hoarding_types', $hoarding_types);
	}

	/**
	 * Handle edit job data
	 * @param  Request $request
	 * @return redirect
	 */
	public function postEdit(Request $request, $job_id)
	{
		$this->validate($request, [
			'type'                => 'required',
			'client'              => 'required',
			'num_doors'           => 'required|numeric',
			'dust_panel_height'   => 'required|numeric',
			'total_height'        => 'required|numeric',
			'total_length'        => 'required|numeric',
			'return_size'         => 'required|numeric',
			'material'            => 'required',
			'material_other'      => 'required_if:material,other',
			'hoarding_type'       => 'required',
			'hoarding_type_other' => 'required_if:hoarding_type,other',
			'start_date'          => 'date_format:d/m/Y',
			'start_time'          => 'date_format:h:iA',
		]);

		$job = Job::findOrFail($job_id);

		if (!$this->user->canManageJob($job)) {
			abort(403);
		}

		$job->agent_id = Auth::user()->agent_id;
		$job->client_id = $request->get('client', '');
		$job->user_id = Auth::user()->id;
		$job->hoarding_type_id = $request->hoarding_type == 'other' ? null : $request->hoarding_type;
		$job->material_id = $request->material == 'other' ? null : $request->material;
		$job->type = $request->get('type', '');
		$job->shop_name = $request->get('shop_name', '');
		$job->address = $request->get('address', '');
		$job->suburb = $request->get('suburb', '');
		$job->state = $request->get('state', '');
		$job->postcode = $request->get('postcode', '');
		$job->num_doors = $request->get('num_doors', '');

		$job->dust_panel_height = $request->get('dust_panel_height', 0) * 1000;
		$job->total_length = $request->get('total_length', 0) * 1000;
		$job->total_height = $request->get('total_height', 0) * 1000;
		$job->return_size = $request->get('return_size', 0) * 1000;

		$job->hoarding_type_other = $request->get('hoarding_type_other', '');
		$job->material_other = $request->get('material_other', '');
		$job->internal_job_id = $request->get('internal_job_id');

		$job->comments = $request->get('comments', '');

		// These times are not timezone aware. They're stored as, say, 1pm UTC
		// which means 1pm in the location's timezone.
		if ($request->get('start_date') && $request->get('start_time')) {
			$job->start_time = DateTime::createFromFormat('d/m/Y h:iA', $request->get('start_date') . ' ' . $request->get('start_time'));
		}

		$job->save();
		$job->addLog('Edited job');

		JobNotification::create([
			'user_id'  => Auth::user()->id,
			'agent_id' => Auth::user()->agent_id,
			'job_id'   => $job->id,
			'message'  => 'Modified a job (' . ucwords($job->type) . ' of ' . (is_null($job->hoardingType) ? $job->hoarding_type_other : $job->hoardingType->name) . ' for ' . $job->client->name . ')',
			'title'    => 'Modified a Job',
			'type'     => 'info',
		]);

		return redirect('/jobs');
	}

	public function getCopyJob($job_id)
	{
		return view('modals.jobs-copy')
			->with('job_id', $job_id);
	}

	public function postCopyJob(Request $request)
	{
		$this->validate($request, [
			'job_id'     => 'required',
			'type'       => 'required',
			'start_date' => 'required|date_format:d/m/Y',
			'start_time' => 'required|date_format:h:iA',
		]);

		$old_job = Job::find($request->job_id);

		$job = new Job;
		$job->agent_id = $old_job->agent_id;
		$job->client_id = $old_job->client_id;
		$job->user_id = Auth::user()->id;
		$job->hoarding_type_id = $old_job->hoarding_type_id;
		$job->material_id = $old_job->material_id;
		$job->type = $request->type;
		$job->status = $old_job->status;
		$job->shop_name = $old_job->shop_name;
		$job->address = $old_job->address;
		$job->suburb = $old_job->suburb;
		$job->state = $old_job->state;
		$job->postcode = $old_job->postcode;
		$job->num_doors = $old_job->num_doors;
		$job->dust_panel_height = $old_job->dust_panel_height;
		$job->total_length = $old_job->total_length;
		$job->total_height = $old_job->total_height;
		$job->return_size = $old_job->return_size;
		$job->hoarding_type_other = $old_job->hoarding_type_other;
		$job->material_other = $old_job->material_other;
		$job->related_job_id = $old_job->related_job_id;

		$job->internal_job_id = $request->internal_job_id;

		// These times are not timezone aware. They're stored as, say, 1pm UTC
		// which means 1pm in the location's timezone.
		if ($request->get('start_date') && $request->get('start_time')) {
			$job->start_time = DateTime::createFromFormat('d/m/Y h:iA', $request->get('start_date') . ' ' . $request->get('start_time'));
		}

		$job->save();

		$job->jsra()->create([]);
		$job->qc()->create([]);

		$this->copyProducts($request->job_id, $job->id);
		$this->copyContacts($request->job_id, $job->id);
		$this->copyImages($request->job_id, $job->id);

		if ($request->copy_installers == 'yes') {
			$this->copyInstallers($request->job_id, $job->id);
		}

		$job_notification = [
			'user_id'  => Auth::user()->id,
			'agent_id' => Auth::user()->agent_id,
			'job_id'   => $old_job->id,
			'message'  => 'Performed a job copy (' . ucwords($old_job->type) . ' of ' . (is_null($old_job->hoardingType) ? $old_job->hoarding_type_other : $old_job->hoardingType->name) . ' for ' . $old_job->client->name . ')',
			'title'    => 'Performed a Job copy',
			'type'     => 'info',
		];

		JobNotification::create($job_notification);
	}

	private function copyInstallers($old_job_id, $new_job_id)
	{
		$old_job_installers = JobInstaller::where('job_id', $old_job_id)->get();
		$array = [];
		foreach ($old_job_installers as $installer) {
			$array[] = [
				'job_id'       => $new_job_id,
				'installer_id' => $installer->installer_id,
			];
		}

		if ($array == []) {
			return false;
		}

		JobInstaller::insert($array);
	}

	private function copyImages($old_job_id, $new_job_id)
	{
		$old_job_images = Image::where('job_id', $old_job_id)->get();
		$array = [];
		foreach ($old_job_images as $image) {
			$array[] = [
				'user_id'   => Auth::user()->id,
				'job_id'    => $new_job_id,
				'filename'  => $image->filename,
				'extension' => $image->extension,
				'caption'   => $image->caption,
				'type'      => $image->type,
			];
		}

		if ($array == []) {
			return false;
		}

		Image::insert($array);
	}

	private function copyContacts($old_job_id, $new_job_id)
	{
		$old_job_contacts = JobContact::where('job_id', $old_job_id)->get();
		$array = [];
		foreach ($old_job_contacts as $contact) {
			$array[] = [
				'job_id'     => $new_job_id,
				'contact_id' => $contact->contact_id,
			];
		}

		if ($array == []) {
			return false;
		}

		JobContact::insert($array);
	}

	private function copyProducts($old_job_id, $new_job_id)
	{
		$old_job_products = JobProduct::where('job_id', $old_job_id)->get();
		$array = [];
		foreach ($old_job_products as $product) {
			$array[] = [
				'job_id'       => $new_job_id,
				'product_id'   => $product->product_id,
				'price'        => $product->price,
				'is_collected' => $product->is_collected,
				'quantity'     => $product->quantity,
			];
		}

		if ($array == []) {
			return false;
		}

		JobProduct::insert($array);
	}

	public function postDelete(Request $request)
	{
		$job = Job::where('id', $request->job_id)->first();

		JobNotification::create([
			'user_id'  => Auth::user()->id,
			'agent_id' => Auth::user()->agent_id,
			'job_id'   => $request->job_id,
			'message'  => 'Deleted a job (' . ucwords($job->type) . ' of ' . (is_null($job->hoardingType) ? $job->hoarding_type_other : $job->hoardingType->name) . ' for ' . $job->client->name . ')',
			'title'    => 'Deleted a Job',
			'type'     => 'info',
		]);

		Job::where('id', $request->job_id)->delete();
	}

	public function getAddExistingContacts($job_id)
	{
		$job = Job::findOrFail($job_id);

		return view('modals.jobs-add-existing-contacts')
			->with('job', $job);
	}

	public function postAddExistingContacts(Request $request, $job_id)
	{
		$this->validate($request, [
			'contact_ids' => 'required',
		]);

		$job = Job::findOrFail($job_id);

		foreach ($request->contact_ids as $contact_id) {
			if (JobContact::where('job_id', $job_id)->where('contact_id', $contact_id)->exists()) {
				continue;
			}

			$job_contact = JobContact::create([
				'job_id'     => $job->id,
				'contact_id' => $contact_id,
			]);

			$job->addLog('Added contact : ' . ucwords($job_contact->contact->first_name . ' ' . $job_contact->contact->last_name));

			JobNotification::create([
				'user_id'  => $this->user->id,
				'agent_id' => $this->user->agent_id,
				'job_id'   => $job->id,
				'message'  => 'Added a job contact: ' . ucwords($job_contact->contact->first_name . ' ' . $job_contact->contact->last_name),
				'title'    => 'Added a Job contact',
				'type'     => 'info',
			]);
		}
	}

	public function getCreateContact($job_id)
	{
		$job = Job::findOrFail($job_id);

		return view('modals.jobs-create-contact')
			->with('job', $job);
	}

	public function postDeleteContact($contact_id, Request $request)
	{
		$job_contact = JobContact::whereContactId($contact_id)
			->whereJobId($request->job_id)
			->first();

		JobContact::whereContactId($contact_id)
			->whereJobId($request->job_id)
			->delete();

		$job = Job::findOrFail($request->job_id);
		$job->addLog('Deleted contact : ' . ucwords($job_contact->contact->first_name . ' ' . $job_contact->contact->last_name));

		$job_notification = [
			'user_id'  => Auth::user()->id,
			'agent_id' => Auth::user()->agent_id,
			'job_id'   => $request->job_id,
			'message'  => 'Deleted a job contact ' . ucwords($job_contact->contact->first_name . ' ' . $job_contact->contact->last_name),
			'title'    => 'Deleted a Job contact',
			'type'     => 'info',
		];

		JobNotification::create($job_notification);

		return response()->json(true);
	}

	public function postAddNote(Request $request)
	{
		$this->validate($request, [
			'message' => 'required',
			'job_id'  => 'required',
		]);

		$note = new JobNote;
		$note->message = $request->message;
		$note->job_id = $request->job_id;
		$note->user_id = $this->user->id;
		$note->created_at = Carbon::now();
		$note->save();

		JobNotification::create([
			'user_id'  => Auth::user()->id,
			'agent_id' => Auth::user()->agent_id,
			'job_id'   => $request->job_id,
			'message'  => 'Added a job note ' . ucwords($request->get('message', '')),
			'title'    => 'Added a Job note',
			'type'     => 'info',
		]);

		return response()->json(['note_id' => $note->id]);
	}

	public function postAddInstallers(Request $request)
	{
		$this->validate($request, [
			'installer_ids' => 'required|array',
		]);

		foreach ($request->installer_ids as $installer_id) {
			$exists = JobInstaller::where('job_id', $request->job_id)
				->where('installer_id', $installer_id)
				->exists();

			if ($exists) {
				continue;
			}

			$job_installer = new JobInstaller;
			$job_installer->job_id = $request->job_id;
			$job_installer->installer_id = $installer_id;
			$job_installer->save();

			$job = Job::findOrFail($request->job_id);
			$job->addLog('Added a job installer : ' . ucwords($job_installer->installer->first_name . ' ' . $job_installer->installer->last_name));

			$job_installer->installer->queuePushNotification(
				'A new Job is assigned to you. Please accept OR decline the job.',
				0,
				'titan://job/invite/' . $request->job_id
			);

			JobNotification::create([
				'user_id'  => $this->user->id,
				'agent_id' => $this->user->agent_id,
				'job_id'   => $request->job_id,
				'message'  => 'Added a job installer ' . ucwords($job_installer->installer->first_name . ' ' . $job_installer->installer->last_name),
				'title'    => 'Added a Job installer',
				'type'     => 'info',
			]);

			// Set primary installer if  no primary installer is set.
			if ($job->primary_installer_id == null) {
				$job->primary_installer_id = $installer_id;
				$job->save();
				$user = User::findOrFail($installer_id);
				$job->addLog('Set ' . $user->email . ' as primary installer');
			}
		}
	}

	public function postDeleteInstaller($installer_id, Request $request)
	{
		$job_installer = JobInstaller::whereInstallerId($installer_id)
			->whereJobId($request->job_id)->first();

		$job_notification = [
			'user_id'  => Auth::user()->id,
			'agent_id' => Auth::user()->agent_id,
			'job_id'   => $request->job_id,
			'message'  => 'Deleted a job installer ' . ucwords($job_installer->installer->first_name . ' ' . $job_installer->installer->last_name),
			'title'    => 'Deleted a Job installer',
			'type'     => 'info',
		];

		JobNotification::create($job_notification);

		$job = Job::findOrFail($request->job_id);
		$job->addLog('Deleted job installer ' . ucwords($job_installer->installer->first_name . ' ' . $job_installer->installer->last_name));

		JobInstaller::whereInstallerId($installer_id)
			->whereJobId($request->job_id)
			->delete();

		if ($job->primary_installer_id == $installer_id) {
			$job->primary_installer_id = null;
			$job->save();
		}

		return response()->json(true);
	}

	public function postAddDocument(Request $request)
	{
		$this->validate($request, [
			'job_id' => 'required',
			'file'   => 'required|file',
		]);

		$job = Job::findOrFail($request->job_id);

		$document = $job->addDocument($request->file('file'));

		$job->addLog('Added document ' . ucwords($document->name));

		JobNotification::create([
			'user_id'  => $this->user->id,
			'agent_id' => $this->user->agent_id,
			'job_id'   => $job->id,
			'message'  => 'Uploaded a job document ' . ucwords($document->name),
			'title'    => 'Uploaded a Job document',
			'type'     => 'info',
		]);

		return response()->json(['document_id' => $document->id]);
	}

	public function postDeleteDocument(Request $request, $document_id)
	{
		$document = JobDocument::findOrFail($document_id);
		$document->delete();

		JobNotification::create([
			'user_id'  => $this->user->id,
			'agent_id' => $this->user->agent_id,
			'job_id'   => $document->job_id,
			'message'  => 'Deleted a job document ' . ucwords($document->name),
			'title'    => 'Deleted a Job document',
			'type'     => 'info',
		]);

		$job = Job::findOrFail($document->job_id);
		$job->addLog('Deleted document ' . ucwords($document->name));

		return redirect('/jobs/view/' . $document->job_id . '#documents');
	}

	public function postAddProduct(Request $request)
	{
		$this->validate($request, [
			'product_id' => 'required',
			'quantity'   => 'required',
		]);

		if (JobProduct::whereJobId($request->job_id)->whereProductId($request->product_id)->exists()) {
			return response()->json(['error' => ['Product already added in this job.']], 422);
		}

		$job_product = new JobProduct;
		$job_product->job_id = $request->job_id;
		$job_product->product_id = $request->product_id;
		$job_product->price = Product::getPriceForAgent($this->user->agent, $request->product_id);
		$job_product->quantity = $request->quantity;
		$job_product->is_collected = (bool) $request->is_collected;
		$job_product->save();

		$job = Job::findOrFail($request->job_id);
		$job->addLog('Added product : ' . ucwords($job_product->product->name));

		JobNotification::create([
			'user_id'  => $this->user->id,
			'agent_id' => $this->user->agent_id,
			'job_id'   => $request->job_id,
			'message'  => 'Added a job product ' . ucwords($job_product->product->name),
			'title'    => 'Added a Job product',
			'type'     => 'info',
		]);
	}

	public function postEditProduct(Request $request, $job_id, $product_id)
	{
		$this->validate($request, [
			'quantity' => 'required',
		]);

		$job = Job::findOrFail($job_id);
		$this->ensureJobBelongsToAgent($job);

		$job_product = $job->products()->whereProductId($product_id)->firstOrFail();
		$job_product->quantity = $request->quantity;
		$job_product->is_collected = (bool) $request->is_collected;
		$job_product->save();

		$job = Job::findOrFail($job_product->job_id);
		$job->addLog('Edited product : ' . ucwords($job_product->product->name));

		JobNotification::create([
			'user_id'  => $this->user->id,
			'agent_id' => $this->user->agent_id,
			'job_id'   => $job_product->job_id,
			'message'  => 'Modified a job product ' . ucwords($job_product->product->name),
			'title'    => 'Modified a Job product',
			'type'     => 'info',
		]);
	}

	public function postDeleteProduct($id)
	{
		$job_product = JobProduct::whereId($id)->first();

		$job = Job::findOrFail($job_product->job_id);
		$job->addLog('Deleted product : ' . ucwords($job_product->product->name));

		JobNotification::create([
			'user_id'  => Auth::user()->id,
			'agent_id' => Auth::user()->agent_id,
			'job_id'   => $job_product->job_id,
			'message'  => 'Deleted a job product ' . ucwords($job_product->product->name),
			'title'    => 'Deleted a Job Product',
			'type'     => 'info',
		]);

		JobProduct::whereId($id)->delete();

		return response()->json(true);
	}

	public function getSearchInstallers(Request $request)
	{
		$query = User::orderBy('id', 'desc');

		$query->where('type', 'installer');

		$query->where(function ($query) use ($request) {
			$query->orWhere('first_name', 'like', '%' . $request->global_search . '%');
			$query->orWhere('last_name', 'like', '%' . $request->global_search . '%');
			$query->orWhere('email', 'like', '%' . $request->global_search . '%');
		});

		$user = isset($request->api_token) ? Auth::guard('api')->user() : Auth::user();

		if (!$user->isGlobalAdmin()) {
			$query->where('agent_id', $user->agent_id);
		}

		return $query->get();
	}

	public function postSetPrimaryInstaller(Request $request, $job_id)
	{
		$job = Job::findOrFail($job_id);
		$job->primary_installer_id = $request->installer_id;
		$job->save();

		$user = User::findOrFail($request->installer_id);

		$job->addLog('Set ' . $user->email . ' as primary installer');
	}

	public function getSqc($job_id)
	{
		$job = Job::findOrFail($job_id);

		if (!$job->isViewableBy($this->user)) {
			abort(404);
		}

		$pdf = $job->sqc();

		return response($pdf)
			->header('Content-type', 'application/pdf');
	}

}