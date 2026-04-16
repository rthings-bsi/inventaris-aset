<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $fillable = ['role', 'permission'];

    public static function forRole($role)
    {
        return self::where('role', $role)->pluck('permission')->toArray();
    }
}
