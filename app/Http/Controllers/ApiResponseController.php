<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

class ApiResponseController extends Controller
{

    static public function respond($statuscCode = 200, $message = '', $data = '', $errorType = '',  $headers = [])
    {
        $errorType = ($statuscCode != 200 && $statuscCode!=201 && $errorType=='') ? "Error de peticion" : $errorType;

        $apiResponse = [
            'data' => $data,
            'message' => $message,
        ];

        if ($errorType != '') {
            $apiResponse['error'] = $errorType;
        }
        
    	return Response::json($apiResponse, $statuscCode, $headers);
    }
    
}
