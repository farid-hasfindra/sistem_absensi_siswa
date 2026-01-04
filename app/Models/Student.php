<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'nis',
        'name',
        'class_id',
        'parent_id',
        'barcode_code',
        'gender',
        'birth_date',
        // 'class' column might be deprecated in favor of relationship, but keeping it for compatibility if needed or removing. 
        // ERD uses relation. Let's assume we migrate data or just use relation.
        // For now, let's keep 'class' string attribute if it exists, or better, remove it from fillable in favor of class_id
        // But migration didn't drop 'class' column. Let's keep both for safe transition or just add new fields.
        'class' // Keeping for backward compat until distinct drop
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
