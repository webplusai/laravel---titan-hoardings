<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
	protected $dates = ['expires_at', 'created_at', 'updated_at'];

	public function client()
	{
		return $this->belongsTo('App\Client');
	}

	public function agent()
	{
		return $this->belongsTo('App\Agent');
	}

	public function hoardingType()
	{
		return $this->belongsTo('App\HoardingType');
	}

}
