<?php

namespace App\Helpers;

class ResponseHelper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Function: Common function to display success - JSON response
     * @param mixed $status
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($status = "success", $data = [], $message =  null, $statusCode = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'statusCode' => $statusCode
        ], $statusCode);
    }

     /**
     * Function: Common function to display error - JSON response
     * @param mixed $status
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($status = "error", $message = null, $statusCode = 400)
    {
        return response()->json([
            'status' => $status,
            'message' => $message
        ], $statusCode);
    }
}
