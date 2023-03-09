<?php
Class MY_Controller Extends CI_Controller{
    public static $site_settings;
    public function __construct(){
        parent::__construct();
       
       //$this->load->helper('language');       
       //$this->lang->load('base','english');
       
        $this::$site_settings = get_options_by_type("general_setting");
       $this->db->query("SET sql_mode = '' ");
    }
    
}
?>