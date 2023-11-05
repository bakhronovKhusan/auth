<?php

namespace App\Services;

use App\Components\Coins;
use App\Http\Requests\Api\V1\AuthRequest;
use App\Http\Resources\Api\V1\UserMiniResource;
use App\Http\Response\BaseResponse;
use App\Models\Api\V1\AppPoint;
use App\Models\Api\V1\GroupStudent;
use App\Models\User;
use Illuminate\Cache\RateLimiter;
use Predis\Client;

class AuthService
{
    public $token;

    public $is_app = false;
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'scheme' => 'tcp',
            'host' => env('REDIS_HOST','192.168.0.2'),
            'port' => env('REDIS_PORT',6379),
            'password' => env('REDIS_PASSWORD','Gf4ezYLeNB32zvtTpkFQD/co0D8ZnrJKoqTbMBiyyQfbpEMyq8sZSy69MquluZIh$')
        ]);
    }

    public function login(AuthRequest $request, RateLimiter $rate_limiter)
    {
        if($this->checkTooManyFailedAttempts($rate_limiter)){
            BaseResponse::error(null,403,'Your phone number is banned. Too many login attempts. Try again later.');
        }
        if(!($user = User::where(['phone'=>$request->phone])->first()) || !( $this->token = auth('api')->setTTL(15)->attempt(request(['phone', 'password'])))) {
            $rate_limiter->hit($this->throttleKey(), $seconds = 1800);
            return BaseResponse::error(null,401,'Unauthorized');
        }
        $rate_limiter->clear($this->throttleKey());
        $result = $this->getToken($request, $user);
        return BaseResponse::success((new UserMiniResource($result)));
    }
    public function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    protected function throttleKey()
    {
        return request('phone');
    }

    protected function checkTooManyFailedAttempts(RateLimiter $ra)
    {
        if (! $ra->tooManyAttempts($this->throttleKey(), 5)) {
            return false;
        }
        return true;
    }

    protected function getToken(AuthRequest $request, $user){
        $this->is_app = ($request->is_app == 1);
        if ($user->type=='student'){
            if($user->student->id) {
                $this->checkCoin($request, $user);
                $user->student->token = $user->token;
                return $user->student;
            }
        }
        $user->staff->token= $this->token;
        return $user->staff;
    }

    protected function checkCoin(AuthRequest $request, $user):void {
        if ($request->is_app == 1) {
            if (AppPoint::where(["student_id" => $user->type_id])->count() == 0) {
                AppPoint::create(["student_id" => $user->type_id]);
                $std_group = GroupStudent::where(['student_id' => $user->type_id, 'status' => 'a'])->first();
                $c = new Coins();
                $details = ["info" => "For start using mobile app"];
                $c->add_coin_to_student($user->type_id, $std_group->group_id, "app", $details);
            }
        }
    }

    public function registerToRedis(){
        $expirationInSeconds = $this->is_app ? config('register.redis_each_expire') : config('register.redis_each_expire_app');
        $this->client->set($this->token , json_encode([
            'roles'       => auth('api')->user()->getRoleNames()->toArray() ?? [],
            'permissions' => auth('api')->user()->getAllPermissions() ?? [],
            'branch_id'   => 10 ?? auth('api')->user()->staff->branch_id,
            'user_id'     =>  auth('api')->user()->id,
            'token'       => $this->token ?? null,
        ]),'EX', $expirationInSeconds);
    }

}
