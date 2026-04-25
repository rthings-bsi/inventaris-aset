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
        'acquisition_cost' => 'decimal:2'
    ];

    // Filter dinamis untuk menggantikan duplikasi di Controller
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['category'] ?? false, function ($query, $category) {
            $query->where('id_categories', $category);
        });

        $query->when($filters['status'] ?? false, function ($query, $status) {
            $query->where('status', $status);
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
