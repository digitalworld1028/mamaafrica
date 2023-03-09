<?php   defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . 'third_party/vendor/autoload.php';

class Twilio_sms 
{
    public function __construct()
    {
        
    }
    
    function send($to_number,$message){
        $options = get_options(array("twilio_sender_id","twilio_account_sid","twilio_auth_token"));
        if($options["twilio_auth_token"] == "" || $options["twilio_account_sid"] == "" || $options["twilio_sender_id"] == "")
            return false;
        
        
        $client = new Twilio\Rest\Client($options["twilio_account_sid"], $options["twilio_auth_token"]);
        $message = $client->messages->create(
          $to_number, // Text this number
          array(
            'from' => $options["twilio_sender_id"], // From a valid Twilio number
            'body' => $message
          )
        );
		
        return $message->sid;
    }
}
?>