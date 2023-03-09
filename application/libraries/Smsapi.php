<?php
class Smsapi
{
    protected $os_url;
    protected $mobile_prefix;
    protected $is_production = true;
    public function __construct()
    {
            $options = get_options(array("sms_link"));
            $this->os_url = $options["sms_link"]; 
    }
    function send($numbers,$message){
        if(is_array($numbers)){
        }else{
            $numbers = explode(",",$numbers);
        }
        foreach($numbers as $number){
            $number = trim($number);
        }
        $numbers = implode(",",$numbers);

        $message=urlencode($message);
        $url=str_replace('[mobile]',$numbers,$this->os_url);
        $url=str_replace('[message]',$message,$url);

        
        $res = $this->callApi($url);
        if($res != NULL){
            return false;
        }else{
            return true;
        }
    }
    function callApi($url){
        
        //return $fields; 
        //,'X-Api-Key: '.$this->os_key	
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, FALSE);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}