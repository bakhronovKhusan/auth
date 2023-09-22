<?php
namespace App\Models\Api\V1;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Student extends Model
{
    use Userstamps;

    protected $fillable = [
        'name',
        'surname',
        'gender',
        'photo',
        'phone',
        'phone2',
        'date_of_birth',
        'ref',
        'balance',
        'comment',
        'branch_id',
        'email',
        'address',
        'expectation',
        'degree',
        'oferta',
        'oferta_time',
        'created_by',
        'updated_by',
        'called',
        'grant',
        'grant_details',
        'representative',
        'representative_phone',
        'representative_kinship_degree',
        'target',
        'ielts_target',
        'university',
        'when_enter',
        'first_level',
        'first_level_type',
        'grammar_result',
        'speaking_level',
        'coins',
        'coins_for_buy',
        'coins_exchanged',
        'referral',
    ];

    protected $casts = [
        'grant_details' => 'array',
    ];
    public function courses()
    {
        return $this->belongsToMany(Course::class)->withPivot(
            'status',
            'comment',
            'branch_id'
        );
    }

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'level_student')->withPivot(
            'student_time',
            'days',
            'status'
        );
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_student')->withPivot(
            'status',
            'balance',
            'lessons_left',
            'comment',
            'exception_sum',
            'exception_status',
            'created_at',
            'updated_at',
            'activated_at'
        );
    }

    public function latest_group()
    {
        // return $this->hasMany('App\StudentRequest')->latest();
        return $this->belongsToMany(Group::class, 'group_student')->withPivot(
            'status',
            'balance',
            'lessons_left',
            'comment',
            'exception_sum',
            'exception_status',
            'created_at',
            'updated_at',
            'activated_at'
        )->latest();
    }

    public function a_p1_in_groups()
    {
        return $this->belongsToMany(Group::class, 'group_student')
            ->withPivot('status', 'balance', 'lessons_left', 'created_at', 'exception_sum','updated_at','activated_at')
            ->wherePivotIn('status', ['a', 'p1']);
    }

    public function for_pay_groups()
    {
        return $this->belongsToMany(Group::class, 'group_student')
            ->withPivot('status', 'balance', 'lessons_left', 'created_at', 'exception_sum','updated_at')
            ->wherePivotIn('status', ['a', 'p1','np']);
    }

    public function real_active_in_groups()
    {
        return $this->belongsToMany(Group::class, 'group_student')
            ->withPivot('group_id','status', 'balance', 'lessons_left', 'created_at', 'exception_sum','exception_status','updated_at','activated_at')
            ->where('groups.status','a')
            ->wherePivot('status', 'a');
    }
    public function failed_in_groups()
    {
        return $this->belongsToMany(Group::class, 'group_student')
            ->withPivot('status', 'balance', 'lessons_left', 'created_at', 'exception_sum','updated_at')
            ->where('groups.status','a')
            ->wherePivot('status', 'fs');
    }

    public function not_archived_in_groups()
    {
        return $this->belongsToMany(Group::class, 'group_student')
            ->withPivot('status', 'balance', 'lessons_left', 'created_at', 'exception_sum','updated_at')
            ->wherePivot('status', '!=', 'ar');
    }

    public function trial_in_groups()
    {
        return $this->belongsToMany(Group::class, 'group_student')
            ->withPivot(
                'status',
                'balance',
                'lessons_left',
                'comment',
                'called',
                'created_at',
                'updated_at',
                'exception_sum'
            )
            ->wherePivotIn('status', ['p1', 'np']);
    }

    public function in_groups_missed_1()
    {
        return $this->belongsToMany(Group::class, 'group_student')
            ->withPivot('missed_lessons', 'called', 'comment', 'exception_sum')
            ->wherePivot('missed_lessons', '>=', 1)
            ->wherePivotIn('status', ['a', 'f'])
            ->where('groups.status', 'a');
    }

    public function in_groups_missed_2()
    {
        return $this->belongsToMany(Group::class, 'group_student')
            ->withPivot('missed_lessons','exception_sum')
            ->wherePivot('missed_lessons', '>=', 2)
            ->wherePivotIn('status', ['a', 'f'])
            ->where('groups.status', 'a');
    }

    public function in_groups_missed_3()
    {
        return $this->belongsToMany(Group::class, 'group_student')
            ->withPivot('missed_lessons')
            ->wherePivot('missed_lessons', '>=', 3)
            ->wherePivotIn('status', ['a', 'f']);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'type_id')->where('type', 'student');
    }


}
