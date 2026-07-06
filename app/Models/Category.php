<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'id_categories';
    protected $fillable = ['category_name', 'description', 'code_prefix'];

    public function assets()
    {
        return $this->hasMany(Asset::class, 'id_categories', 'id_categories');
    }

    /** Auto-generate prefix from category name if not set */
    public static function generatePrefix(string $name): string
    {
        $clean = preg_replace('/[^A-Za-z]/', '', $name);
        return strtoupper(substr($clean, 0, 3));
    }
}
