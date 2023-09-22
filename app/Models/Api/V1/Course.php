<?php
namespace App\Models\Api\V1;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Course extends Model{
    use Userstamps;

    protected $fillable = [
        'name'
    ];

    public function levels(){
        return $this->hasMany(Level::class);
    }

}
