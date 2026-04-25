<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'id_categories';
    protected $fillable = ['category_name', 'description'];

    public function assets()
    {
        return $this->hasMany(Asset::class, 'id_categories', 'id_categories');
    }
}
