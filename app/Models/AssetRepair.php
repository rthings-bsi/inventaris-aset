<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetRepair extends Model
{
    protected $table = 'asset_repairs';
    protected $primaryKey = 'id_asset_repairs';

    const STATUS_REPORTED = 'reported';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_APPROVED = 'approved';
    const STATUS_HANDED_OVER = 'handed_over';

    protected $fillable = [
        'id_assets', 'id_asset_loans', 'repair_date', 'description', 'repair_type',
        'cost', 'vendor', 'notes', 'status', 'created_by',
        'approved_by', 'approved_at', 'handover_by', 'handover_date',
        'new_condition_grade', 'handover_notes',
    ];

    protected $casts = [
        'repair_date' => 'date',
        'cost' => 'decimal:2',
        'approved_at' => 'datetime',
        'handover_date' => 'date',
    ];

    /** All valid workflow statuses in order */
    public static array $workflowFlow = [
        self::STATUS_REPORTED,
        self::STATUS_IN_PROGRESS,
        self::STATUS_COMPLETED,
        self::STATUS_APPROVED,
        self::STATUS_HANDED_OVER,
    ];

    /** Labels for each status */
    public static array $statusLabels = [
        self::STATUS_REPORTED => 'Dilaporkan',
        self::STATUS_IN_PROGRESS => 'Dalam Perbaikan',
        self::STATUS_COMPLETED => 'Selesai Perbaikan',
        self::STATUS_APPROVED => 'Disetujui QC',
        self::STATUS_HANDED_OVER => 'Serah Terima',
    ];

    /** Who can perform each action */
    public static array $actionRoles = [
        'report' => ['admin', 'staff'],
        'start_repair' => ['admin'],
        'complete_repair' => ['admin', 'vendor'],
        'approve' => ['admin', 'supervisor'],
        'handover' => ['admin'],
    ];

    // ─── Relationships ─────────────────────────────────────────

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'id_assets');
    }

    public function loan()
    {
        return $this->belongsTo(AssetLoan::class, 'id_asset_loans');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function handoverOfficer()
    {
        return $this->belongsTo(User::class, 'handover_by');
    }

    // ─── Workflow Methods ──────────────────────────────────────

    public function canTransitionTo(string $targetStatus): bool
    {
        $flow = self::$workflowFlow;
        $currentIdx = array_search($this->status, $flow);
        $targetIdx = array_search($targetStatus, $flow);

        if ($currentIdx === false || $targetIdx === false) return false;

        // Must be sequential — can only move to the NEXT status
        return $targetIdx === $currentIdx + 1;
    }

    public function getNextStatusAttribute(): ?string
    {
        $flow = self::$workflowFlow;
        $currentIdx = array_search($this->status, $flow);
        if ($currentIdx === false || $currentIdx >= count($flow) - 1) return null;
        return $flow[$currentIdx + 1];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusProgressAttribute(): int
    {
        $flow = self::$workflowFlow;
        $idx = array_search($this->status, $flow);
        if ($idx === false) return 0;
        return (int) round(($idx / (count($flow) - 1)) * 100);
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

    /** Suggested grade uplift after repair */
    public function getSuggestedGradeAttribute(): string
    {
        if (!$this->asset) return 'C';
        $currentGrade = $this->asset->condition;
        return match($currentGrade) {
            'E' => 'D',
            'D' => 'C',
            'C' => 'B',
            default => $currentGrade,
        };
    }
}
