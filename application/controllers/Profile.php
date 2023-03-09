<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Profile extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = $this->router->fetch_class();
        
         if(!_is_user_login()){            
            redirect("login");
            exit();
        }
    }

    /**
	* user edit profile
	* @return edit user page
	*/
    public function index(){
            $id = _get_current_user_id();
            $this->action();
			$this->load->model('user_model');
            $field = $this->user_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
            
			$this->data["fileupload"] = true;
            $this->data["field"] = $field;
            $this->data["page_content"] = $this->load->view("admin/profile",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
	
	/**
	* action add or edit godown
	* @return redirect to godown list
	*/
    private function action(){
        $post = $this->input->post();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_firstname', _l("First Name"), 'trim|required');
        $this->form_validation->set_rules('user_lastname', _l("Last Name"), 'trim|required');
        $this->form_validation->set_rules('user_email', _l("User Email"), 'trim|required|valid_email');
        
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
                "user_firstname"=>$post["user_firstname"],
                "user_lastname"=>$post["user_lastname"],
                "user_email"=>$post["user_email"]
            );
            $img="";
			if(isset($_FILES["profile_photo"]) && $_FILES['profile_photo']['size'] > 0){
			 
			 $path = PROFILE_IMAGE_PATH;
                
                if(!file_exists($path)){
                    mkdir($path);
                }
                $this->load->library("imagecomponent");
                $file_name_temp = md5(uniqid())."_".$_FILES['profile_photo']['name'];
                $file_name = $this->imagecomponent->upload_image_and_thumbnail('profile_photo',680,200,$path ,'crop',true,$file_name_temp);
                $img = $file_name_temp;
            }
			if($img!="")
			{
				$add_data['user_image']=$img;	
			}
            $id=_get_current_user_id();
            if(IS_TEST){
                
            }else{
                $this->common_model->data_update("users", $add_data, array("user_id"=>$id), true);
            }
			$this->message_model->action_mesage("update",ucfirst(str_replace("_"," ",_l("Profile"))),false);
			
			redirect($this->controller);

        }
    }

}
