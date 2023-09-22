<?php

namespace App\Models\Api\V1;

use Illuminate\Database\Eloquent\Model;

class GroupRoadmap extends Model
{
    // Disable default updated at timestamp
    const UPDATED_AT = null;

    protected $table = 'group_roadmap';

    protected $fillable = [
        'id',
        'group_id',
        'level_id',
        'week_num',
        'date',
        'week_day_num',
        'is_done',
        'is_exam_day'
    ];

    // set default updated_by userstamp null
    public function setUpdatedByAttribute($value) {
        return null;
    }

    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function level(){
        return $this->belongsTo(Level::class);
    }
}
