<?php

namespace App\Helpers;

class ResponseHelper {
    public static function success($data = [], $message = "Success!", $status  = 200){
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    
    }
    public static function failedValidation($message = "Failed!"){
        return response()->json([
            'status' => false,
            'message' => $message,
        ], 422);
    }
    public static function failedNoData($message = "Data not found!"){
        return response()->json([
            'status' => false,
            'message' => $message,
        ], 404);
    }
}
