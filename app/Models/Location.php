<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $primaryKey = 'id_locations';
    protected $fillable = ['location_name', 'description'];

    public function assets()
    {
        return $this->hasMany(Asset::class, 'id_locations', 'id_locations');
    }
}
