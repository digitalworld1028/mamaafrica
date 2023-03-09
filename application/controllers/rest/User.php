<?php defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class User extends REST_Controller
{
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model("user_model");
        $this->load->model("sms_model");
    }
    public function test_post()
    {
        $headers = getallheaders();
        $headers["test"] = generate_encryption_key();
        //$this->lang->load("rest_controller",$headers["X-APP-LANGUAGE"]);
        $headers["lang"] = _l("text_rest_invalid_credentials");
        print_r($headers);
    }
    public function login_post()
    {
        $this->load->library('form_validation');
        // Validate The Logi User
        $this->form_validation->set_rules('user_phone', _l("Phone No"), 'trim|required');
        $this->form_validation->set_rules('user_password', _l("Password"), 'trim|required');
        
        if ($this->form_validation->run() == false) {
            $this->response(array(
                        RESPONCE => false,
                        MESSAGE => strip_tags($this->form_validation->error_string()),
                        DATA =>strip_tags($this->form_validation->error_string()),
                        CODE => CODE_MISSING_INPUT
                    ), REST_Controller::HTTP_OK);
        } else {
            $post = $this->post();
                    
            $this->db->where("user_phone", $post["user_phone"]);
            $this->db->where("user_type_id", USER_CUSTOMER);
            $q = $this->db->get("users");
            $user = $q->row();
                    
            if (empty($user)) {
                $this->response(array(
                            RESPONCE => false,
                            MESSAGE => _l("Sorry! No User with this phone no"),
                            DATA =>_l("Sorry! No User with this phone no"),
                            CODE => CODE_USER_NOT_FOUND
                        ), REST_Controller::HTTP_OK);
            }
            if ($user->is_mobile_verified == 0) {
                $OTP = generateNumericOTP(6);
                
                $this->load->library("smsapi");
                $msg = _l("OTP ###### use for user verification");
                $msg = str_replace("######",$OTP,$msg);
                $this->sms_model->send($user->user_phone,$msg);
                

                $this->common_model->data_update("users", array("verify_token"=>md5($OTP)), array("user_id"=>$user->user_id));
                $this->response(array(
                            RESPONCE => false,
                            MESSAGE => _l("Please enter verification OTP sent to your email"),
                            DATA =>array("user_email"=>$user->user_email,"otp"=>$OTP),
                            CODE => 108
                        ), REST_Controller::HTTP_OK);
            }
                    
            if ($user->status == 2) {
                $this->response(array(
                            RESPONCE => false,
                            MESSAGE => _l("Sorry! Your account is suspended"),
                            DATA =>_l("Sorry! Your account is suspended"),
                            CODE => 107
                        ), REST_Controller::HTTP_OK);
            }
            if ($user->user_type_id != USER_CUSTOMER) {
                $this->response(array(
                            RESPONCE => false,
                            MESSAGE => _l("Sorry! User not allowed to use this app"),
                            DATA =>_l("Sorry! User not allowed to use this app"),
                            CODE => 110
                        ), REST_Controller::HTTP_OK);
            }
                    
            if ($user->user_password != md5($post["user_password"])) {
                $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => _l("Incorrect password"),
                                        DATA => _l("Incorrect password"),
                                        CODE => 109
                                    ), REST_Controller::HTTP_OK);
            }
            /*
            $this->db->where("user_address.user_id",$user->user_id);
            $this->db->join("user_address","user_address.postal_code = postal_codes.postal_code");
            $q = $this->db->get("postal_codes");
            $postal_code = $q->row();
            if(empty($postal_code)){
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Unfortunately, we do not deliver to your postcode yet!"),
                    DATA => _l("Unfortunately, we do not deliver to your postcode yet!"),
                    CODE => 105
                ), REST_Controller::HTTP_OK);
            }
            */

            $this->userInfo($user);
        }
    }
    public function verifyphone_post()
    {
        $phone = $this->post("user_phone");
        $otp = $this->post("otp");

        if ($phone == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide phone no"),
                DATA => _l("Please provide phone no"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        if ($otp == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide otp"),
                DATA => _l("Please provide otp"),
                CODE => 101
            ), REST_Controller::HTTP_OK);
        }
        $this->db->where("users.user_phone", $phone);
        $q = $this->db->get("users");
        $user = $q->row();
        if (empty($user)) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Phone No not registered with our system"),
                DATA => _l("Phone No not registered with our system"),
                CODE => 102
            ), REST_Controller::HTTP_OK);
        }
        if ($user->verify_token == md5($otp) || IS_TEST) {
            $this->load->model("email_model");
            $this->email_model->send_welcome_mail($user);
            $this->email_model->new_user_mail($user);

            $req_queue = "";
            $array = array("is_mobile_verified"=>"1","verify_token"=>"","status"=>"1");
            $this->common_model->data_update("users", $array, array("user_id"=>$user->user_id));
            $user->is_mobile_verified = "1";
            $user->status = "1";
            $this->userInfo($user);
        } else {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Invalid OTP Try again"),
                DATA => _l("Invalid OTP Try again"),
                CODE => 103
            ), REST_Controller::HTTP_OK);
        }
    }
    private function userInfo($user)
    {
        unset($user->user_password);
        $user->addresses = $this->user_model->get_address($user->user_id);
        $user->settings = $this->user_model->get_settings($user->user_id);
        
        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("Welcome")." ".$user->user_firstname." ".$user->user_lastname,
            DATA => $user,
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }
    public function profile_post()
    {
        $user_id = $this->post("user_id");
        if ($user_id == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide user reference"),
                DATA => _l("Please provide user reference"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        $this->responceByUserID($user_id);
    }
    public function newuser_post()
    {
        $this->load->library('form_validation');
        // Validate The Logi User
        $this->form_validation->set_rules('user_phone', _l("User Phone"), 'trim|required|is_unique[users.user_phone]');
        $this->form_validation->set_rules('user_fullname', _l("Full Name"), 'trim|required');
        $this->form_validation->set_rules('user_password', _l("Password"), 'trim|required');
        $this->form_validation->set_message('is_unique', _l("User phone is already register"));
        
        if ($this->form_validation->run() == false) {
            $this->response(array(
                        RESPONCE => false,
                        MESSAGE => strip_tags($this->form_validation->error_string()),
                        DATA =>strip_tags($this->form_validation->error_string()),
                        CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        } else {
            $post = $this->post();
            $fullname = $post["user_fullname"];
            $names = explode(" ", $fullname);
            
            $first_name = $names[0];
            $last_name = "";
            if (count($names) > 1) {
                $last_name = $names[1];
            }

            $OTP = generateNumericOTP(6);
            
            $this->load->library("smsapi");
            $msg = _l("OTP ###### use for user verification");
            $msg = str_replace("######",$OTP,$msg);
            
            if(!IS_TEST)
                $sent_otp =  $this->sms_model->send($post["user_phone"],$msg);
            else
                $sent_otp = true;

            if ($sent_otp) {
                $insert = array(
                    "user_type_id"=>USER_CUSTOMER,
                    "user_phone"=>$post["user_phone"],
                    "user_firstname"=>$first_name,
                    "user_lastname"=>$last_name,
                    "user_email"=>$post["user_email"],
                    "user_password"=>md5($post["user_password"]),
                    "ios_token"=>$post["ios_token"],
                    "android_token"=>$post["android_token"],
                    "is_email_verified"=>"1",
                    "is_mobile_verified"=>"0",
                    "verify_token"=>md5($OTP),
                    "status"=>"0"
                );
                $user_id = $this->common_model->data_insert("users", $insert, true);
                $insert["user_id"] = $user_id;
                $insert["otp"] = $OTP;
                //$this->email_model->send_welcome_mail((Object)$insert);

                $this->userInfo((Object)$insert);
            } else {
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Something wrong with your phone, Please use correct phone"),
                    DATA =>_l("Something wrong with your phone, Please use correct phone"),
                    CODE => CODE_MISSING_INPUT
                    ), REST_Controller::HTTP_OK);
            }
        }
    }
   
    public function updatesettings_post()
    {
        $this->load->library('form_validation');
        // Validate The Logi User
        $user_id =  $this->post("user_id");
        $user_phone =  $this->post("user_phone");
        
        if ($user_id == null && $user_phone == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide user reference"),
                DATA =>_l("Please provide user reference"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }
       
        $this->form_validation->set_rules('general_notifications', _l("General Notification"), 'trim|required');
        $this->form_validation->set_rules('order_notifications', _l("Order Notifcation"), 'trim|required');
        $this->form_validation->set_rules('general_emails', _l("General Emails"), 'trim|required');
        $this->form_validation->set_rules('order_emails', _l("Order Emails"), 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->response(array(
                        RESPONCE => false,
                        MESSAGE => strip_tags($this->form_validation->error_string()),
                        DATA =>strip_tags($this->form_validation->error_string()),
                        CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        } else {
            if ($user_id == null) {
                $this->db->where("user_phone", $user_phone);
                $q = $this->db->get("users");
                $user = $q->row();
                $user_id = $user->user_id;
            }
            $post = $this->post();
            $insert = array(
                "user_id"=>$user_id,
                "general_notifications"=>$post["general_notifications"],
                "order_notifications"=>$post["order_notifications"],
                "general_emails"=>$post["general_emails"],
                "order_emails"=>$post["order_emails"]
            );
            $setting = $this->user_model->get_settings($user_id);
            if (empty($setting)) {
                $id = $this->common_model->data_insert("user_settings", $insert, true);
                $insert["user_setting_id"] = $id;
            } else {
                $this->common_model->data_update("user_settings", $insert, array("user_id"=>$user_id), true);
                $insert["user_setting_id"] = $setting->user_setting_id;
            }
            $this->response(array(
                                        RESPONCE => true,
                                        MESSAGE => _l("Settings Updated Success"),
                                        DATA => $insert,
                                        CODE => CODE_SUCCESS
                                    ), REST_Controller::HTTP_OK);
        }
    }
    
    private function responceByUserID($user_id){
        $user = $this->user_model->get_by_id($user_id);
        $this->userInfo($user);
    }
    public function update_name_post()
    {
        $this->load->library('form_validation');
        // Validate The Logi User
        $this->form_validation->set_rules('user_id', _l("User ID"), 'trim|required');
        $this->form_validation->set_rules('user_firstname', _l("User First Name"), 'trim|required');
        $this->form_validation->set_rules('user_lastname', _l("User Last Name"), 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->response(array(
                        RESPONCE => false,
                        MESSAGE => strip_tags($this->form_validation->error_string()),
                        DATA =>strip_tags($this->form_validation->error_string()),
                        CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        } else {
            $post = $this->post();
            
            $insert = array(
                "user_firstname"=>$post["user_firstname"],
                "user_lastname"=>$post["user_lastname"]
            );
            
            $this->common_model->data_update("users", $insert, array("user_id"=>$post["user_id"]), true);
            
            $this->responceByUserID($post["user_id"]);
        }
    }


    public function update_phone_post()
    {
        $user_id = $this->post("user_id");
        $user_phone = $this->post("user_phone");

        if ($user_id == null || $user_phone == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please enter user reference"),
                DATA =>_l("Please enter user reference"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }
        $this->db->where("user_id", $user_id);
        $q = $this->db->get("users");
        $user = $q->row();
        if (empty($user)) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Wrong user reference"),
                DATA =>_l("Wrong user reference"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }

        if ($user->user_phone == $user_phone) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please try different phone number"),
                DATA =>_l("Please try different phone number"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }
        $this->db->where("user_phone", $user_phone);
        $q = $this->db->get("users");
        $existing_email = $q->row();
        if (!empty($existing_email)) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("This phone number already registered with our system, Please try different phone number"),
                DATA =>_l("This phone number already registered with our system, Please try different phone number"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }

        $OTP = generateNumericOTP(6);
        
        $this->load->library("smsapi");
        $msg = _l("OTP ###### use for user verification");
        $msg = str_replace("######",$OTP,$msg);
        $res =  $this->sms_model->send($user_phone,$msg);
        
        $res = true;
        if ($res) {
            $this->common_model->data_update("users", array("verify_token"=>md5($OTP)), array("user_id"=>$user->user_id));
            $this->response(array(
                        RESPONCE => true,
                        MESSAGE => _l("Verify Your Phone Number"),
                        DATA =>array("user_id"=>$user->user_id,"user_phone"=>$user_phone,"otp"=>$OTP),
                        CODE => CODE_SUCCESS
                    ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Faild to sent verification on this phone number"),
                    DATA =>_l("Faild to sent verification on this phone number"),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
        }
    }
    public function resendotp_post()
    {
        $user_phone = $this->post("user_phone");

        if ($user_phone == null) {
            $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Please enter user reference"),
                    DATA =>_l("Please enter user reference"),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
        }
        $this->db->where("users.user_phone", $user_phone);
        $q = $this->db->get("users");
        $user = $q->row();
        if (empty($user)) {
            $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Phone number not registered with our system"),
                    DATA => _l("Phone number not registered with our system"),
                    CODE => 102
                ), REST_Controller::HTTP_OK);
        }

        $OTP = generateNumericOTP(6);
        
        $this->load->library("smsapi");
        $msg = _l("OTP ###### use for user verification");
        $msg = str_replace("######",$OTP,$msg);
        $res =  $this->sms_model->send($user_phone,$msg);

        if ($res) {
            $this->common_model->data_update("users", array("verify_token"=>md5($OTP)), array("user_id"=>$user->user_id));
            $this->response(array(
                        RESPONCE => true,
                        MESSAGE => _l("Verify Your Phone Number"),
                        DATA =>array("user_id"=>$user->user_id,"user_phone"=>$user_phone),
                        CODE => CODE_SUCCESS
                    ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Faild to sent verification on this phone number"),
                    DATA =>_l("Faild to sent verification on this phone number"),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
        }
    }
    public function verify_update_phone_post()
    {
        $user_phone = $this->post("user_phone");
        $otp = $this->post("otp");
        $user_id = $this->post("user_id");
        if ($user_phone == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide phone number"),
                DATA => _l("Please provide phone number"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        if ($otp == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide otp"),
                DATA => _l("Please provide otp"),
                CODE => 101
            ), REST_Controller::HTTP_OK);
        }
        if ($user_id == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide user reference"),
                DATA => _l("Please provide  user reference"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        $this->db->where("users.user_id", $user_id);
        $q = $this->db->get("users");
        $user = $q->row();
        if (empty($user)) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("User not registered with our system"),
                DATA => _l("User not registered with our system"),
                CODE => 102
            ), REST_Controller::HTTP_OK);
        }
        if ($user->verify_token == md5($otp)) {
            $this->common_model->data_update("users", array("user_phone"=>$user_phone), array("user_id"=>$user->user_id));
            $user->user_phone = $user_phone;
            $this->userInfo($user);
        } else {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Invalid OTP Try again"),
                DATA => _l("Invalid OTP Try again"),
                CODE => 103
            ), REST_Controller::HTTP_OK);
        }
    }

    public function update_email_post()
    {
        $this->load->library('form_validation');
        // Validate The Logi User
        $this->form_validation->set_rules('user_id', _l("User ID"), 'trim|required');
        $this->form_validation->set_rules('user_email', _l("User Email"), 'trim|required|valid_email');
        if ($this->form_validation->run() == false) {
            $this->response(array(
                        RESPONCE => false,
                        MESSAGE => strip_tags($this->form_validation->error_string()),
                        DATA =>strip_tags($this->form_validation->error_string()),
                        CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        } else {
            $post = $this->post();
            
            $insert = array(
                "user_email"=>$post["user_email"]
            );
            
            $this->common_model->data_update("users", $insert, array("user_id"=>$post["user_id"]), true);
            
            $this->responceByUserID($post["user_id"]);
        }
    }
    public function photo_post()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', _l("User ID"), 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->response(array(
                    RESPONCE => false,
                    MESSAGE => strip_tags($this->form_validation->error_string()),
                    DATA =>strip_tags($this->form_validation->error_string()),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
        } else {
            $file_name = "";
            if (isset($_FILES["user_image"]) && $_FILES['user_image']['size'] > 0) {
                $path = PROFILE_IMAGE_PATH;
                    
                if (!file_exists($path)) {
                    mkdir($path);
                }
                $this->load->library("imagecomponent");
                $file_name_temp = $this->imagecomponent->getuniquefilename($_FILES['user_image']['name']);//md5(uniqid())."_".$_FILES['user_image']['name'];
                $file_name = $this->imagecomponent->upload_image_and_thumbnail('user_image', 680, 200, $path, 'crop', false, $file_name_temp);
                $update_array["user_image"] = $file_name;
                $this->common_model->data_update("users", $update_array, array("user_id"=>$this->post("user_id")));
                      
                $this->responceByUserID($this->post("user_id"));

            } else {
                $this->response(array(
                        RESPONCE => false,
                        MESSAGE => _l("No File Selected"),
                        DATA => _l("No File Selected"),
                        CODE => CODE_MISSING_INPUT
                    ), REST_Controller::HTTP_OK);
            }
        }
    }
    public function forgotpassword_post()
    {
        $user_phone = $this->post("user_phone");
        if ($user_phone == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide your registered phone number"),
                DATA => _l("Please provide your registered phone number"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }
        $this->db->where("user_phone", $user_phone);
        $q = $this->db->get("users");
        $user = $q->row();
        if (empty($user)) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Sorry! email not exist with our system"),
                DATA => _l("Sorry! email not exist with our system"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }
        $OTP = generateNumericOTP(6);
        
        $this->load->library("smsapi");
        $msg = _l("OTP ###### use for forgot password");
        $msg = str_replace("######",$OTP,$msg);
        $res =  $this->sms_model->send($user_phone,$msg);
        
        if ($res) {
            $this->common_model->data_update("users", array("verify_token"=>md5($OTP)), array("user_id"=>$user->user_id));
            $this->response(array(
                        RESPONCE => true,
                        MESSAGE => _l("We sent otp to your mobile"),
                        DATA =>array("user_id"=>$user->user_id,"user_phone"=>$user->user_phone,"otp"=>$OTP),
                        CODE => CODE_SUCCESS
                    ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Faild to sent verification on this phone number"),
                    DATA =>_l("Faild to sent verification on this phone number"),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
        }
    }
    public function forgotpasswordverify_post()
    {
        $user_phone = $this->post("user_phone");
        $otp = $this->post("otp");
        if ($user_phone == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide phone number"),
                DATA => _l("Please provide phone number"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        if ($otp == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide otp"),
                DATA => _l("Please provide otp"),
                CODE => 101
            ), REST_Controller::HTTP_OK);
        }
        
        $this->db->where("users.user_phone", $user_phone);
        $q = $this->db->get("users");
        $user = $q->row();
        if (empty($user)) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("User not registered with our system"),
                DATA => _l("User not registered with our system"),
                CODE => 102
            ), REST_Controller::HTTP_OK);
        }
        if ($user->verify_token == md5($otp)) {
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("OTP Verified"),
                DATA => array("user_id"=>$user->user_id,"user_phone"=>$user->user_phone),
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Invalid OTP Try again"),
                DATA => _l("Invalid OTP Try again"),
                CODE => 103
            ), REST_Controller::HTTP_OK);
        }
    }
    public function resetpassword_post()
    {
        $user_id = $this->post("user_id");
        $n_password = $this->post("n_password");
        $r_password = $this->post("r_password");
        if ($n_password == null || $r_password == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide required fields"),
                DATA => _l("Please provide required fields"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }
        $this->db->where("user_id", $user_id);
        $q = $this->db->get("users");
        $user = $q->row();
        if (empty($user)) {
            $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Sorry! You provide wrong existing password"),
                    DATA => _l("Sorry! You provide wrong existing password"),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
        }
        if ($n_password != $r_password) {
            $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Sorry! Repeat password not match with new password"),
                    DATA => _l("Sorry! Repeat password not match with new password"),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
        }
           
        $this->common_model->data_update("users", array("user_password"=>md5($n_password)), array("user_id"=>$user->user_id));
        $this->response(array(
                        RESPONCE => true,
                        MESSAGE => _l("Your password change successfully"),
                        DATA =>_l("Your password change successfully"),
                        CODE => CODE_SUCCESS
                    ), REST_Controller::HTTP_OK);
    }
    public function changepassword_post()
    {
        $user_id = $this->post("user_id");
        $c_password = $this->post("c_password");
        $n_password = $this->post("n_password");
        $r_password = $this->post("r_password");
        if ($c_password == null || $n_password == null || $r_password == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide required fields"),
                DATA => _l("Please provide required fields"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }
        $this->db->where("user_id", $user_id);
        $this->db->where("user_password", md5($c_password));
        $q = $this->db->get("users");
        $user = $q->row();
        if (empty($user)) {
            $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Sorry! You provide wrong existing password"),
                    DATA => _l("Sorry! You provide wrong existing password"),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
        }
        if ($n_password != $r_password) {
            $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Sorry! Repeat password not match with new password"),
                    DATA => _l("Sorry! Repeat password not match with new password"),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
        }
           
        $this->common_model->data_update("users", array("user_password"=>md5($n_password)), array("user_id"=>$user->user_id));
        $this->response(array(
                        RESPONCE => true,
                        MESSAGE => _l("Your password change successfully"),
                        DATA =>_l("Your password change successfully"),
                        CODE => CODE_SUCCESS
                    ), REST_Controller::HTTP_OK);
    }
    public function playerid_post()
    {
        $this->load->library('form_validation');
        // Validate The Logi User
        $this->form_validation->set_rules('user_id', _l("User ID"), 'trim|required');
        $this->form_validation->set_rules('player_id', _l("OneSignal Player ID"), 'trim|required');
        $this->form_validation->set_rules('device', _l("Device Type"), 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->response(array(
                        RESPONCE => false,
                        MESSAGE => strip_tags($this->form_validation->error_string()),
                        DATA =>strip_tags($this->form_validation->error_string()),
                        CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        } else {
            $post = $this->post();
            
            $insert = array(
                "android_token"=>$post["player_id"]
            );
            
            if ($post["device"] == "ios") {
                $insert = array(
                    "ios_token"=>$post["player_id"]
                );
            }

            $this->common_model->data_update("users", $insert, array("user_id"=>$post["user_id"]), true);
            
            $this->response(array(
                                        RESPONCE => true,
                                        MESSAGE => _l("One Signal Token Updated"),
                                        DATA => $insert,
                                        CODE => CODE_SUCCESS
                                    ), REST_Controller::HTTP_OK);
        }
    }
}
