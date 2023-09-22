<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchStaff extends Model{

    protected $table = 'branch_staff';

    protected $fillable = [
		'branch_id', 'staff_id', 'main',
	];

    public function branch(){
        return $this->belongsTo('App\Branch')->withDefault();
    }

    public function staff(){
        return $this->belongsTo('App\Staff')->withDefault();
    }
}
