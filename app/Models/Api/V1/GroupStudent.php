<?php
namespace App\Models\Api\V1;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class GroupStudent extends Model{
    use Userstamps;
    protected $table = 'group_student';

    protected $fillable = [
        'group_id',
        'student_id',
        'status',
        'balance',
        'lessons_left',
        'missed_lessons',
        'missed_trials',
        'np_days_count',
        'called',
        'called_count',
        'debtors_sms_count',
        'comment',
        'exception_sum',
        'exception_sum_expire_date',
        'exception_sum_reason',
        'exception_by',
        'exception_accepted_by',
        'exception_status',
        'exception_cancel',
        'exception_change_time',
        'exception_created_by',
        'exception_created_at',
        'archived_at',
        'comment_failed',
        'administrator_id',
        'activated_at'
    ];

    public function group(){
        return $this->belongsTo(Group::class)->withDefault();
    }

    public function student(){
        return $this->belongsTo(Student::class)->withDefault();
    }
}
