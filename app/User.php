<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at','updated_at', 'email_verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get All permission of the current user
     *
     * @return App\Permission
     */
    public function permissions() {
        return $this->belongsToMany('App\Permission');
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Check if the user has a especific permission
     *
     * @param string Permission Name
     * @return boolean
     */
    public function hasPermissionTo($moduleName) {

        $permissions=$this->permissions;
        $hasPermission=false;
        foreach($permissions as $permission)
        {
            if($permission->name == $moduleName)
            {
                $hasPermission=true;
            }
        }
        return $hasPermission;
    }

}
