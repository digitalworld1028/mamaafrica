<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Apppages extends MY_Controller
{
    public $lang = "english";
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model("app_pages_model");
    }
    function about($lang="english"){
        $this->lang = $lang;
        $this->get_page(1);
    }
    function contact($lang="english"){
        $this->lang = $lang;
        $this->get_page(3);
    }
    function tnc($lang="english"){
        $this->lang = $lang;
        $this->get_page(5);
    }
    function policy($lang="english"){
        $this->lang = $lang;
        $this->get_page(7);
    }
    
    function get_page($page_id){
        $page = $this->app_pages_model->get_by_id($page_id);
        if($this->lang == "arabic"){
            $page->page_title = $page->page_title_ar;
            $page->page_content = $page->page_content_ar;
            unset($page->page_title_ar);
            unset($page->page_content_ar);
        }else{
            $page->page_title = $page->page_title_en;
            $page->page_content = $page->page_content_en;
            unset($page->page_title_en);
            unset($page->page_content_en);
        }
        $this->load->view('api/app_pages',array("desc"=>$page->page_content));
    }
}
