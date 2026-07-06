<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetRepair extends Model
{
    protected $table = 'asset_repairs';
    protected $primaryKey = 'id_asset_repairs';

    protected $fillable = [
        'id_assets', 'repair_date', 'description', 'repair_type',
        'cost', 'vendor', 'notes', 'status', 'created_by'
    ];

    protected $casts = [
        'repair_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'id_assets');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getRepairTypeLabelAttribute(): string
    {
        return match($this->repair_type) {
            'maintenance' => 'Perawatan Rutin',
            'damage' => 'Perbaikan Kerusakan',
            'warranty' => 'Klaim Garansi',
            default => ucfirst($this->repair_type)
        };
    }
}
