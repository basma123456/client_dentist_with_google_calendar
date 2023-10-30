<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendResponse( $statusCode = null  , $message= null  ,$data = null)
    {
        $array = [
            'status' => true,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,

        ];
        return response($array );
    }

    public function sendError( $statusCode = null ,$message= null  ,$data = null , $errorMessages = [])
    {
        $array = [

            'status' => false,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,

        ];

        if(!empty($errorMessages)){
            $array['message'] = $errorMessages;
        }

        return response($array );
    }



//    public function sendResponse($result, $message)
//    {
//        $response = [
//            'success' => true,
//            'data'    => $result,
//            'message' => $message,
//        ];
//
//
//        return response()->json($response, 200);
//    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
//    public function sendError($error, $errorMessages = [], $code = 404)
//    {
//        $response = [
//            'success' => false,
//            'message' => $error,
//        ];
//
//
//        if(!empty($errorMessages)){
//            $response['data'] = $errorMessages;
//        }
//
//
//        return response()->json($response, $code);
//    }
}
