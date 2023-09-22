<?php
namespace App\Components;
use App\Models\Api\V1\Student;
use App\Models\Api\V1\CoinsConfig;
use App\Models\Api\V1\CoinsDetails;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class Coins{

    public function add_coin_to_student($student_id,$group_id,$code,$details=array())
    {
        $conf=CoinsConfig::where('code',$code)->first();
        // dd($conf);
        $cd=CoinsDetails::create([
            'student_id'=>$student_id,
            'group_id'=>$group_id,
            'coins_config_id'=>$conf->id,
            'debit'=>$conf->point_plus,
            'credit'=>0,
            'details'=>$details
        ]);
        if ($cd)
        {
            $student = Student::find($student_id);
            $student->coins += $conf->point_plus;
            $student->save();
            $n_helper=new NotificationHelper();
            $n_helper->send_notification_to_student($student_id,"2",['title'=>'Points 💰','page'=>'point_history','description'=>'You have earned ' . $conf->point_plus. 'points 🎉']);
        }
    }

    public function add_selected_coin_to_student($student_id,$group_id,$code,$add_coin,$details=array())
    {
        $conf=CoinsConfig::where('code',$code)->first();
        // dd($conf);
        $cd=CoinsDetails::create([
            'student_id'=>$student_id,
            'group_id'=>$group_id,
            'coins_config_id'=>$conf->id,
            'debit'=>$add_coin,
            'credit'=>0,
            'details'=>$details
        ]);
        if ($cd)
        {
            $student = Student::find($student_id);
            $student->coins += $add_coin;
            $student->save();
            $n_helper=new NotificationHelper();
            $n_helper->send_notification_to_student($student_id,2,['title'=>'Points','page'=>'point_history','description'=>'You have earned ' . $add_coin.' points 🎉']);
        }
    }

    public function subtract_coin_for_survey($student_id,$group_id,$code,$coin,$details=array())
    {
        // $conf=CoinsConfig::where('code',$code)->first();
        // $cd=CoinsDetails::create([
        //     'student_id'=>$student_id,
        //     'coins_config_id'=>$conf->id,
        //     'debit'=>0,
        //     'credit'=>$coin,
        //     'details'=>$details
        // ]);
        // if ($cd)
        // {
        $student = Student::find($student_id);
        $student->coins_for_buy -= $coin;
        $student->save();

        // }
    }

    public function subtract_coin_to_student($student_id,$group_id,$code,$details=array())
    {
        $conf=CoinsConfig::where('code',$code)->first();
        $cd=CoinsDetails::create([
            'student_id'=>$student_id,
            'group_id'=>$group_id,
            'coins_config_id'=>$conf->id,
            'debit'=>0,
            'credit'=>$conf->point_minus,
            'details'=>$details
        ]);
        if ($cd)
        {
            $student = Student::find($student_id);
            $student->coins -= $conf->point_minus;
            $student->save();
            $n_helper=new NotificationHelper();
            $n_helper->send_notification_to_student($student_id,2,['title'=>'Points','page'=>'point_history','description'=>'Subtracted points: ' . $conf->point_minus]);
        }
    }

}
