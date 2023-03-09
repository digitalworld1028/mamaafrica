<?php defined('BASEPATH') or exit('No direct script access allowed');
class Products extends MY_Controller
{
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/" . $this->router->fetch_class();
        $this->table_name = "products";
        $this->primary_key = "product_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;

        if (_is_admin()) {

        } else {
            redirect("login");
            exit();
        }
        $this->load->model("products_model");
        $this->load->model("categories_model");
        $this->load->model("productoptions_model");

        $this->data["categories"] = $this->categories_model->get();
    }

    public function index()
    {
        $filter = array();
        $post = $this->input->post();

        if (isset($post["category_id"])) {
            $filter["products.category_id"] = $post["category_id"];
        }

        $this->data["field"] = $post;
        $this->data["select2"] = true;
        $this->data["active_menu_link"] = array(site_url("admin/products"));
        $this->data["data"] = $this->products_model->get($filter, "", "", "", "");
        $this->data["page_content"] = $this->load->view($this->controller . "/list", $this->
            data, true);
        $this->data["page_script"] = $this->load->view($this->controller .
            "/list_script", $this->data, true);

        $this->load->view(BASE_TEMPLATE, $this->data);
    }

    public function delete($id)
    {
        $id = _decrypt_val($id);
        if(IS_TEST){
            $data['responce'] = false;
            $data['error'] = _l("This feature disable in Demo");
            echo json_encode($data);
        }else{
            $row = $this->products_model->get_by_id($id);
            if (!empty($row)) {
                $pk = $this->primary_key;
                $this->common_model->data_remove($this->table_name, array($this->primary_key =>
                    $row->$pk), false);
                $data['responce'] = true;
                $data['message'] = $this->message_model->action_mesage("delete", "Product", true);
                echo json_encode($data);
            } else {
                $data['responce'] = false;
                $data['error'] =_l("Products not available");
                echo json_encode($data);
            }
        }
    }

    public function add()
    {
        $this->action();
        $this->data["select2"] = true;
        $this->data["active_menu_link"] = array(site_url("admin/products"));
        $this->data["fileupload"] = true;
        $this->data["ckeditor"] = array("product_desc_en", "product_desc_ar");
        $field = $this->input->post();
        $this->data["field"] = $field;
        $this->data["ajax_option"] = true;

        if (isset($field["product_id"])) {
            $this->data["productoptions"] = $this->productoptions_model->get(array('product_options.product_id' =>
                    $field["product_id"]));
        }

        $this->data["page_content"] = $this->load->view($this->controller . "/add", $this->
            data, true);
        $this->load->view(BASE_TEMPLATE, $this->data);
    }

    private function action()
    {
        $post = $this->input->post();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('category_id', _l("Category") . _l("(En)"),
            'trim|required');
        $this->form_validation->set_rules('product_name_en', _l("Product Name") . _l("(En)"),
            'trim|required');
        $this->form_validation->set_rules('product_name_ar', _l("Product Name") . _l("(Ar)"),
            'trim|required');      
        $this->form_validation->set_rules('price', _l("Price"), 'trim|required');

        $responce = array();
        if ($this->form_validation->run() == false) {
            if ($this->form_validation->error_string() != "") {
                _set_flash_message($this->form_validation->error_string(), "error");
            }
        } else {
            $add_data = array(
                "category_id" => $post["category_id"],
                "product_name_en" => $post["product_name_en"],
                "product_name_ar" => $post["product_name_ar"],
                "product_desc_en" => $post["product_desc_en"],
                "product_desc_ar" => $post["product_desc_ar"],
                "is_promotional" => (isset($post["is_promotional"]) && $post["is_promotional"] ==
                    "on") ? 1 : 0,
                "is_veg" => (isset($post["is_veg"]) && $post["is_veg"] == "on") ? 1 : 0,
                "status" => (isset($post["status"]) && $post["status"] == "on") ? 1 : 0,
                "price" => $post["price"],
                "price_note" => $post["price_note"],
                "calories" => $post["calories"],
                );

            if (isset($_FILES["product_image"]) && $_FILES['product_image']['size'] > 0) {
                $path = PRODUCT_IMAGE_PATH;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $this->load->library("imagecomponent");
                $file_name_temp = $this->imagecomponent->getuniquefilename($_FILES['product_image']['name']); //md5(uniqid())."_".$_FILES['cat_image']['name'];
                $file_name = $this->imagecomponent->upload_image_and_thumbnail('product_image',
                    840, 200, $path, 'crop', true, $file_name_temp);
                $add_data["product_image"] = $file_name_temp;
            }

            if (!empty($post["id"])) {

                $id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name, $add_data, array($this->
                        primary_key => $id), true);
                $this->message_model->action_mesage("update", "Product", false);

                redirect($this->controller);
            } else {
                $id = $this->common_model->data_insert($this->table_name, $add_data, true);
                $this->message_model->action_mesage("add", "Product", false);
                if (isset($post["option_name_en"]) && isset($post["option_price"]) && $post["option_name_en"] !=
                    "" && $post["option_price"] != "") {
                    $insert = array(
                        "product_id" => $id,
                        "option_name_en" => $post["option_name_en"],
                        "option_name_ar" => $post["option_name_ar"],
                        "option_desc_en" => $post["option_desc_en"],
                        "option_desc_ar" => $post["option_desc_ar"],
                        "price" => $post["option_price"],
                        "multiple" => (isset($post["multiple"]) && $post["multiple"] == "on") ? 1 : 0,);

                    $this->common_model->data_insert("product_options", $insert, true);
                }
                redirect($this->controller);
            }
        }
    }


    public function edit($id)
    {
        $id = _decrypt_val($id);
        $this->action();
        $field = $this->products_model->get_by_id($id);
        if (empty($field)) {
            exit();
        }
        $this->data["select2"] = true;
        $this->data["active_menu_link"] = array(site_url("admin/products"));
        $this->data["fileupload"] = true;

        $this->data["ckeditor"] = array("product_desc_en", "product_desc_ar");
        $this->data["field"] = $field;
        $this->data["ajax_option"] = true;
        $this->load->model("productoptions_model");

        if (isset($id)) {
            $this->data["productoptions"] = $this->productoptions_model->get(array('product_options.product_id' =>
                    $id));
        }

        $this->data["page_content"] = $this->load->view($this->controller . "/add", $this->
            data, true);

        $this->load->view(BASE_TEMPLATE, $this->data);
    }


    public function delete_option($id)
    {
        $id = _decrypt_val($id);

        $this->load->model("productoptions_model");
        $row = $this->productoptions_model->get_by_id($id);
        if (!empty($row)) {
            $this->common_model->data_remove("product_options", array("product_option_id" =>
                    $row->product_option_id), true);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete",
                "Product Option", true);

        } else {
            $data['responce'] = false;
            $data['error'] = "Product Options not available";
            echo json_encode($data);
        }
    }

    public function get_product_by_id()
    {
        $id = $this->input->post("id");
        $data = $this->coupons_model->get(array($this->table_name . '.' . $this->
                primary_key => $id));
        echo json_encode($data);
    }

    function set_options()
    {
        $post = $this->input->post();
        $id = _decrypt_val($post["id"]);
        $count = $post["count"];
        $insert = array(
            "product_id" => $id,
            "option_name_en" => $post["option_name_en"],
            "option_name_ar" => $post["option_name_ar"],
            "option_desc_en" => $post["option_desc_en"],
            "option_desc_ar" => $post["option_desc_ar"],
            "price" => $post["option_price"],
            "multiple" => (isset($post["multiple"]) && $post["multiple"] == "on") ? 1 : 0);

        $id = $this->common_model->data_insert("product_options", $insert, true);
        $dt = $this->productoptions_model->get_by_id($id);
        $this->load->view("admin/products/row_options", array(
            "dt" => $dt,
            "count" => $count,
            "controller" => $this->controller));
    }

    function quickoption($id)
    {
        $id = _decrypt_val($id);
        $field = $this->products_model->get_by_id($id);
        if (empty($field)) {
            exit();
        }

        $this->data["productoptions"] = $this->productoptions_model->get(array('product_options.product_id' =>
                $id));

        $this->data["r_index"] = $this->input->post("r_index");
        $this->data["field"] = $field;

        $this->load->view($this->controller . "/quick_options", $this->data);
        $this->load->view($this->controller . "/quick_option_script", $this->data);
    }
}
