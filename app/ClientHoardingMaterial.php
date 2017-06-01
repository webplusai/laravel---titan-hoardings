<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientHoardingMaterial extends Model
{

	protected $fillable = [
		'client_id', 'hoarding_material_id'
	];
	public $timestamps = false;

	public function hoardingMaterial()
	{
		return $this->belongsTo('App\HoardingMaterial', 'hoarding_material_id');
	}

}
