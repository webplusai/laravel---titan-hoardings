<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mail;
use Illuminate\Http\UploadedFile;
use App\User;
use Auth;
use App\Log;
use mPDF;
use Carbon;

class Job extends Model
{
	protected $dates = ['start_time','finish_time','form_completed_at'];

	public function emailCertificate()
	{
		$pdf = \PDF::loadView('emails.job-certificate', ['job' => $this]);

		Mail::send('emails.job-agent-mail', ['job' => $this], function ($message) use($pdf) {
			$message->from(env('MAIL_FROM'), 'Titan Hoardings');
			$message->to($this->agent->email, $this->agent->name)->subject('Job Certificate');
			$message->attachData($pdf->inline(), 'Job-Certificate.pdf');
		});
	}

	/**
	 * Generates the Safety, Quality and Compliance PDF and returns it.
	 *
	 * This is combination of the JSRA and QC forms, in PDF format.
	 */
	public function sqc()
	{
		$html = view('pdfs.sqc')
			->with('job', $this)
			->render();

		$mpdf = new mPDF;
		$mpdf->addPage(null, null, null, null, null, 5, 5, 35, 5, 5);
		$mpdf->writeHtml($html);

		return $mpdf->output('', 'S');
	}

	public function addLog($activity)
	{
		$user = Auth::user() ? Auth::user() : Auth::guard('api')->user();

		Log::create([
				'user_id'  => $user ? $user->id : null,
				'job_id'   => $this->id,
				'activity' => $activity,
			]);
	}

	public function updateFormStatus()
	{
		if ($this->installers()->whereNull('form_signed_at')->exists()) {
			$this->form_completed_at = null;
			$this->save();
			return;
		}

		$this->form_completed_at = Carbon::now();
		$this->status = 'jsra-complete';
		$this->save();
	}

	public function notifyUnsignedInstallers()
	{
		$installers = $this->installers()->whereNull('form_signed_at')->get();

		foreach ($installers as $installer) {
			$installer->installer->queuePushNotification(
				'The JSA for job #' . $this->id . ' is ready to be signed or needs to be re-signed due to changes.',
				0,
				'titan://jobs/form/' . $this->id
			);
		}
	}

	public function addDocument(UploadedFile $file)
	{
		$document = $this->documents()->create([
			'name' => '',
		]);

		$document->name = $document->id . '-' . $file->getClientOriginalName();
		$document->save();

		$file->move('storage/job_documents/', $document->name);

		return $document;
	}

	public function isViewableBy(User $user)
	{
		if ($user->agent_id == $this->agent_id) {
			return true;
		}

		return $this->installers()->where('installer_id', $user->id)->exists();
	}

	public function client()
	{
		return $this->belongsTo('App\Client');
	}

	public function agent()
	{
		return $this->belongsTo('App\Agent');
	}

	public function contacts()
	{
		return $this->hasMany('App\JobContact');
	}

	public function hoardingType()
	{
		return $this->belongsTo('App\HoardingType');
	}

	public function material()
	{
		return $this->belongsTo('App\Material');
	}

	public function images()
	{
		return $this->hasMany('App\Image');
	}

	public function notes()
	{
		return $this->hasMany('App\JobNote');
	}

	public function installers()
	{
		return $this->hasMany('App\JobInstaller');
	}

	public function documents()
	{
		return $this->hasMany('App\JobDocument');
	}

	public function products()
	{
		return $this->hasMany('App\JobProduct');
	}

	public function jobNotification()
	{
		return $this->hasMany('App\JobNotification');
	}

	public function primaryInstaller()
	{
		return $this->belongsTo('App\User', 'primary_installer_id');
	}

	public function jsra()
	{
		return $this->hasOne('App\JsraForm');
	}

	public function qc()
	{
		return $this->hasOne('App\QcForm');
	}

}
