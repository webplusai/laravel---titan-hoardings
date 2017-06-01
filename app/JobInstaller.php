<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobInstaller extends Model
{
	public $dates = ['form_signed_at'];

	public function installer()
	{
		return $this->belongsTo('App\User', 'installer_id');
	}

}
