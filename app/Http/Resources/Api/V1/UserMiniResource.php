<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserMiniResource extends JsonResource
{
    public function toArray($request)
    {
        $roles = (auth('api')->user()->hasRole('Offline Hunter') ? 'Offline Hunter' :
            (auth('api')->user()->hasRole('Online Hunter') ? 'Online Hunter' : false ));
        return [
            'token'       => $this->token ?? null,
            'branch_id'   => 10 ?? auth('api')->user()->staff->branch_id,
            'user_id'     =>  auth('api')->user()->id,
            'roles'       => $roles ? $roles : '',
            'permissions' => auth('api')->user()->getAllPermissions() ?? [],
        ];
    }
}
