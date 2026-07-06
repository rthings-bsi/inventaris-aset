<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditCriteriaGroup extends Model
{
    protected $table = 'audit_criteria_groups';
    protected $primaryKey = 'id_criteria_groups';
    protected $fillable = ['name', 'description', 'category_type'];

    public function items()
    {
        return $this->hasMany(AuditCriteriaItem::class, 'id_criteria_groups');
    }
}
