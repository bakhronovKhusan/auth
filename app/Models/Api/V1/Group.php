<?php
namespace App\Models\Api\V1;

use Carbon\CarbonPeriod;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use Userstamps;
    protected $appends = ['count_lesson'];
    protected $fillable = [
        'name',
        'time',
        'days',
        'exact_days',
        'status',
        'fee',
        'type',
        'is_online',
        'is_corporate',
        'salary_for_corporate',
        'level_id',
        'branch_id',
        'selection_started_date',
        'selection_end_date',
        'group_started_date',
        'group_end_date',
        'tariff_id',
        'is_exception',
        'exception_salary_from_one_student',
        'supervisor_id',
        'tg_group_chat_id',
        'tg_group_link',
        'course_book',
        'is_only_girls',
        'is_30_plus',
        'is_vip',
        'is_kids',
        'is_rus',
    ];

    protected $casts = [
        'exact_days' => 'array',
    ];

    public function getCountLessonAttribute()
    {
        $d=date("Y-m-d");
        $k=0;
        if ($this->days=="mwf"):
            while ($d<=$this->group_end_date)
            {
                $timestamp = strtotime($d);
                $day = date('D', $timestamp);
                if (($day=='Mon') || ($day=='Wed') || ($day=='Fri'))
                    $k++;
                $d = date('Y-m-d', strtotime($d .' +1 day'));
            }
        endif;
        if ($this->days=="tts"):
            while ($d<=$this->group_end_date)
            {
                $timestamp = strtotime($d);
                $day = date('D', $timestamp);
                if (($day=='Tue') || ($day=='Thu') || ($day=='Sat'))
                    $k++;
                $d = date('Y-m-d', strtotime($d .' +1 day'));
            }
        endif;
        if ($this->days=="ed"):
            while ($d<=$this->group_end_date)
            {
                $timestamp = strtotime($d);
                $day = date('D', $timestamp);
                if ($day!='Sun')
                    $k++;
                $d = date('Y-m-d', strtotime($d .' +1 day'));
            }
        endif;
        if ($this->days=="ss"):
            while ($d<=$this->group_end_date)
            {
                $timestamp = strtotime($d);
                $day = date('D', $timestamp);
                if (($day=='Sat') || ($day=='Sun') )
                    $k++;
                $d = date('Y-m-d', strtotime($d .' +1 day'));
            }
        endif;
        if ($this->days=="other"):
            while ($d<=$this->group_end_date)
            {
                $timestamp = strtotime($d);
                $day = date('D', $timestamp);
                if (in_array($day,$this->exact_days))
                    $k++;
                $d = date('Y-m-d', strtotime($d .' +1 day'));
            }
        endif;
        return $k;
    }

    public function getLessonCount($period = null){
        if($period){
            $from_date = new \DateTime($period[0]);
            $to_date = new \DateTime($period[1]);
        }
        else{
            $from_date = date("Y-m-01");
            $to_date = date("Y-m-t");
            $from_date = new \Datetime($from_date);
            $to_date = new \Datetime($to_date);
        }
        if(
            $from_date->format("Y-m-d")==$from_date->format("Y-m")."-01" &&
            $from_date->format("Y-m-t")==$to_date->format("Y-m-d")
        ){
            $group_lesson_days = $this->getDaysOfWeek();
            $number_of_lesson_days = 0;
            $period = CarbonPeriod::create($from_date, $to_date);
            foreach($period as $date){
                foreach($group_lesson_days as $group_lesson_day){
                    if($date->format("D")==$group_lesson_day){
                        $number_of_lesson_days++;
                    }
                }
            }
        }
        else{
            $number_of_lesson_days = $this->getLessonCountByStaticDays();
        }
        return $number_of_lesson_days;
    }

    public function level()
    {
        return $this->belongsTo(Level::class)->withDefault();
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'group_student')
            ->withPivot('status', 'balance', 'lessons_left', 'created_at','updated_at', 'administrator_id', 'exception_sum', 'comment','missed_trials')
            ->orderBy('pivot_created_at', 'desc');
    }
    public function active_students()
    {
        return $this->belongsToMany(Student::class, 'group_student')
            ->withPivot('status','created_at','updated_at')
            ->wherePivot('status', 'a')
            ->orderBy('pivot_created_at', 'desc');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class)->withDefault();
    }

    public function currentWeek(){
        return $this->hasOne(GroupRoadmap::class)->orderBy("id","DESC");
    }

    public function support(){
        // return $this->hasOne(Staff::class)->latest();
        return $this->belongsTo(Staff::class,'supervisor_id','id');
    }

    public function teachers()
    {
        return $this->belongsToMany(
            Staff::class,
            'group_teacher',
            'group_id',
            'teacher_id'
        )->withPivot('owner');
    }

    public function support_teachers()
    {
        return $this->belongsToMany(
            Staff::class,
            'group_teacher',
            'group_id',
            'teacher_id'
        )->withPivot('owner');
    }
}
