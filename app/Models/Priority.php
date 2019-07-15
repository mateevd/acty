<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Priority extends Model
	{
		/**
		 * @return \Illuminate\Database\Eloquent\Relations\HasMany
		 */
		public function activities()
		{
			return $this->hasMany('App\Models\Activity', 'priority_id');
		}
	}
