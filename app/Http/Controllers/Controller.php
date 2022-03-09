<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
        /**
     * avoid nested data key of array
     * @param $result, $message
     * @return json datatype
     */
    protected function sendResponse($result,$message){
        $response = [
            'success' => true,
            'message' => $message,
        ];
        if (empty($result['links'])){
            $response['data'] = $result;
        }else{
            $response['data'] = $result['data'];
            $response['links'] = $result['links'];
            $response['meta'] = $result['meta'];
        }
        return response()->json($response,200);
    }

    /**
     * the same idea with above but return error
     * @param $error, $errorMessage, $code status
     * @return json datatype
     */
    protected function sendError($error, $errorMessage = [], $code = 404){
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if (!empty($errorMessage)){
            $response['data'] = $errorMessage;
        }
        return response()->json($response,$code);
    }
}
