<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Contacts extends MY_Controller
{
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "contact_request";
        $this->primary_key= "contact_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if (!_is_admin()) {
            redirect("login");
            exit();
        }
        $this->load->model("contactus_model");
    }

    public function index()
    {
        $this->data["data"] = $this->contactus_model->get();
        $this->data["page_content"] = $this->load->view($this->controller."/list", $this->data, true);
        $this->load->view(BASE_TEMPLATE, $this->data);
    }
    public function delete($id){
        $id = _decrypt_val($id);
        if(IS_TEST){
            $data['responce'] = false;
            $data['error'] = _l("This feature disable in Demo");
            echo json_encode($data);
        }else{
            $row = $this->contactus_model->get_by_id($id);
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