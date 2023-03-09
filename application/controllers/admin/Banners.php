<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Banners extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "banners";
        $this->primary_key= "banner_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if(!_is_admin()){
            redirect("login");
            exit();
        }
		$this->load->model("banners_model");        
    }

    public function index(){
	
        $this->data["data"] = $this->banners_model->get();
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
            $row = $this->banners_model->get_by_id($id);
            if (!empty($row)) {
                $pk=$this->primary_key;
                $this->common_model->data_remove($this->table_name, array($this->primary_key=>$row->$pk), false);
                $data['responce'] = true;
                $data['message'] = $this->message_model->action_mesage("delete", _l("Banner"), true);
                echo json_encode($data);
            } else {
                $data['responce'] = false;
                $data['error'] = _l("Banners not available");
                echo json_encode($data);
            }
        }
    }

    public function add(){
            $this->action();
            
            $this->data["active_menu_link"] = array(site_url("admin/banners"));
			$this->data["fileupload"] = true;
            $this->data["field"] = $this->input->post();
            $this->data["select2"] = true;
            
            $this->load->model("products_model");      
            $this->data["products"] = $this->products_model->get();
            
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
	
    private function action(){
        $post = $this->input->post();
        $this->load->library('form_validation');      
        $this->form_validation->set_rules('banner_title_en', _l("Title")._l("(En)"), 'trim|required');
        $this->form_validation->set_rules('banner_title_ar', _l("Title")._l("(Ar)"), 'trim|required');
        $this->form_validation->set_rules('product_id', _l("Product"), 'trim|required');
         
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
                "banner_title_ar"=>$post["banner_title_ar"],
                "banner_title_en"=>$post["banner_title_en"],
                "product_id"=>$post["product_id"]
            );
			
			if(isset($_FILES["banner_image"]) && $_FILES['banner_image']['size'] > 0){
                $path = BANNER_IMAGE_PATH;
                if(!file_exists($path)){
                    mkdir($path, 0777, true);
                }
                $this->load->library("imagecomponent");
                $file_name_temp = $this->imagecomponent->getuniquefilename($_FILES['banner_image']['name']); //md5(uniqid())."_".$_FILES['banner_image']['name'];
                $file_name = $this->imagecomponent->upload_image_and_thumbnail('banner_image',840,200,$path ,'crop',false,$file_name_temp);
                $add_data["banner_image"] = $file_name_temp;
            }
            
			
            if(!empty($post["id"])){
                
				$id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                $this->message_model->action_mesage("update",_l("Banner"),false);
				
                redirect($this->controller);
            }else{
				
                $id = $this->common_model->data_insert($this->table_name,$add_data,true);
                $this->message_model->action_mesage("add",_l("Banner"),false);
				
                redirect($this->controller);
            }

        }
    }

    public function edit($id){
            $id = _decrypt_val($id);
            $this->action();
            $field = $this->banners_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
		
            $this->data["active_menu_link"] = array(site_url("admin/banners"));
			$this->data["fileupload"] = true;
            $this->data["field"] = $field;
            $this->data["select2"] = true;
            
            $this->load->model("products_model");      
            $this->data["products"] = $this->products_model->get();
            
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }	

	public function get_category_by_id()
	{
		$id=$this->input->post("id");
		$data=$this->banners_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
		echo json_encode($data);
	}
	
	
}
