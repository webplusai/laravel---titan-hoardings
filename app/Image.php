<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function job()
	{
		return $this->belongsTo('App\Job');
	}

	/**
	 * Get the image url.
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return url('/storage/job-images') . '/' . $this->filename . '.' . $this->extension;
	}

	/**
	 * Get the image thumbnail url.
	 *
	 * @return string
	 */
	public function getThumbnailUrl()
	{
		return url('/storage/job-images') . '/' . $this->filename . '-thumbnail.' . $this->extension;
	}

}
