<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
	/**
	 * The users that belong to the role.
	 */
	public function users()
	{
		return $this->belongsToMany(User::class, 'config_user')->withPivot('value');
	}
}
