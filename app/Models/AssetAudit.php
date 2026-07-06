<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetAudit extends Model
{
    protected $primaryKey = 'id_asset_audits';

    protected $fillable = [
        'title', 'description', 'audit_type', 'frequency',
        'audit_date', 'status', 'next_audit_date',
        'selected_assets', 'created_by'
    ];

    protected $casts = [
        'audit_date' => 'date',
        'next_audit_date' => 'date',
        'selected_assets' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(AssetAuditItem::class, 'id_asset_audits');
    }

    public function foundItems()
    {
        return $this->items()->whereNotNull('id_assets');
    }

    public function missingItems()
    {
        return $this->items()->whereNull('id_assets');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getAuditTypeLabelAttribute(): string
    {
        return match($this->audit_type) {
            'full' => 'Audit Penuh',
            'sample' => 'Audit Sampel',
            'spot' => 'Spot Check',
            default => ucfirst($this->audit_type)
        };
    }

    public function getFrequencyLabelAttribute(): ?string
    {
        return match($this->frequency) {
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'quarterly' => 'Triwulan',
            'yearly' => 'Tahunan',
            default => $this->frequency
        };
    }
}
