<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MY_Controller
{

    /**
     * login page
     * @param username for user name
     * @param password for password
     * @return login page
     */
    public function index()
    {
        
        
        if (_is_user_login($this)) {
            redirect(_get_user_redirect($this));
        } else {
            $this->load->helper('cookie');
            $data = array("error" => "");
            if (isset($_POST)) {

                $this->load->library('form_validation');

                $this->form_validation->set_rules('username', 'User Name', 'trim|required');
                $this->form_validation->set_rules('password', 'Password', 'trim|required');
                if ($this->form_validation->run() == false) {
                    if ($this->form_validation->error_string() != "") {
                        $this->message_model->error($this->form_validation->error_string());
                    }

                } else {

                    $q = $this->db->query("Select * from `users` where (`user_phone`='" . $this->
                        input->post("username") . "') and user_password='" . md5($this->input->post("password")) ."'   Limit 1");

                    if ($q->num_rows() > 0) {
                        $row = $q->row();
                        if ($row->status == "0") {
                            _set_flash_message(_l("msg_account_active"), "error");

                        } else {
                            $rememberme = $this->input->post('remember');

                            if (isset($rememberme) && $rememberme == "on") {

                                set_cookie("c_username", $this->input->post("username"), '3600');
                                set_cookie("c_password", $this->input->post("password"), '3600');
                                set_cookie("remiber_me", $rememberme, '3600');
                            } else {
                                delete_cookie('c_username');
                                delete_cookie('c_password');
                                delete_cookie("remiber_me");
                            }

                            $newdata = array(                                
                                'user_fullname' => $row->user_firstname." ".$row->user_lastname,
                                'user_email' => $row->user_email,
                                'user_phone' => $row->user_phone,
                                'logged_in' => true,
                                'user_id' => $row->user_id,
                                'user_type_id' => $row->user_type_id,
                                'user_image' => $row->user_image);

                            $this->session->set_userdata($newdata);

                            redirect(_get_user_redirect($this));

                        }
                    } else {
                        _set_flash_message(_l("msg_correct_user_n_password"), "error");
                    }
                }
            }
            $data["active"] = "login";
            $this->load->view('admin/login', $data);
        }

    }

    /**
     * logout page
     * @return login page
     */
    function logout()
    {
        $this->session->sess_destroy();
        redirect();
    }

}
