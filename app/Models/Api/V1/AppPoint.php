<?php
namespace App\Models\Api\V1;

use Illuminate\Database\Eloquent\Model;


class AppPoint extends Model{

    protected $table = 'app_points';

    protected $fillable = [
        'student_id'
    ];

    public function student(){
        return $this->belongsTo(Student::class);
    }

}
