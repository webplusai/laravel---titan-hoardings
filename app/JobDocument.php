<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use File;

class JobDocument extends Model
{

	public $guarded = [];

	public function delete()
	{
		if (File::exists('storage/job_documents/'.$this->name)) {
			File::delete('storage/job_documents/'.$this->name);
		}

		parent::delete();
	}

	public function job()
	{
		return $this->belongsTo('App\Job');
	}

	/**
	 * Get the document as a url.
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return url('storage/job_documents/') . '/' . $this->name;
	}

}
