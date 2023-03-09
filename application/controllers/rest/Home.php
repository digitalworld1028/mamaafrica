<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Home extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        $this->load->model("categories_model");   
        $this->load->model("banners_model");   
        $this->load->model("products_model");   
    }
    
    function list_post(){       
        $userid=0; 
        $user_id = $this->post("user_id");
        
        if($user_id!=null)
        {
          $userid=$user_id; 
        }
        
        $products=$this->products_model->get_home_products($userid);
        if(empty($products)){
            $filter["categories.status"] = 1;
            $filter["products.status"] = 1;
            $filter["product_discounts.product_discount_id >"] = "0";
            $products = $this->products_model->get($filter);   
            
        }
        $categories = $this->categories_model->get(array("categories.status"=>1));
        $banners = $this->banners_model->get(array("banners.status"=>1));
        
        $this->response(array(
                                        RESPONCE => true,
                                        MESSAGE => _l("Categories"),
                                        DATA => array("categories"=>$categories,"banners"=>$banners,"products"=>$products),
                                        CODE => CODE_SUCCESS
                                    ), REST_Controller::HTTP_OK);
    }
}