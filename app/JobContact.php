<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobContact extends Model
{

	public $timestamps = false;

	protected $guarded = [];

	public function contact()
	{
		return $this->belongsTo('App\Contact');
	}

}
