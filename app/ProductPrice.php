<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
	public $table = 'product_prices';

	public function product()
	{
		return $this->belongsTo('App\Product');
	}

}
