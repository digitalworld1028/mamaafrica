<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . 'third_party/vendor/autoload.php');

class Nexmo_sms_gateway{
    public $client ;
    public function __construct()
    {
        /**
        * Call App_gateway __construct function
        */
		
        
        
    }
    public function send_sms($to,$sms_text){
		
		$options = get_options(array("nexmo_api_key","nexmo_secret_key","nexmo_from"));
        if($options["nexmo_api_key"] == "" || $options["nexmo_secret_key"] == "" || $options["nexmo_from"] == "")
            return false;
		
		$this->client = new Nexmo\Client(new Nexmo\Client\Credentials\Basic($options["nexmo_api_key"], $options["nexmo_secret_key"]));
        if($this->client != NULL){
            $message = $this->client->message()->send([
                'to' => $to,
                'from' => $options["nexmo_from"],
                'text' => $sms_text
            ]);
            //return "Sent message to " . $message['to'] . ". Balance is now " . $message['remaining-balance'] . PHP_EOL;
            return true;
        }
		else{
			return false;
		}
    }
}