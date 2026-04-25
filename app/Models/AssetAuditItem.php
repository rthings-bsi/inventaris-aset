<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetAuditItem extends Model
{
    protected $primaryKey = 'id_asset_audit_items';

    protected $fillable = ['id_asset_audits', 'id_assets', 'scanned_code', 'status', 'notes', 'scanned_at'];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function audit()
    {
        return $this->belongsTo(AssetAudit::class, 'id_asset_audits');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'id_assets');
    }
}
