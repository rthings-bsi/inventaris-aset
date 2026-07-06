<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetAuditItem extends Model
{
    protected $primaryKey = 'id_asset_audit_items';

    protected $fillable = [
        'id_asset_audits', 'id_assets', 'scanned_code',
        'status', 'condition_score', 'condition_grade',
        'criteria_data', 'checklist_status', 'notes', 'scanned_at'
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'criteria_data' => 'array',
        'condition_score' => 'decimal:2',
    ];

    public function audit()
    {
        return $this->belongsTo(AssetAudit::class, 'id_asset_audits');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'id_assets');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->checklist_status) {
            'pending' => 'Belum Diperiksa',
            'scanned' => 'Telah Dipindai',
            'checked' => 'Telah Diceklis',
            'verified' => 'Terverifikasi',
            default => ucfirst($this->checklist_status)
        };
    }

    public function getConditionGradeBadgeAttribute(): ?string
    {
        return match($this->condition_grade) {
            'A' => 'Baik Sekali',
            'B' => 'Baik',
            'C' => 'Cukup',
            'D' => 'Rusak Ringan',
            'E' => 'Rusak Berat',
            default => $this->condition_grade
        };
    }
}
