<?php

namespace App\Http\Controllers\Api\Traits;

trait responseTrait
{

    function sendError($statusCode = null, $message = null, $data = null, $errorMessages = [])
    {
        $array = [

            'status' => false,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,

        ];

        if (!empty($errorMessages)) {
            $array['message'] = $errorMessages;
        }

        return response($array);
    }

}
