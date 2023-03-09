<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Products extends REST_Controller {

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
    }
    function ids_post(){
        $ids = $this->post("ids");
        if($ids != NULL){
            $products = $this->products_model->get(array("in"=>array("products.product_id"=>explode(",",$ids)),"products.status"=>"1"));
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("Products"),
                DATA => $products,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);
        }
    }
    function search_post(){
        $cart_user_id = $this->post("user_id");
        $search = $this->post("search");
        if ( $search == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please Provide Referance"),
                DATA =>_l("Please Provide Referance"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        
        if($cart_user_id == null)
        {
           $cart_user_id=0; 
        }
        
        $filter["cart_user_id"] = $cart_user_id;

        $products = $this->products_model->get($filter,$search);
        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("Products"),
            DATA => $products,
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }
    function list_post(){
        $cat_id = $this->post("category_id");
        $discount = $this->post("discount");
        if($cat_id == NULL && $discount == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide category relation"),
                DATA => _l("Please provide category relation"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        $filter = array();
        if($cat_id != NULL){
            $filter["products.category_id"] = $cat_id;
        }
        
        $filter["categories.status"] = 1;
        
        
        if($discount != NULL && $discount == "true"){
            $filter["product_discounts.product_discount_id >"] = "0";
        }
        
        $user_id = $this->post("user_id");
        if($user_id != NULL){
            $filter["cart_user_id"] = $user_id;
        }
        $filter["products.status"] = 1;
        $products = $this->products_model->get($filter);    
        $this->response(array(
                                        RESPONCE => true,
                                        MESSAGE => _l("Products"),
                                        DATA => $products,
                                        CODE => CODE_SUCCESS
                                    ), REST_Controller::HTTP_OK);
    }
    function details_post(){
        $product_id = $this->post("product_id");
        if($product_id == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide product identifire"),
                DATA => _l("Please provide product identifire"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        $filter = array();
        $user_id = $this->post("user_id");
        if($user_id != NULL){
            $filter["cart_user_id"] = $user_id;
        }
        $product = $this->products_model->get_by_id($product_id,$filter);
        $product->product_desc_en = $message = $this->load->view('api/product_desc',array("desc"=>$product->product_desc_en),TRUE);
        $product->product_desc_ar = $message = $this->load->view('api/product_desc',array("desc"=>$product->product_desc_ar),TRUE);
        
        $filter = array("product_id"=>$product_id);
        if($user_id != NULL){
            $filter["cart_user_id"] = $user_id;
            $this->load->model("cart_model");
            $cart_products = $this->cart_model->manage_cart($user_id,$product_id);
            if(count($cart_products) > 0){
                if (count($cart_products["products"]) > 0) {
                    $cart_product = $cart_products["products"][0];
                    $product->cart_item = $cart_product;
                    $product->cart_price = $cart_product->cart_price;
                }else{
                    $product->cart_item = array();
                    $product->cart_price = 0;
                }
            }else{
                $product->cart_item = array();
                $product->cart_price = 0;
            }
            
        }
        $product->options_list = $this->productoptions_model->get($filter);
        
        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("Product Details"),
            DATA => $product,
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }
    
   
}