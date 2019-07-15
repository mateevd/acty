<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Businesses extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities()
    {
        return $this->hasMany('App\Models\Activity', 'businesses_id');
    }
}
