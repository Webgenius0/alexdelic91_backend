<?php
namespace App\Traits;

use Illuminate\Http\Request;

trait ApiResponse
{
    public function success($data, $message = null, $code = 200)
    {

    }

    public function error($data, $message = null, $code = 500)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data,
            'code' => $code
        ], $code);
    }
}
