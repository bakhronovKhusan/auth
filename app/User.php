<?php

namespace App;

use App\Models\Api\V1\Staff;
use App\Models\Api\V1\Student;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Wildside\Userstamps\Userstamps;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, Userstamps, HasRoles;
    use HasPermissions;
    use HasApiTokens;
    protected $guard_name = 'api';
    const LANG_UZB = 1;
    const LANG_RUS = 2;

    protected $fillable = [
        'type',
        'type_id',
        'phone',
        'name',
        'email',
        'password',
        'lang',
        'fcm_token',
        'receive_notification',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function student(){
        return $this->belongsTo(Student::class,'type_id')->withDefault();
    }

    public function staff(){
        return $this->belongsTo(Staff::class,'type_id')->withDefault();
    }

    public function getAllPermissions()
    {
        $permissions = [];
        $roles = auth('api')->user()->roles;
        foreach ($roles as $role) {
            $permissions = array_merge($permissions, $role->permissions->pluck('name')->toArray());
        }
        return $permissions;
    }
}
