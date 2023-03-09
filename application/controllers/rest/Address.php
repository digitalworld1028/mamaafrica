<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Address extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model("address_model");
       
    }
    function list_post(){
        $user_id = $this->post("user_id");
        if($user_id == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("User reference required"),
                DATA => _l("User reference required"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }

        $addresses = $this->address_model->get(array("user_address.user_id"=>$user_id));
        
        $this->response(array(
                                        RESPONCE => true,
                                        MESSAGE => _l("User Addresses"),
                                        DATA => $addresses,
                                        CODE => CODE_SUCCESS
                                    ), REST_Controller::HTTP_OK);
    }
    function add_post(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id',  _l("User Reference"),'trim|required');
        $this->form_validation->set_rules('postal_code',  _l("Postal Code"), 'trim|required');
        $this->form_validation->set_rules('address_line1',  _l("Address Line 1"), 'trim|required');
        
        if ($this->form_validation->run() == FALSE) 
        {
            $this->response(array(
                        RESPONCE => false,
                        MESSAGE => strip_tags($this->form_validation->error_string()),
                        DATA =>strip_tags($this->form_validation->error_string()),
                        CODE => CODE_MISSING_INPUT     
            ), REST_Controller::HTTP_OK); 
        }else
        {
            $post = $this->post();
            $postal_code = str_replace(" ","",$post["postal_code"]) ;
            $lat = $post["latitude"];
            $lon = $post["longitude"];

            //$this->db->where("postal_code",$postal_code);
            //$q = $this->db->get("postal_codes");
            //$is_postal_available = $q->row();
            $this->db->select("111.1111 *
            DEGREES(ACOS(COS(RADIANS(branches.latitude))
             * COS(RADIANS($lat))
             * COS(RADIANS(branches.longitude) - RADIANS($lon))
             + SIN(RADIANS(branches.latitude))
             * SIN(RADIANS($lat)))) AS distance_in_km,branches.delivery_area_in_km");
             $this->db->having("distance_in_km < branches.delivery_area_in_km");
             $q = $this->db->get("branches");
             $branches = $q->result();

            if(empty($branches)){
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Unfortunately, we do not deliver to your area!"),
                    DATA => _l("Unfortunately, we do not deliver to your area!"),
                    CODE => 105
                ), REST_Controller::HTTP_OK);
            }

            $inser_array = array(
                "user_id"=>$post["user_id"],
                "address_line1"=>$post["address_line1"],
                "address_line2"=>$post["address_line2"],
                "postal_code"=>str_replace(" ","",$post["postal_code"]),
                "city"=>(isset($post["city"]) && $post["city"] != NULL) ? $post["city"] : "",
                "latitude"=>$post["latitude"],
                "longitude"=>$post["longitude"]
            );
            $user_address_id = $this->common_model->data_insert("user_address",$inser_array,true);
            $inser_array["user_address_id"] = strval($user_address_id);
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("Address Added Successfully"),
                DATA => $inser_array,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);
        }
    }
    function update_post(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_address_id',  _l("User Address ID"),'trim|required');
        $this->form_validation->set_rules('user_id',  _l("User Reference"),'trim|required');
        $this->form_validation->set_rules('postal_code',  _l("Postal Code"), 'trim|required');
        $this->form_validation->set_rules('address_line1',  _l("Address"), 'trim|required');
        
        if ($this->form_validation->run() == FALSE) 
        {
            $this->response(array(
                        RESPONCE => false,
                        MESSAGE => strip_tags($this->form_validation->error_string()),
                        DATA =>strip_tags($this->form_validation->error_string()),
                        CODE => CODE_MISSING_INPUT     
            ), REST_Controller::HTTP_OK); 
        }else
        {
            $post = $this->post();
            $user_id = $post["user_id"];
            $user_address_id = $post["user_address_id"];
            $postal_code = str_replace(" ","",$post["postal_code"]) ;

            $lat = $post["latitude"];
            $lon = $post["longitude"];
            $this->db->select("111.1111 *
            DEGREES(ACOS(COS(RADIANS(branches.latitude))
             * COS(RADIANS($lat))
             * COS(RADIANS(branches.longitude) - RADIANS($lon))
             + SIN(RADIANS(branches.latitude))
             * SIN(RADIANS($lat)))) AS distance_in_km,branches.delivery_area_in_km");
             $this->db->having("distance_in_km < branches.delivery_area_in_km");
             $q = $this->db->get("branches");
             $branches = $q->result();

            if(empty($branches)){
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Unfortunately, we do not deliver to your postcode yet!"),
                    DATA => _l("Unfortunately, we do not deliver to your postcode yet!"),
                    CODE => 105
                ), REST_Controller::HTTP_OK);
            }

            

            $address = $this->address_model->get_by_id($user_address_id);
            
            $inser_array = array(
                "user_id"=>$post["user_id"],
                "address_line1"=>$post["address_line1"],
                "address_line2"=>$post["address_line2"],
                "postal_code"=>str_replace(" ","",$post["postal_code"]),
                "city"=>(isset($post["city"]) && $post["city"] != NULL) ? $post["city"] : "",
                "latitude"=>$post["latitude"],
                "longitude"=>$post["longitude"]
            );
            
            $this->common_model->data_update("user_address",$inser_array,array("user_address_id"=>$address->user_address_id));
            $inser_array["user_address_id"] = $address->user_address_id;
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("Address Updated Successfully"),
                DATA => $inser_array,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);
        }
    }
    function delete_post(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_address_id',  _l("User Address ID"),'trim|required');
        $this->form_validation->set_rules('user_id',  _l("User Reference"),'trim|required');
        
        if ($this->form_validation->run() == FALSE) 
        {
            $this->response(array(
                        RESPONCE => false,
                        MESSAGE => strip_tags($this->form_validation->error_string()),
                        DATA =>strip_tags($this->form_validation->error_string()),
                        CODE => CODE_MISSING_INPUT     
            ), REST_Controller::HTTP_OK); 
        }else
        {
            $post = $this->post();
            $user_id = $post["user_id"];
            $user_address_id = $post["user_address_id"];
            
            $address = $this->address_model->get_by_id($user_address_id);
            
            $this->common_model->data_remove("user_address",array("user_id"=>$user_id, "user_address_id"=>$address->user_address_id),true);
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("Address Deleted Successfully"),
                DATA => _l("Address Deleted Successfully"),
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);
        }
    }
}