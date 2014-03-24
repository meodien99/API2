<?php
/**
 * Created by PhpStorm.
 * User: madcat
 * Date: 3/24/14
 * Time: 8:54 PM
 */
class ApiCaller {
    private $api_url;

    public function __construct($url){
            $this->api_url = $url;
    }

    public function sendRequest($request_params){

        //initialize and setup the curl handler
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$this->api_url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,count($request_params));
        curl_setopt($ch,CURLOPT_POSTFIELDS,$request_params);

        //execute the request
        $result = curl_exec($ch);

        $result = json_decode($result,true);
        if($result == false){
            throw new Exception("Request was not correct !");
        }

        return $result;
    }
}