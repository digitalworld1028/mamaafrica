<?php   defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . 'third_party/sendgrid-php/vendor/autoload.php';


class Sendgrid_email 
{
    public function __construct()
    {
        
    }
    public function send($to,$subject,$message){
        
        $email = new \SendGrid\Mail\Mail();
        $options = get_options(array("email_sender","sendgrid_key","name"));
         if($options["sendgrid_key"] == "")
            return false;
         
        $email->setFrom($options["email_sender"],$options["name"]);
        $email->setSubject($subject);
        // $to in in formate of array("email"=>"name","email"=>"name")
        $email->addTos($to);
        $email->addContent(
            "text/html", $message
        );
        $sendgrid = new \SendGrid($options["sendgrid_key"]);
        try {
            $response = $sendgrid->send($email);
            if( $response->statusCode() == 200 || $response->statusCode() == 202){
                return true;
            }
			else
			{
				return $response;
			}	
        } catch (Exception $e) {
            return $e;
        }
    }
}