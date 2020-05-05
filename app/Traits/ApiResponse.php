<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 1/14/2019
 * Time: 11:29 AM
 */

namespace App\Traits;

/**
 * Trait ApiResponse
 * @package App\Traits
 */
trait ApiResponse
{
    /**
     * @param bool $status
     * @param $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function sucessResponse($status=true,$message,$data=[])
    {
        $response['status'] = $status;
        $response['message'] = $message;
        $response['data'] = (!empty($data) ? $data : (object)$data);
        return response()->json($response);
    }

    /**
     * @param bool $status
     * @param $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($status=false,$message, $data=[])
    {
        $response['status'] =  $status;
        $response['message'] = $message;
        $response['data'] = (!empty($data) ? $data : (object)$data);
        return response()->json($response);
    }

    /**
     * @param bool $status
     * @param $validator
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function validationResponse($status=false,$validator,$data=[])
    {
        $response['status'] =  $status;
        $response['message'] = $validator->errors()->first();
        $response['data'] = (!empty($data) ? $data : (object)$data);
        return response()->json($response);
    }

    public function exceptionResponse()
    {
        $response['status'] =  false;
        $response['message'] = 'Please wait, Server is down';
        $response['data'] = ((object)[]);
        return response()->json($response);
    }
}