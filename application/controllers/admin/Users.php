<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Users extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "users";
        $this->primary_key= "user_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if(!_is_admin()){
            redirect("login");
            exit();
        }
		$this->load->model("user_model");        
    }

    function appusers(){
        $this->data["data"] = $this->user_model->get(array("in"=> array("user_type_id"=>array(USER_CUSTOMER))));
        $this->data["page_content"] = $this->load->view($this->controller."/appusers",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
   
    function details($id){
        $id = _decrypt_val($id);
        if($id == NULL){
            return;
        }
        $this->data["data"] = $this->user_model->get_by_id($id);
        $address = $this->user_model->get_address($id);
        
        $this->data["active_menu_link"] = array(site_url("admin/users/appusers"));
        
        $this->data["addresses"] = $address;
        
        $this->data["page_content"] = $this->load->view($this->controller."/details",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
  
    public function delete($id){
        $id = _decrypt_val($id);
        if(IS_TEST){
            $data['responce'] = false;
            $data['error'] = _l("This feature disable in Demo");
            echo json_encode($data);
        }else{
            $row = $this->user_model->get_by_id($id);
            if (!empty($row)) {
                $pk=$this->primary_key;
                $this->common_model->data_remove($this->table_name, array($this->primary_key=>$row->$pk), false);
                $data['responce'] = true;
                $data['message'] = $this->message_model->action_mesage("delete", _l("User"), true);
                echo json_encode($data);
            } else {
                $data['responce'] = false;
                $data['error'] = _l("Users not available");
                echo json_encode($data);
            }
        }
    }

}