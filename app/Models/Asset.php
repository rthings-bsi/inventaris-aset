<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Asset extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    
    protected $primaryKey = 'id_assets';

    protected $fillable = [
        'asset_code',
        'asset_name',
        'description',
        'id_categories',
        'acquisition_cost',
        'residual_value',
        'useful_life_years',
        'depreciation_method',
        'acquisition_date',
        'condition',
        'id_locations',
        'id_users',
        'person_in_charge',
        'photo',
        'status'
    ];

    protected $casts = [
        'acquisition_date' => 'date',
        'acquisition_cost' => 'decimal:2',
        'residual_value' => 'decimal:2',
    ];

    // ─── Scopes ──────────────────────────────────────────────
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['category'] ?? false, function ($query, $category) {
            $query->where('id_categories', $category);
        });

        $query->when($filters['status'] ?? false, function ($query, $status) {
            $query->where('status', $status);
        });

        $query->when($filters['condition'] ?? false, function ($query, $condition) {
            $query->where('condition', $condition);
        });

        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('asset_code', 'like', '%' . $search . '%')
                  ->orWhere('asset_name', 'like', '%' . $search . '%')
                  ->orWhereHas('location', function ($locQ) use ($search) {
                      $locQ->where('location_name', 'like', '%' . $search . '%');
                  });
            });
        });

        $query->when($filters['start_date'] ?? false, function ($query, $startDate) {
            $query->whereDate('acquisition_date', '>=', $startDate);
        });

        $query->when($filters['end_date'] ?? false, function ($query, $endDate) {
            $query->whereDate('acquisition_date', '<=', $endDate);
        });
    }

    // ─── Relationships ───────────────────────────────────────
    public function loans()
    {
        return $this->hasMany(AssetLoan::class, 'id_assets', 'id_assets');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_categories', 'id_categories');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'id_locations', 'id_locations');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }

    public function repairs()
    {
        return $this->hasMany(AssetRepair::class, 'id_assets');
    }

    // ─── Depreciation ────────────────────────────────────────
    public function getDepreciableValueAttribute(): float
    {
        $residual = $this->residual_value ?? 0;
        return max(0, $this->acquisition_cost - $residual);
    }

    public function getAnnualDepreciationAttribute(): float
    {
        if (!$this->useful_life_years || $this->useful_life_years <= 0) return 0;
        return $this->depreciable_value / $this->useful_life_years;
    }

    public function getYearsElapsedAttribute(): float
    {
        if (!$this->acquisition_date) return 0;
        return max(0, now()->diffInYears($this->acquisition_date, true));
    }

    public function getAccumulatedDepreciationAttribute(): float
    {
        $years = $this->years_elapsed;
        if ($this->depreciation_method === 'straight_line') {
            return min($this->depreciable_value, $this->annual_depreciation * $years);
        }
        // double-declining
        if ($this->depreciation_method === 'double_declining') {
            $rate = (2 / max(1, $this->useful_life_years));
            $acc = 0;
            $bookValue = $this->acquisition_cost;
            for ($y = 0; $y < floor($years); $y++) {
                $depr = min($bookValue - ($this->residual_value ?? 0), $bookValue * $rate);
                $acc += $depr;
                $bookValue -= $depr;
                if ($bookValue <= ($this->residual_value ?? 0)) break;
            }
            // Partial year
            $partial = $years - floor($years);
            if ($partial > 0 && $bookValue > ($this->residual_value ?? 0)) {
                $depr = min($bookValue - ($this->residual_value ?? 0), $bookValue * $rate * $partial);
                $acc += $depr;
            }
            return $acc;
        }
        return 0;
    }

    public function getBookValueAttribute(): float
    {
        return max($this->residual_value ?? 0, $this->acquisition_cost - $this->accumulated_depreciation);
    }

    public function getDepreciationRateAttribute(): float
    {
        if ($this->acquisition_cost <= 0) return 0;
        return round(($this->accumulated_depreciation / $this->acquisition_cost) * 100, 1);
    }

    public function getDepreciationStatusAttribute(): string
    {
        if (!$this->useful_life_years) return 'not_set';
        $rate = $this->depreciation_rate;
        if ($rate >= 100) return 'fully_depreciated';
        if ($rate >= 75) return 'near_fully';
        if ($rate >= 50) return 'half';
        return 'active';
    }

    public function getTotalRepairCostAttribute(): float
    {
        return (float) $this->repairs()->sum('cost');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // ─── Auto-generate Asset Code ─────────────────────────────
    public static function generateAssetCode(int $categoryId): string
    {
        $category = Category::find($categoryId);
        $prefix = $category->code_prefix ?? Category::generatePrefix($category->category_name ?? 'AST');
        
        $year = now()->format('Y');
        $month = now()->format('m');
        $ym = $year . $month;
        
        // Count existing assets with same prefix and year-month
        $count = self::where('asset_code', 'like', "{$prefix}-{$ym}-%")->count();
        $running = $count + 1;
        
        return "{$prefix}-{$ym}-" . str_pad($running, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the next asset code without creating an asset
     */
    public static function previewAssetCode(int $categoryId): string
    {
        return self::generateAssetCode($categoryId);
    }
}
