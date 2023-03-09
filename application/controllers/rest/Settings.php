<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Settings extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
      
    }
    function list_post(){
        $options = get_options_by_type(array("general_setting","app_setting","sms_setting"));
        $pay_options = get_options(array("enable_cod","enable_payonline"));
        $options["enable_cod"] = $pay_options["enable_cod"];
        $options["enable_payonline"] = $pay_options["enable_payonline"];
         
        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("Settings"),
            DATA => $options,
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }
}