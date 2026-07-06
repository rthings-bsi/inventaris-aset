<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditCriteriaItem extends Model
{
    protected $table = 'audit_criteria_items';
    protected $primaryKey = 'id_criteria_items';
    protected $fillable = ['id_criteria_groups', 'name', 'description', 'weight'];

    public function group()
    {
        return $this->belongsTo(AuditCriteriaGroup::class, 'id_criteria_groups');
    }
}
