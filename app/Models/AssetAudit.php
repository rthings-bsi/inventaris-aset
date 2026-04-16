<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetAudit extends Model
{
    protected $fillable = ['title', 'description', 'audit_date', 'status', 'created_by'];

    protected $casts = [
        'audit_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(AssetAuditItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
