<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * Users relation, all users of this role
     *
     * @return App\User
     */
    public function users() {
        return $this->hasMany('App\User');
    }
}
