<?php

namespace App\Http\Response;

use Illuminate\Http\JsonResponse;

class BaseResponse extends JsonResponse
{
    public static function error($data = null, $code = null, $message = [], $error = []): JsonResponse
    {
        $code = $code ?? 500;
        $result['status'] = false;
        $result['code'] = $code;
        $result['message'] = $message;
        $result['data'] = $data;
        return new JsonResponse($result, $code);
    }

    public static function success($data = null, $code = null): JsonResponse
    {
        $code = $code ?? 200;
        $result['status'] = true;
        $result['code'] = $code;
        $result['data'] = $data;
        return new JsonResponse($result, $code);
    }

}
