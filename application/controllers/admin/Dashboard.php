<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function index()
	{
		if (_is_admin()){
			$this->load->model("orders_model");
			$this->load->model("deliveryboy_model");
        	$data["deliveryboys"] = $this->deliveryboy_model->get(array("delivery_boy.status"=>"1")); 
			$orders_with_status = $this->orders_model->order_counts_with_status();
			$data["order_with_status"] = $orders_with_status;		
			$orders = $this->orders_model->get(array("DATE(orders.order_date)"=>date(MYSQL_DATE_FORMATE)));
			$data["data"] = $orders;
			$data["page_content"] = $this->load->view("admin/dashboard",$data,true).$this->load->view("admin/orders/list",$data,true);
			$data["page_script"] = $this->load->view("admin/orders/order_script",$data,true).$this->load->view("admin/orders/location_map",$data,true);
			$this->load->view(BASE_TEMPLATE,$data);
        }
        else
        {
           redirect("login");
            exit();
        }
	    
	}
}
