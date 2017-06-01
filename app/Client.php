<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

	public $guarded = [];

	public function contacts()
	{
		return $this->hasMany('App\Contact');
	}

	public function hoardingMaterials()
	{
		return $this->hasMany('App\ClientHoardingMaterial');
	}

}
