<?php
/**
 * Created by PhpStorm.
 * User: raheel
 * Date: 2/11/2019
 * Time: 5:15 PM
 */

namespace App\Traits;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;


trait GuzzleApiCallTrait {
    /**
     * @param $url
     * @param array $header
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($url,$header= [])
    {
       try{
           $res = (new Client())->request('GET',$url,['headers'=>$header]);
           return $res->getBody()->getContents();
       }catch (Exception $e) {
           return ['status'=>false,'message'=>$e->getMessage(),'result'=>[]];
       }
    }

    public function post()
    {

    }
}



