<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetAuditItem extends Model
{
    protected $fillable = ['asset_audit_id', 'asset_id', 'scanned_code', 'status', 'notes', 'scanned_at'];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function audit()
    {
        return $this->belongsTo(AssetAudit::class, 'asset_audit_id');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
