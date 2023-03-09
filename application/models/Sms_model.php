<?php
class Sms_model extends CI_Model{
    
	function send($to,$message)
	{
		$options = get_options(array("sms_via"));
		if($options['sms_via']=="general")
		{
			$this->load->library("smsapi");
			$ret=$this->smsapi->send($to,$message);
		}
		else if($options['sms_via']=="twillio")
		{
			$this->load->library('Twilio_sms');
			$ret=$this->twilio_sms->send($to,$message);
		}
		else if($options['sms_via']=="nexmo")
		{
			$this->load->library('nexmo_sms_gateway');
			$ret=$this->nexmo_sms_gateway->send_sms($to,$message);
		}
		return ($ret)?1:0;
	}
	/*
	function send_sms($to,$message)
	{
		$options = get_options(array("sms_link"));
		
		$message=urlencode($message);
        $url = $options['sms_link'];
        $url=str_replace('[mobile]',$to,$url);
        $url=str_replace('[message]',$message,$url);
		
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);

        return $curl_scraped_page;
	}
	*/
}
?>