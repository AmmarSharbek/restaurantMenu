<?php

namespace App\Traits;

trait ResponseHandler
{
    public function success($data = [])
    {
        return  [
            'errors' => [],
            'errorCode' => '',
            'data' => $data,
        ];
        // return response()->json($response, 200);
    }
    public function error($errorCode, $errors = [])
    {
        return  [
            'errors' => $errors,
            'errorCode' => $errorCode,
            'data' => [],
        ];
        // return response()->json($response, $errorCode);
    }
}
