<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteRequest extends Model
{
    public function client() {
    	return $this->belongsTo('App\Client');
    }

    public function product() {
    	return $this->belongsTo('App\Product');
    }
}
