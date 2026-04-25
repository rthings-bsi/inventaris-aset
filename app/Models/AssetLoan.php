<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetLoan extends Model
{
    protected $primaryKey = 'id_asset_loans';

    protected $fillable = [
        'id_assets',
        'id_users',
        'loan_date',
        'return_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'loan_date' => 'datetime',
        'return_date' => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'id_assets', 'id_assets')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }
}
