<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Branches extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "branches";
        $this->primary_key= "branch_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if(!_is_admin()){
            redirect("login");
            exit();
        }
        
		$this->load->model("branches_model");
    }

    public function index(){
		
        $this->data["data"] = $this->branches_model->get();
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
            $row = $this->branches_model->get_by_id($id);
            if (!empty($row)) {
                $pk=$this->primary_key;
                $this->common_model->data_remove($this->table_name, array($this->primary_key=>$row->$pk), false);
                $data['responce'] = true;
                $data['message'] = $this->message_model->action_mesage("delete", _l("Branch"), true);
                echo json_encode($data);
            } else {
                $data['responce'] = false;
                $data['error'] = _l("Branches not available");
                echo json_encode($data);
            }
        }
    }

    public function add(){
            $this->action();
          
            $this->data["active_menu_link"] = array(site_url("admin/branches"));			
            $this->data["field"] = $this->input->post();
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
	
    private function action(){
        $post = $this->input->post();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('branch_name_en', _l("Name")._l("(En)"), 'trim|required');
        $this->form_validation->set_rules('branch_name_ar', _l("Name")._l("(Ar)"), 'trim|required');
        $this->form_validation->set_rules('opening_time', _l("Opening Time"), 'trim|required');
        $this->form_validation->set_rules('closing_time', _l("Closing Time"), 'trim|required');
        $this->form_validation->set_rules('area_en', _l("Area")._l("(En)"), 'trim|required');
        $this->form_validation->set_rules('area_ar', _l("Area")._l("(Ar)"), 'trim|required');
        $this->form_validation->set_rules('postal_code', _l("Postal Code"), 'trim|required');
        $this->form_validation->set_rules('phone', _l("Phone"), 'trim|required');
        $this->form_validation->set_rules('latitude', _l("Latitude"), 'trim|required');
        $this->form_validation->set_rules('longitude', _l("Longitude"), 'trim|required');
        
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
            $add_data = array(
                "branch_name_en"=>$post["branch_name_en"],
                "branch_name_ar"=>$post["branch_name_ar"],
                "opening_time"=>date(MYSQL_TIME_FORMATE,strtotime($post["opening_time"])),
                "closing_time"=>date(MYSQL_TIME_FORMATE,strtotime($post["closing_time"])),
                "phone"=>$post["phone"],
                "postal_code"=>$post["postal_code"],
                "area_en"=>$post["area_en"],
                "area_ar"=>$post["area_ar"],
                "address_en"=>$post["address_en"],
                "address_ar"=>$post["address_ar"],
                "latitude"=>$post["latitude"],
                "longitude"=>$post["longitude"],
                "delivery_area_in_km"=>$post["delivery_area_in_km"],
                "is_active"=>(isset($post["is_active"]) && $post["is_active"] == "on") ? 1 : 0
            );			
		
            if(!empty($post["id"])){
                
				$id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                $this->message_model->action_mesage("update",_l("Branch"),false);
				
                redirect($this->controller);
            }else{
				
                $id = $this->common_model->data_insert($this->table_name,$add_data,true);
                $this->message_model->action_mesage("add",_l("Branch"),false);
				
                redirect($this->controller);
            }

        }
    }
	
    public function edit($id){
            $id = _decrypt_val($id);
            $this->action();
            $field = $this->branches_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
		
            $this->data["active_menu_link"] = array(site_url("admin/branches"));			
            $this->data["field"] = $field;
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
	

	public function get_branch_by_id()
	{
		$id=$this->input->post("id");
		$data=$this->branches_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
		echo json_encode($data);
	}

}
