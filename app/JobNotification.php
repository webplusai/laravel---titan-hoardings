<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobNotification extends Model
{
	protected $table = 'job_notifications';
	protected $fillable = ['user_id','agent_id','job_id','message','title','type'];

	public function agent()
	{
		return $this->belongsTo('App\Agent');
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function job()
	{
		return $this->belongsTo('App\Jobs');
	}

}
