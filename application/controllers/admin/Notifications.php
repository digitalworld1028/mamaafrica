<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Notifications extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "notifications";
        $this->primary_key= "noti_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if(!_is_admin()){
            redirect("login");
            exit();
        }
        
		$this->load->model("notifications_model");
    }
	/**
	* Product notification
	* @return product notification page
	*/
    public function index(){
		//$this->data["datatable_export"]=true;	
        $this->data["data"] = $this->notifications_model->get(array("user_id"=>"0"));
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
	/**
	* delete product notification
	* @param id for product noti id
	* @return delete product notification
	*/
    public function delete($id){
        $id = _decrypt_val($id);
        if(IS_TEST){
            $data['responce'] = false;
            $data['error'] = _l("This feature disable in Demo");
            echo json_encode($data);
        }else{
            $row = $this->notifications_model->get_by_id($id);
            if (!empty($row)) {
                $pk=$this->primary_key;
                $this->common_model->data_remove($this->table_name, array($this->primary_key=>$row->$pk), false);
                $data['responce'] = true;
                $data['message'] = $this->message_model->action_mesage("delete", _l("Notification"), true);
                echo json_encode($data);
            } else {
                $data['responce'] = false;
                $data['error'] = _l("Categories not available");
                echo json_encode($data);
            }
        }
    }
	/**
	* add product notification
	* @return add product notification page
	*/
    public function add(){
            $this->action();
          
            $this->data["active_menu_link"] = array(site_url("admin/notifications"));
			$this->data["fileupload"] = true;
            $this->data["field"] = $this->input->post();
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
	/**
	* action add or edit product notifiation
	* @return redirect to product notification list
	*/
    private function action(){
        $post = $this->input->post();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title_en', _l("Title")._l("(En)"), 'trim|required');
        $this->form_validation->set_rules('message_en', _l("Message")._l("(En)"), 'trim|required');
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
                "title_en"=>$post["title_en"],
                "message_en"=>$post["message_en"],
            );
			$image = "";
			if(isset($_FILES["not_image"]) && $_FILES['not_image']['size'] > 0){
                $path = NOTIFICATION_IMAGE_PATH;
                if(!file_exists($path)){
                    mkdir($path, 0777, true);
                }
                $this->load->library("imagecomponent");
                $file_name_temp =  $this->imagecomponent->getuniquefilename($_FILES['not_image']['name']);//md5(uniqid())."_".$_FILES['cat_image']['name'];
                $file_name = $this->imagecomponent->upload_image_and_thumbnail('not_image',680,200,$path ,'crop',true,$file_name_temp);
                $add_data["attachment"] = $file_name_temp;
                $image = base_url($path."/".$file_name_temp);
            }
            
            $this->load->library("onesignallib");
            $this->onesignallib->sendToAll($post["message_en"],$post["title_en"],array(),$add_data,$image);
            
			
            if(!empty($post["id"])){
                
				$id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                $this->message_model->action_mesage("update",_l("Notification"),false);
				
                redirect($this->controller);
            }else{
                
                
                $id = $this->common_model->data_insert($this->table_name,$add_data,true);
                $this->message_model->action_mesage("add",_l("Notification"),false);
				
                redirect($this->controller);
            }

        }
    }
	
	/**
	* edit product size
	* @return edit product size page
	*/
    public function edit($id){
            $id = _decrypt_val($id);
            $this->action();
            $field = $this->notifications_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
		
            $this->data["active_menu_link"] = array(site_url("admin/notifications"));
			$this->data["fileupload"] = true;
            $this->data["field"] = $field;
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
	
	/**
	* get category by id
	* @return category json
	*/
	public function get_notification_by_id()
	{
		$id=$this->input->post("id");
		$data=$this->notifications_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
		echo json_encode($data);
	}
    
	
}
