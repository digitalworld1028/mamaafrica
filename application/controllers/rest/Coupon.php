<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Coupon extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model("coupons_model");       
    }
    
    function list_post(){
        $user_id = $this->post("user_id");
        if($user_id == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("User referance required"),
                DATA => _l("User referance required"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }

        $date = date(MYSQL_DATE_FORMATE);
        $filter = array("user_id"=>$user_id,"validity"=>$date);

        $type = $this->post("coupon_type");
        if($type != NULL){
            $filter["coupon_type"] = $type;
        }
        $coupons = $this->coupons_model->get($filter);
        
        if($type == "share"){
            $coupons = $coupons[0];
        }

        $this->response(array(
                                        RESPONCE => true,
                                        MESSAGE => _l("Valid Coupons"),
                                        DATA => $coupons,
                                        CODE => CODE_SUCCESS
                                    ), REST_Controller::HTTP_OK);
    }
    function validate_post(){
        $user_id = $this->post("user_id");
        if($user_id == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("User reference required"),
                DATA => _l("User reference required"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        $coupon_code = $this->post("coupon_code");
        if($coupon_code == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Enter Coupon Code"),
                DATA => _l("Enter Coupon Code"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        $this->response($this->coupons_model->validate($user_id,$coupon_code), REST_Controller::HTTP_OK);     
        
    }
    
}