<?php class Email_model extends CI_Model{
    
    public function __construct()
    {
        parent::__construct();
        
    }
    public function get_template_by_id($id){
        $this->db->where("template_id",$id);
        $q = $this->db->get("email_templates");
        return $q->row();
    }
    function send($to,$subject,$message){
        $options = get_options_by_type("email_settings");
        if($options["mail_via"] == "smtp"){
            $this->load->library('email');
            $config['protocol'] = 'smtp';
            $config['mailpath'] = '/usr/sbin/sendmail';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            $config['smtp_host'] = $options['smtp_host'];
            $config['smtp_user'] = $options['smtp_user'];
            $config['smtp_pass'] = $options['smtp_pass'];
            $config['smtp_port'] = $options['smtp_port'];
            $config['smtp_crypto'] = $options['smtp_crypto'];
            $config['mailtype'] = 'html';

            $this->email->initialize($config);

            $this->email->from($options['email_sender'],(isset($options["name"])) ? $options["name"] : "");
            $send_to = array();
            foreach($to as $k=>$t){
                $send_to[] = $k;
            }
            $this->email->to($send_to);
            $this->email->subject($subject);
            $this->email->message($message);
            return $this->email->send();
        }else if($options["sendgrid"]){
            $this->load->library("emailsenders/sendgrid_email");
            return $this->sendgrid_email->send($to, $subject, $message);
        }else{
            $this->load->library('email');
            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            $config['mailtype'] = 'html';

            $this->email->initialize($config);

            $this->email->from($options['email_sender'],$options["name"]);
            $send_to = array();
            foreach($to as $k=>$t){
                $send_to[] = $k;
            }
            $this->email->to($send_to);
            $this->email->subject($subject);
            $this->email->message($message);
            return $this->email->send();
        }
    }
    function send_welcome_mail($user){
            if (isset($user->user_email) && $user->user_email != null) {
                $language = getheaderlanguage();
                $template = $this->get_template_by_id(1);
                $message = $template->email_message_en;
                $subject = $template->email_subject_en;
                if ($language == "arabic") {
                    $message = $template->email_message_ar;
                    $subject = $template->email_subject_ar;
                }
                $this->lang->load('base', $language);

                //$tags = explode(",",$template->email_tags);
                $subject = str_replace(array("##user_full_name##","##user_phone##"), array($user->user_firstname." ".$user->user_lastname,$user->user_phone), $subject);
                $message = str_replace(array("##user_full_name##","##user_phone##"), array($user->user_firstname." ".$user->user_lastname,$user->user_phone), $message);
                $to = array();
                $to[$user->user_email] = $user->user_firstname;
                $message = $this->load->view('emails/email_base_template', array("subject"=>$subject,"message"=>$message), true);
                return $this->send($to, $subject, $message);
            }
    }
    
    function send_order_mail($order,$order_items){
        if (isset($order->user_email) && $order->user_email != null) {
            $language = getheaderlanguage();
            $template = $this->get_template_by_id(2);
            if (!empty($template)) {
                $message = $template->email_message_en;
                $subject = $template->email_subject_en;
                if ($language == "arabic") {
                    $message = $template->email_message_ar;
                    $subject = $template->email_subject_ar;
                }
                $this->lang->load('base', $language);

                $settings = get_options_by_type(array("billing","general_setting"));
                $body = $this->load->view("emails/order_email_template", array("order"=>$order,"items"=>$order_items,"setting"=>$settings,"language"=>$language), true);
                $message = str_replace(array("##order_details##"), array($body), $message);
            
                $to = array();
                $to[$order->user_email] = $order->user_firstname;
                $subject = str_replace(array("#order_no#","#net_amount#"), array($order->order_no,$order->net_amount), $subject);
                $message = $this->load->view('emails/email_base_template', array("subject"=>$subject,"message"=>$body), true);
                return $this->send($to, $subject, $message);
            }
            return false;
        }
    }
   
    function new_user_mail($user){
        $language = getheaderlanguage();
        $template = $this->get_template_by_id(3);
        $message = $template->email_message_en;
        $subject = $template->email_subject_en;
        if($language == "arabic"){
            $message = $template->email_message_ar;
            $subject = $template->email_subject_ar;
        }
        $this->lang->load('base',$language);

        //$tags = explode(",",$template->email_tags);
        $subject = str_replace(array("##user_full_name##","##user_phone##"),array($user->user_firstname." ".$user->user_lastname,$user->user_phone),$subject);
        $message = str_replace(array("##user_full_name##","##user_phone##"),array($user->user_firstname." ".$user->user_lastname,$user->user_phone),$message);
        $to = array();
        $app_email = get_options(array("app_email","name"));
        $to[$app_email["app_email"]] = $app_email["name"];
        $message = $this->load->view('emails/email_base_template',array("subject"=>$subject,"message"=>$message),TRUE);
        return $this->send($to,$subject,$message);
}

function new_order_mail($order,$order_items){
    $language = getheaderlanguage();
    $template = $this->get_template_by_id(4);
    if (!empty($template)) {
        $message = $template->email_message_en;
        $subject = $template->email_subject_en;
        if($language == "arabic"){
            $message = $template->email_message_ar;
            $subject = $template->email_subject_ar;
        }
        $this->lang->load('base', $language);

        $settings = get_options_by_type(array("billing","general_setting"));
        $body = $this->load->view("emails/order_email_template", array("order"=>$order,"items"=>$order_items,"setting"=>$settings,"language"=>$language), true);
        $message = str_replace(array("##order_details##"), array($body), $message);
        
        $to = array();
        $app_email = get_options(array("app_email","name"));
        $to[$app_email["app_email"]] = $app_email["name"];
        $subject = str_replace(array("#order_no#","#net_amount#"), array($order->order_no,$order->net_amount), $subject);
        $message = $this->load->view('emails/email_base_template', array("subject"=>$subject,"message"=>$body), true);
        return $this->send($to, $subject, $message);
    }
    return false;
}
function contact_request_mail($contact){
    $language = getheaderlanguage();
    $template = $this->get_template_by_id(5);
    $message = $template->email_message_en;
    $subject = $template->email_subject_en;
    if($language == "arabic"){
        $message = $template->email_message_ar;
        $subject = $template->email_subject_ar;
    }
    $this->lang->load('base',$language);

    //$tags = explode(",",$template->email_tags);
    $subject = str_replace(array("##full_name##","##phone##","##message##","##date_time##"),array($contact["fullname"],$contact["phone"],$contact["message"],date(DEFAULT_DATE_TIME_FORMATE)),$subject);
    $message = str_replace(array("##full_name##","##phone##","##message##","##date_time##"),array($contact["fullname"],$contact["phone"],$contact["message"],date(DEFAULT_DATE_TIME_FORMATE)),$message);
    $to = array();
    $app_email = get_options(array("app_email","name"));
    $to[$app_email["app_email"]] = $app_email["name"];
    $message = $this->load->view('emails/email_base_template',array("subject"=>$subject,"message"=>$message),TRUE);
    return $this->send($to,$subject,$message);
}
function new_contact_request_mail($contact){
    $language = getheaderlanguage();
    $template = $this->get_template_by_id(5);
    $message = $template->email_message_en;
    $subject = $template->email_subject_en;
    if($language == "arabic"){
        $message = $template->email_message_ar;
        $subject = $template->email_subject_ar;
    }
    $this->lang->load('base',$language);

    //$tags = explode(",",$template->email_tags);
    $date_time = date(DEFAULT_DATE_TIME_FORMATE);
    $subject = str_replace(array("##full_name##","##phone##","##message##","##date_time##"),array($contact["fullname"],$contact["phone"],$contact["message"],$date_time),$subject);
    $message = str_replace(array("##full_name##","##phone##","##message##","##date_time##"),array($contact["fullname"],$contact["phone"],$contact["message"],$date_time),$message);
    $to = array();
    $app_email = get_options(array("app_email","name"));
    $to[$app_email["app_email"]] = $app_email["name"];
    $message = $this->load->view('emails/email_base_template',array("subject"=>$subject,"message"=>$message),TRUE);
    return $this->send($to,$subject,$message);
}
}