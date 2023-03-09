<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Setting extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = $this->router->fetch_class();
        
        if(!_is_admin()){
            redirect("login");
            exit();
            
        }
    }

    /**
	* setting
	* @return setting page
	*/
    public function index(){
            $id = _get_current_user_id();
            
			if($_POST)
            {
                $post = $this->input->post();
                add_options($post,"general_setting",true,true);
            }
            $setting =  get_options_by_type("general_setting");
              
            $this->data["field"] = $setting;
            
            $this->data["time_zones"] = get_timezones_list();
            $keys = array_keys($this->data["time_zones"]);
            $default_timezoe = $keys[0];
            if(isset($setting["default_timezone"]) && $setting["default_timezone"] != ""){
                $default_timezoe = $setting["default_timezone"];
            }
            $this->data["date_time_zone"] = $this->data["time_zones"][$default_timezoe];
            if(IS_TEST){
                $this->data["page_content"] = $this->load->view("disabled",$this->data,true);
            }else{
                $this->data["page_content"] = $this->load->view("admin/settings/general_setting",$this->data,true);
            }
            $this->data["page_script"] = $this->load->view("admin/settings/setting_script",$this->data,true);
		    $this->load->view(BASE_TEMPLATE,$this->data);
    }
    function date_time_zone_json(){
        header('Content-type: text/json');
        $time_zone = get_timezones_list();
        echo json_encode($time_zone[$this->input->post("time_zone")]);
    }
    /*
    public function sms_setting(){
        $id = _get_current_user_id();
        
        if($_POST)
        {
            $post = $this->input->post();
            add_options($post,"sms_setting",true,true);
        }
          $setting =  get_options_by_type("sms_setting");
        
        $this->data["field"] = $setting;
        
        $this->data["page_content"] = $this->load->view("admin/settings/sms_setting",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    */
    /**
	* sms setting
	* @return sms setting page
	*/
    public function sms_setting(){            
		if($_POST)
		{
            
                $post = $this->input->post();
                add_options($post, "sms_setting", true, true);
                _set_flash_message("Setting update successfully", "success");
            
        }
        

		$setting = get_options(array("sms_via","twilio_sender_id","twilio_account_sid","twilio_auth_token","nexmo_from","nexmo_api_key","nexmo_secret_key","sms_link"));
		$this->data["field"] = $setting;
		$this->data["setting_js"]=true;
        $this->data["active_menu_link"] = array(site_url("admin/settings/sms"));
        if(IS_TEST){
            $this->data["page_content"] = $this->load->view("disabled",$this->data,true);
        }else{
            $this->data["page_content"] = $this->load->view("admin/settings/sms", $this->data, true);
        }
        $this->data["page_script"] = $this->load->view("admin/settings/setting_script",$this->data,true);
		$this->load->view(BASE_TEMPLATE,$this->data);
    }
     public function app(){
        $id = _get_current_user_id();
       
        if($_POST)
        {
            if(isset($_POST["api_key"])){
                
                $post = $this->input->post();
                $this->load->library("verifytoken");
              
                $res = $this->verifytoken->verify($post["api_key"]);
                $this->db->delete("keys", array("id"=>"1"));
                if ($res["response"]) {
                    $item_id = $res["data"];
                    if ($item_id != null && $item_id == $post["item_id"]) {
                        $this->db->insert("keys", array("id"=>"1","key"=>$post["api_key"]));
                        add_options($post, "app_setting", true, true);
                    }
                }else{
                    _set_flash_message($res["data"],"error");
                }
            }else{
                $post = $this->input->post();
                add_options($post, "app_setting", true, true);
            }
        }
        $q = $this->db->get("keys");
        $this->data["api"] = $q->row();

        $setting = get_options_by_type("app_setting");
        
        $this->data["field"] = $setting;
        if(IS_TEST){
            $this->data["page_content"] = $this->load->view("disabled",$this->data,true);
        }else{
            $this->data["page_content"] = $this->load->view("admin/settings/app_setting", $this->data, true);
        }
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    
	function email(){
        $id = _get_current_user_id();
            
			if($_POST)
            {
                $post = $this->input->post();
                add_options($post,"email_settings",true,true);
            }
            $setting = get_options_by_type("email_settings");
            
            $this->data["field"] = $setting;
			if(IS_TEST){
                $this->data["page_content"] = $this->load->view("disabled",$this->data,true);
            }else{
                $this->data["page_content"] = $this->load->view("admin/settings/email", $this->data, true);
            }
            $this->data["page_script"] = $this->load->view("admin/settings/setting_script",$this->data,true);
		    $this->load->view(BASE_TEMPLATE,$this->data);
    }
    function payment(){
        $id = _get_current_user_id();
            
			if($_POST)
            {
                $post = $this->input->post();
                add_options($post,"payment_settings",true,true);
            }
            $setting = get_options_by_type("payment_settings");
            
            $this->data["field"] = $setting;
			if(IS_TEST){
                $this->data["page_content"] = $this->load->view("disabled",$this->data,true);
            }else{
                $this->data["page_content"] = $this->load->view("admin/settings/payment", $this->data, true);
            }
            $this->data["page_script"] = $this->load->view("admin/settings/setting_script",$this->data,true);
		    $this->load->view(BASE_TEMPLATE,$this->data);
    }
    function billing(){
        $id = _get_current_user_id();
            
			if($_POST)
            {
                $post = $this->input->post();
                add_options($post,"billing",true,true);
            }
            $setting = get_options_by_type("billing");
            
            $this->data["field"] = $setting;
			if(IS_TEST){
                $this->data["page_content"] = $this->load->view("disabled",$this->data,true);
            }else{
                $this->data["page_content"] = $this->load->view("admin/settings/billing", $this->data, true);
            }
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
    public function keys(){
        $id = _get_current_user_id();
        
        if($_POST)
        {
            $post = $this->input->post();
            add_options($post,"keys",true,true);
        }
          $setting =  get_options_by_type("keys");
        
        $this->data["field"] = $setting;
        if(IS_TEST){
            $this->data["page_content"] = $this->load->view("disabled",$this->data,true);
        }else{
            $this->data["page_content"] = $this->load->view("admin/settings/keys", $this->data, true);
        }
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
}
