<?php

namespace App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AuthRequest;
use App\Services\AuthService;
use Illuminate\Cache\RateLimiter;
use Predis\Client;

class AuthController extends Controller
{
    private AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function login(AuthRequest $request, RateLimiter $rate_limiter)
    {
        $result = $this->service->login($request, $rate_limiter);
        if($result->getStatusCode()==200){
            $this->service->registerToRedis();
        }
        return $result;
    }
    public function refresh()
    {
        return $this->service->respondWithToken(auth('api')->refresh());
    }

}
