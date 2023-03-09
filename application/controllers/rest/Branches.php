<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Branches extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model("branches_model");        
    }
    
    function list_post(){
        $branches = $this->branches_model->get(array("branches.is_active"=>1));
        
        $this->response(array(
                                        RESPONCE => true,
                                        MESSAGE => _l("Branches"),
                                        DATA => $branches,
                                        CODE => CODE_SUCCESS
                                    ), REST_Controller::HTTP_OK);
    }
}