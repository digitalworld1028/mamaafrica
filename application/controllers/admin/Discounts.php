<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Discounts extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "product_discounts";
        $this->primary_key= "product_discount_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if(!_is_admin()){
            redirect("login");
            exit();
        }
        
		$this->load->model("discounts_model");
        $this->load->model("products_model");
        
        $this->data["discount_types"] = $this->config->item("coupon_discount_type");
        $this->data["products"] = $this->products_model->get();
    }
	
    public function index(){	
        $this->data["data"] = $this->discounts_model->get();
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }

    public function delete($id){
        $id = _decrypt_val($id);
        if(IS_TEST){
            $data['responce'] = false;
            $data['error'] = _l("This feature disable in Demo");
            echo json_encode($data);
        }else{
            $row = $this->discounts_model->get_by_id($id);
            if (!empty($row)) {
                $pk=$this->primary_key;
                $this->common_model->data_remove($this->table_name, array($this->primary_key=>$row->$pk), false);
                $data['responce'] = true;
                $data['message'] = $this->message_model->action_mesage("delete", _l("Discount"), true);
                echo json_encode($data);
            } else {
                $data['responce'] = false;
                $data['error'] = _l("Discounts not available");
                echo json_encode($data);
            }
        }
    }
	
    public function add(){
            $this->action();
            $this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/discounts"));
			$this->data["daterangepicker"] = true;
            $this->data["field"] = $this->input->post();
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }

    private function action(){
        $post = $this->input->post();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('product_id', _l("Product"), 'trim|required');
        $this->form_validation->set_rules('discount',_l("Discount"),'trim|required');
        $this->form_validation->set_rules('discount_type',_l("Discount Type"),'trim|required');
        $this->form_validation->set_rules('validity',_l("Validity"),'trim|required');
        $responce = array();
        if ($this->form_validation->run() == FALSE)
        {
            if($this->form_validation->error_string()!="")
            {
                _set_flash_message($this->form_validation->error_string(),"error");
            }
        }
        else
        {
            $validity = explode("-",$post["validity"]);
            
            $add_data = array(
                "product_id"=>$post["product_id"],
                "discount"=>$post["discount"],
                "discount_type"=>$post["discount_type"],
                "start_date"=>date(MYSQL_DATE_FORMATE,strtotime(trim($validity[0]))),
                "end_date"=>date(MYSQL_DATE_FORMATE,strtotime(trim($validity[1]))),
                "status"=>(isset($post["status"]) && $post["status"] == "on") ? 1 : 0
            );
			
            if(!empty($post["id"])){
                
				$id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                $this->message_model->action_mesage("update",_l("Discount"),false);
				
                redirect($this->controller);
            }else{
				 $this->db->where("product_discounts.product_id",$post["product_id"]);
                $this->db->where("product_discounts.start_date <=",date(MYSQL_DATE_FORMATE));
                $this->db->where("product_discounts.end_date >=",date(MYSQL_DATE_FORMATE));
                $this->db->where("product_discounts.draft","0");
                $q = $this->db->get("product_discounts");
                $row = $q->result();
                if(!empty($row)){
                    _set_flash_message(_l("There is currently active discount with this product"),"error");
                }else{
                
                    $id = $this->common_model->data_insert($this->table_name,$add_data,true);
                    $this->message_model->action_mesage("add",_l("Discount"),false);
                    redirect($this->controller);
				}
                
            }

        }
    }
	

    public function edit($id){
            $id = _decrypt_val($id);
            $this->action();
            $field = $this->discounts_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
			$this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/discounts"));
			$this->data["daterangepicker"] = true;
            $field->validity = date("m/d/Y",strtotime($field->start_date))." - ".date("m/d/Y",strtotime($field->end_date));
            $this->data["field"] = $field;
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
		
}