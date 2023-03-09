<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Orders extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "orders";
        $this->primary_key= "order_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if(!_is_admin()){
            redirect("login");
            exit();
        }
        
        $this->load->model("orders_model");
        $this->load->model("deliveryboy_model");
        $this->data["deliveryboys"] = $this->deliveryboy_model->get(array("delivery_boy.status"=>"1"));      
    }
    
    public function index(){
			
        $this->data["data"] = $this->orders_model->get();
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/order_script",$this->data,true).$this->load->view($this->controller."/location_map",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    
    function pending(){
        $this->getByStatus(ORDER_PENDING);
    }
    
    function confirmed(){
        $this->getByStatus(ORDER_CONFIRMED);
    }
    
    function outdelivery(){
        $this->getByStatus(ORDER_OUT_OF_DELIVEY);
    }
    
    function delivered(){
        $this->getByStatus(ORDER_DELIVERED);
    }
    
    function declined(){
        $this->getByStatus(ORDER_DECLINE);
    }
    
    function canceled(){
        $this->getByStatus(ORDER_CANCEL);
    }
   
    function neworders(){
        $this->data["data"] = $this->orders_model->get(array("DATE(orders.order_date)"=>date(MYSQL_DATE_FORMATE)));
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/order_script",$this->data,true).$this->load->view($this->controller."/location_map",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    private function getByStatus($status){
        $this->data["data"] = $this->orders_model->get(array("orders.status"=>$status));
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/order_script",$this->data,true).$this->load->view($this->controller."/location_map",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    
    function updatestatus($id,$status=""){
        header('Content-type: text/json');
        $id = _decrypt_val($id);
        if($id == NULL)
            return;

            $order = $this->orders_model->get_by_id($id);
            if($order == NULL)
                return;
            if($status == "")
            $status = $this->input->post("status");
            $update = array("status"=>$status);
            $title = "";
            $message = "";
            $delivery_date = date(DEFAULT_DATE_FORMATE,strtotime($order->order_date));
         
            if($status == ORDER_CONFIRMED){
                $title = _l("Order Confirmed");
                $msg = _l("Your Order No. #order_no# is Confrimed, Delivery will proceed on time #delivery_date#");
                $message = str_replace(array("#order_no#","#delivery_date#"),array($order->order_no,$delivery_date),$msg);                
            }
            if($status == ORDER_DECLINE){
                $title = _l("Order Declined");
                $message = _l("Your Order No. #order_no# is declined by Restaurant.");
                $message = str_replace("#order_no#",$order->order_no,$message);
            }
            if($status == ORDER_OUT_OF_DELIVEY){
                $title = _l("Order Out of Delivery");
                $message = _l("Your Order No. #order_no# is out of delivery you may track it on your phone.");
                $message = str_replace("#order_no#",$order->order_no,$message);
            }
            if($status == ORDER_DELIVERED){
                $title = _l("Order Delivered");
                $message = _l("Thanks being with Restaurant, Your Order No. #order_no# is delivered, Please share your reviews.");
                $message = str_replace("#order_no#",$order->order_no,$message);
            }
            if($title != "" && $message != ""){
                $this->load->library("onesignallib");
                $player_ids = array();
                if(isset($order->android_token) && $order->android_token != ""){
                    $player_ids[] = $order->android_token;
                }
                if(isset($order->ios_token) && $order->ios_token != ""){
                    $player_ids[] = $order->ios_token;
                }
                $res=$this->onesignallib->sendToPlayerIds($message,$title,$player_ids,array("type"=>NOTIFICATION_TYPE_ORDER,"ref_id"=>$order->order_id));
                $this->common_model->data_insert("notifications",
                    array("user_id"=>$order->user_id,
                    "title_en"=>$title,
                    "title_ar"=>$title,
                    "message_en"=>$message,
                    "message_ar"=>$message,
                    "type"=>NOTIFICATION_TYPE_ORDER,
                    "type_id"=>$order->order_id),true
                );
            }    

            $this->common_model->data_update("orders",$update,array("order_id"=>$id));
            $this->common_model->data_insert("order_status",array("order_id"=>$id,"status"=>$status),true);
            
            $data["message"] = $this->message_model->action_mesage("update",_l("Order Status"),true);
            $order->status = $status;
            $data["data"] = $this->load->view("admin/orders/order_status_label",array("dt"=>$order,"count"=>$this->input->post("row_index")),true);
            $data["response"] = true;
            echo json_encode($data);
        }
        public function assign_deliveryboy(){
            header('Content-type: text/json');
            $order_id = $this->input->post("assign_order_id");
            $count = $this->input->post("row_index") + 1;
            $id = _decrypt_val($this->input->post("assign_order_id"));
            $delivery_boy_id = $this->input->post("delivery_boy_id");
            $this->load->model("deliveryboy_model");
            if(isset($_POST["delivery_boy_id"])){
                $order = $this->orders_model->get_by_id($id);
    
                $delivery_boy_id = $this->input->post("delivery_boy_id");
                $deliveryboy = $this->deliveryboy_model->get_by_id($delivery_boy_id);
                
                $this->common_model->data_update("orders",array("delivery_boy_id"=>$deliveryboy->delivery_boy_id),array("order_id"=>$id));
            
                
                $title = _l("Order Assigned");
                $message = _l("Assigned with new Order No. #order_no#, Please Picked Items.");
                $message = str_replace("#order_no#",$order->order_no,$message);
                
                if($title != "" && $message != ""){
                    $this->load->library("onesignallib");
                    $player_ids = array();
                    if(isset($deliveryboy->android_token) && $deliveryboy->android_token != ""){
                        $player_ids[] = $deliveryboy->android_token;
                    }
                    if(isset($deliveryboy->ios_token) && $deliveryboy->ios_token != ""){
                        $player_ids[] = $deliveryboy->ios_token;
                    }
                    if(!empty($player_ids)){
                        $res=$this->onesignallib->sendToPlayerIds($message,$title,$player_ids,array("type"=>NOTIFICATION_TYPE_ORDER,"ref_id"=>$order->order_id));
                    }
                }
                $this->updatestatus($order_id,ORDER_CONFIRMED);
            }
        }
    public function delete($id){
        $id = _decrypt_val($id);
        
        $row = $this->orders_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_remove($this->table_name,array($this->primary_key=>$row->$pk),false);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete",_l("Order"),true);
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = _l("Orders not available");
            echo json_encode($data);
        }
    }
  
	public function get_by_id()
	{
		$id=$this->input->post("id");
		$data=$this->orders_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
		echo json_encode($data);
	}
   
    public function receipt($id,$type=""){
        $id = _decrypt_val($id);       
        
        $row = $this->orders_model->get_by_id($id);
        $items = $this->orders_model->get_order_items($id);
        
        foreach($items as $item){
            $option_items = $this->orders_model->get_order_option_items($item->order_item_id);
            $item->option_items = $option_items;
        }
              
        $this->data["order"] = $row;
        $this->data["items"] = $items;
       
        $this->data["active_menu_link"] = array(site_url("admin/orders"));
			
        $this->data["field"] = $this->input->post();
        $this->data["setting"] = get_options_by_type("billing");
        $this->data["page_content"] = $this->load->view($this->controller."/receipt",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/location_map",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }

	
}
