<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{

	public function scopeByParent($query, $parent_agent_id)
	{
		if ($parent_agent_id) {
			$query->whereParentAgentId($parent_agent_id);
		} else {
			$query->whereNull('parent_agent_id');
		}
	}

	public function parent()
	{
		return $this->belongsTo('App\Agent', 'parent_agent_id');
	}

	public function prices()
	{
		return $this->hasMany('App\ProductPrice');
	}

	public function installers()
	{
		return $this->belongsToMany('App\User', 'agent_installers', 'agent_id', 'installer_id');
	}

	public function users()
	{
		return $this->hasMany('App\User');
	}

	public function contacts()
	{
		return $this->hasMany('App\Contact');
	}

	public function jobNotification()
	{
		return $this->hasMany('App\JobNotification');
	}

}
