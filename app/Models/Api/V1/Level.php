<?php

namespace App\Models\Api\V1;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Level extends Model
{
    use Userstamps;

    protected $fillable = [
        'name',
        'course_id',
        'teaches_2_teachers',
        'image',
        'description',
        'exam_time',
    ];

    protected $casts = [
        'exam_week_range' => 'array'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
