<?php
namespace App\Models\Api\V1;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Staff extends Model
{
    use Userstamps;

    const EMPLOYEE_TYPE_FULL_TIME_2X = 0;
    const EMPLOYEE_TYPE_FULL_TIME = 1;
    const EMPLOYEE_TYPE_PART_TIME = 2;

    protected $table = 'staff';

    protected $fillable = [
        'name',
        'nickname',
        'phone',
        'photo',
        'date_of_birth',
        'certificate',
        'degree',
        'plastic_card',
        'teaches',
        'week_working_days',
        'employment_type',
        'branch_id',
        'status',
        'gender',
        'tutor',
        'experience',
        'graduated_with_success',
        'max_group_number',
        'group_type',
        'retention',
        'work_days',
        'schedule',
        'staff_info',
        'shift',
        'workly_employee_id'
    ];

    protected $casts = [
        'schedule' => 'array',
    ];



    public function user()
    {
        return $this->hasOne(User::class, 'type_id')->where('type', 'staff');
    }


}
