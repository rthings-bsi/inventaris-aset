<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetLoan extends Model
{
    protected $fillable = [
        'asset_id',
        'user_id',
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
        return $this->belongsTo(Asset::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
