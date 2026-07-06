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
        'notes',
        'reject_reason'
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

    public function repairs()
    {
        return $this->hasMany(AssetRepair::class, 'id_asset_loans');
    }

    /** Total biaya perbaikan selama peminjaman ini */
    public function getTotalRepairCostAttribute(): float
    {
        return (float) $this->repairs()->sum('cost');
    }

    /** Estimasi biaya depresiasi selama periode peminjaman */
    public function getDepreciationCostAttribute(): float
    {
        if (!$this->asset || !$this->asset->annual_depreciation || !$this->loan_date) {
            return 0;
        }
        $start = $this->loan_date;
        $end = $this->return_date ?? now();
        $days = $start->diffInDays($end);
        $annualDepr = $this->asset->annual_depreciation;
        return ($annualDepr / 365) * $days;
    }

    /** Total biaya (depresiasi + perbaikan) */
    public function getTotalCostAttribute(): float
    {
        return $this->depreciation_cost + $this->total_repair_cost;
    }

    /** Durasi peminjaman dalam hari */
    public function getDurationDaysAttribute(): int
    {
        $start = $this->loan_date;
        $end = $this->return_date ?? now();
        return max(1, $start->diffInDays($end));
    }
}
