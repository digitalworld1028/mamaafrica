<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Cart extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model("products_model");
        $this->load->model("productoptions_model");
        $this->load->model("cart_model");
    }
    function add_post(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', _l("User Reference"),'trim|required');
        $this->form_validation->set_rules('product_id', _l("Product ID"), 'trim|required');
        $this->form_validation->set_rules('qty', _l("Qty"), 'trim|required');
        
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
            $user_id = $this->post("user_id");
            $product_id = $this->post("product_id");
            $qty = $this->post("qty");

            $product = $this->products_model->get_by_id($product_id);
            if(empty($product)){
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Sorry! Product not found"),
                    DATA =>_l("Sorry! Product not found"),
                    CODE => CODE_MISSING_INPUT     
                ), REST_Controller::HTTP_OK); 
            }
           
            

            $this->db->where(array("cart.user_id"=>$user_id,"cart.product_id"=>$product_id));
            $q = $this->db->get("cart");
            $cart_item = $q->row();
            if(!empty($cart_item)){                
                $qty = $cart_item->qty + $qty;
                $this->common_model->data_update("cart",array("qty" => $qty),array("cart_id"=>$cart_item->cart_id));
            }else{
                $this->common_model->data_insert("cart",array("qty" => $qty,"user_id"=>$user_id,"product_id"=>$product_id));
            }
            
            $cart_array = $this->cart_model->manage_cart($user_id);
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("User Cart"),
                DATA => $cart_array,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);
            
        }
    }
   
    function minus_post(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', _l("User Reference"),'trim|required');
        $this->form_validation->set_rules('product_id', _l("Product ID"), 'trim|required');
        $this->form_validation->set_rules('qty', _l("Qty"), 'trim|required');
        
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
            $user_id = $this->post("user_id");
            $product_id = $this->post("product_id");
            $qty = $this->post("qty");

            $this->db->where("product_id",$product_id);
            $this->db->where("user_id",$user_id);
            $this->db->order_by("cart_id desc");
            $q = $this->db->get("cart");
            $cart_item  = $q->row();
            
            if(!empty($cart_item)){
                $qty = $cart_item->qty - $qty;
                
                if($qty <= 0){
                    $this->db->where_in("cart_id",$cart_item->cart_id);
                    $this->db->where("user_id",$user_id);
                    $this->db->delete("cart");

                    $this->db->where_in("product_id",$cart_item->product_id);
                    $this->db->where("user_id",$user_id);
                    $this->db->delete("cart_option");
                }else{
                    
                    $this->common_model->data_update("cart",array("qty" => $qty),array("cart_id"=>$cart_item->cart_id));
                }
            }
            
            $cart_array = $this->cart_model->manage_cart($user_id);
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("User Cart"),
                DATA => $cart_array,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);
            
        }
    }
    function list_post(){
        $user_id = $this->post("user_id");
        if($user_id == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("User reference required"),
                DATA =>_l("User reference required"),
                CODE => CODE_MISSING_INPUT     
            ), REST_Controller::HTTP_OK);
        }
        $cart_array = $this->cart_model->manage_cart($user_id);

        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("User Cart"),
            DATA => $cart_array,
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }
    

    function delete_post(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', _l("User Reference"),'trim|required');
        $this->form_validation->set_rules('product_id', _l("Cart ID"), 'trim|required');
      
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
            $user_id = $this->post("user_id");
            $product_id = explode(",",$this->post("product_id"));
            
            $this->db->where_in("product_id",$product_id);
            $this->db->where("user_id",$user_id);
            $this->db->delete("cart");

            $this->db->where_in("product_id",$product_id);
            $this->db->where("user_id",$user_id);
            $this->db->delete("cart_option");

            $cart_array = $this->cart_model->manage_cart($user_id);
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("User Cart"),
                DATA => $cart_array,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);
            
        }
    }

    function clean_post(){
        $this->load->library('form_validation');
       $this->form_validation->set_rules('user_id', _l("User Reference"),'trim|required');
        
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
            $user_id = $this->post("user_id");
            
            $this->db->where("user_id",$user_id);
            $this->db->delete("cart");

            $this->db->where("user_id",$user_id);
            $this->db->delete("cart_option");

            $cart_array = $this->cart_model->manage_cart($user_id);
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("User Cart"),
                DATA => $cart_array,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);
            
        }
    }


    function add_option_post(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', _l("User Reference"),'trim|required');
        $this->form_validation->set_rules('product_option_id', _l("Product Option ID"), 'trim|required');
        $this->form_validation->set_rules('qty', _l("Qty"), 'trim|required');
        
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
            $user_id = $this->post("user_id");
            $product_option_id = $this->post("product_option_id");
            $qty = $this->post("qty");

            $product_option = $this->productoptions_model->get_by_id($product_option_id);
            if(empty($product_option)){
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Sorry! Product Option not found"),
                    DATA =>_l("Sorry! Product Option not found"),
                    CODE => CODE_MISSING_INPUT     
                ), REST_Controller::HTTP_OK); 
            }
           
            

            $this->db->where(array("cart_option.user_id"=>$user_id,"cart_option.product_option_id"=>$product_option_id));
            $q = $this->db->get("cart_option");
            $cart_item = $q->row();
            if(!empty($cart_item)){                
                $qty = $cart_item->qty + $qty;
                $this->common_model->data_update("cart_option",array("qty" => $qty),array("cart_option_id"=>$cart_item->cart_option_id));
            }else{
                $this->common_model->data_insert("cart_option",array("qty" => $qty,"user_id"=>$user_id,"product_id"=>$product_option->product_id,"product_option_id"=>$product_option_id));
            }
            
            $cart_array = $this->cart_model->manage_cart($user_id);
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("User Cart"),
                DATA => $cart_array,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);
            
        }
    }
   
    function minus_option_post(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', _l("User Reference"),'trim|required');
        $this->form_validation->set_rules('product_option_id', _l("Product Option ID"), 'trim|required');
        $this->form_validation->set_rules('qty', _l("Qty"), 'trim|required');
        
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
            $user_id = $this->post("user_id");
            $product_option_id = $this->post("product_option_id");
            $qty = $this->post("qty");

            $this->db->where("product_option_id",$product_option_id);
            $this->db->where("user_id",$user_id);
            $this->db->order_by("cart_option_id desc");
            $q = $this->db->get("cart_option");
            $cart_item  = $q->row();
            
            if(!empty($cart_item)){
                $qty = $cart_item->qty - $qty;
                
                if($qty <= 0){
                    $this->db->where_in("cart_option_id",$cart_item->cart_option_id);
                    $this->db->where("user_id",$user_id);
                    $this->db->delete("cart_option");
                }else{
                    
                    $this->common_model->data_update("cart_option",array("qty" => $qty),array("cart_option_id"=>$cart_item->cart_option_id));
                }
            }
            
            $cart_array = $this->cart_model->manage_cart($user_id);
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("User Cart"),
                DATA => $cart_array,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);
            
        }
    }


    function reorder_post(){
        $this->load->library('form_validation');
       $this->form_validation->set_rules('user_id', _l("User Reference"),'trim|required');
       $this->form_validation->set_rules('order_id', _l("Order ID"),'trim|required');
       
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
            $user_id = $this->post("user_id");
            $order_id = $this->post("order_id");
            
            $this->db->where("user_id",$user_id);
            $this->db->delete("cart");

            $this->db->where("user_id",$user_id);
            $this->db->delete("cart_option");

            $this->load->model("orders_model");
            $order_items = $this->orders_model->get_order_items($order_id);
            foreach($order_items as $item){
                $qty = $item->order_qty;
                $product = $this->products_model->get_by_id($item->product_id);
                if(empty($product))
                {
                    continue;
                }
                //$this->db->where(array("cart.user_id"=>$user_id,"cart.product_id"=>$product->product_id));
                //$q = $this->db->get("cart");
                //$cart_item = $q->row();
                
                //$cart_id = 0;
                //if(!empty($cart_item)){
                //    $cart_id = $cart_item->cart_id;
                //        $qty = $cart_item->qty + $qty;
                //        $this->common_model->data_update("cart",array("qty" => $qty),array("cart_id"=>$cart_item->cart_id));
                //}else{
                    $cart_id = $this->common_model->data_insert("cart",array("qty" => $qty,"user_id"=>$user_id,"product_id"=>$product->product_id));
                //}
                foreach($item->product_options as $option){
                    $cart_id = $this->common_model->data_insert("cart_option",array("cart_id"=>$cart_id,"product_option_id"=>$option->product_option_id, "qty" => $option->order_qty,"user_id"=>$user_id,"product_id"=>$product->product_id));
                }
            }
            $cart_array = $this->cart_model->manage_cart($user_id);
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("User Cart"),
                DATA => $cart_array,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);
            
        }
    }
}