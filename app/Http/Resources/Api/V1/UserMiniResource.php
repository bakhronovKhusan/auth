<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserMiniResource extends JsonResource
{
    public function toArray($request)
    {
        $roles = (auth('api')->user()->hasRole('Offline Hunter') ? 'Offline Hunter' : false ) ??
                    (auth('api')->user()->hasRole('Online Hunter') ? 'Online Hunter' : false ) ??
                        auth('api')->user()->getRoleNames()->toArray();
        return [
            'token'       => $this->token ?? null,
            'roles'       => $roles ?? [],
            'permissions' => auth('api')->user()->getAllPermissions() ?? [],
        ];
    }
}
