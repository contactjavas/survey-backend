<?php

namespace App\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use stdClass;
use App\Shared\Models\Role;

class User extends Model
{
    use SoftDeletes;

    /**
     * Appended properties
     *
     * @var array
     */
    protected $appends = ['roles'];

    /**
     * All roles available
     *
     * @var array
     */
    protected $roles = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $roles = Role::all();

        foreach ($roles as $role) {
            $class = new stdClass();

            $class->role_id = $role->role_id;
            $class->slug    = $role->slug;
            $class->name    = $role->name;

            $this->roles[$role->role_id] = $class;
        }
    }

    /**
     * Get roles attribute
     *
     * This function doesn't work properly.
     * Only user with id 1 and id 2 can get the roles properly.
     *
     * @return object
     */
    /*public function getRolesAttribute()
    {
        return $this
        ->hasManyThrough(
            'App\Shared\Models\Role',
            'App\Shared\Models\UserRoleRelationship',
            'user_id',
            'role_id',
            'id'
        )
        ->get();
    }*/

    public function getRolesAttribute()
    {
        $userRoles = $this->hasMany('App\Shared\Models\UserRoleRelationship')->get();

        $roles = [];

        foreach ($userRoles as $userRole) {
            $role = $this->roles[$userRole->role_id];
            array_push($roles, $role);
        }

        return $roles;
    }
}
