<?php defined('BASEPATH') or exit('No direct script access allowed');
class Payment extends MY_Controller
{
    protected $controller;
    protected $data;
    protected $options;
    public function __construct()
    {
        parent::__construct();
        $this->controller = $this->router->fetch_class();
        $this->options = get_options_by_type("payment_settings");
        $this->load->model("orders_model");
    }
    
    public function paymentSuccess($token)
    {
        header('Content-Type: application/json');
        $response = array(
            RESPONCE => true,
            DATA => array("url"=>site_url("rest/order/successpayment?token=".$token),"token"=>$token),
            CODE => CODE_SUCCESS,
            MESSAGE => "Payment Successfully"
        );
        echo json_encode($response);
    }
    public function paymentFalied($token="")
    {
        header('Content-Type: application/json');
        $response = array(
            RESPONCE => false,
            DATA => array("url"=>site_url("rest/order/failedpayment?token=".$token),"token"=>$token),
            CODE => CODE_SUCCESS,
            MESSAGE => "Payment Failed"
        );
        echo json_encode($response);
    }
    public function paypalCancel()
    {
        $token = $this->input->get("token");
        redirect("rest/order/failedpayment?token=".$token);
    }
    private function updatePaymentRef($payment_ref, $logs, $payment_date)
    {
        $order = $this->orders_model->get_by_id("", $payment_ref);
        $order_id = $order->order_id;
        $this->common_model->data_update("orders", array("status"=>ORDER_PENDING, "payment_log"=>$logs,"paid_date"=>date(MYSQL_DATE_TIME_FORMATE, strtotime($payment_date))), array("order_id"=>$order_id));
        $this->common_model->data_update("order_status", array("status"=>ORDER_PENDING), array("order_id"=>$order_id));
    }
    public function paypalResult()
    {
        $token = $this->input->get("token");
        $this->load->library("paypal_payment");
        $response = $this->paypal_payment->orderCapture($token);
        if ($response->statusCode == 201) {
            $orderId = $response->result->id;
            $payment_log = json_encode($response);
            $this->updatePaymentRef($orderId, $payment_log, date(MYSQL_DATE_TIME_FORMATE));
            redirect("payment/paymentSuccess/".$orderId);
        } else {
            redirect("payment/paymentFalied");
        }
    }
    
    /**
     * BOLT PAYU PATMENT SYSTEM
     */
    /*
    public function payumoney($token)
    {
        $this->data["trans_id"] = $token;
        $this->data["options"] = $this->options;
        $order = $this->orders_model->get_by_id("", $token);
        $this->load->model("user_model");
        $user = $this->user_model->get_by_id($order->user_id);
        $this->data["order"] = $order;
        $this->data["user"] = $user;
        $this->load->view("payment/payu_payment", $this->data);
    }
    public function payUResult()
    {
        $postdata = $this->input->post();
        $msg = '';
        if (isset($postdata ['key'])) {
            $key				=   $postdata['key'];
            $salt				=   $this->options["payu_salt"];
            $txnid 				= 	$postdata['txnid'];
            $amount      		= 	$postdata['amount'];
            $productInfo  		= 	$postdata['productinfo'];
            $firstname    		= 	$postdata['firstname'];
            $email        		=	$postdata['email'];
            $udf5				=   $postdata['udf5'];
            $mihpayid			=	$postdata['mihpayid'];
            $status				= 	$postdata['status'];
            $resphash				= 	$postdata['hash'];
            //Calculate response hash to verify
            $keyString 	  		=  	$key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||'.$udf5.'|||||';
            $keyArray 	  		= 	explode("|", $keyString);
            $reverseKeyArray 	= 	array_reverse($keyArray);
            $reverseKeyString	=	implode("|", $reverseKeyArray);
            $CalcHashString 	= 	strtolower(hash('sha512', $salt.'|'.$status.'|'.$reverseKeyString));
    
    
            if ($status == 'success'  && $resphash == $CalcHashString) {
                $this->updatePaymentRef($txnid,json_encode($postdata), date(MYSQL_DATE_TIME_FORMATE));
                redirect("payment/paymentSuccess/".$txnid);
            //Do success order processing here...
            } else {
                redirect("payment/paymentFalied");
            }
        }
    }
    */

    public function payUResult(){
     
            $this->load->library("payu_payment");
            
            $status=$_POST["status"];
            $firstname=$_POST["firstname"];
            $amount=$_POST["amount"]; //Please use the amount value from database
            $txnid=$_POST["txnid"];
            $posted_hash=$_POST["hash"];
            $key=$_POST["key"];
            $productinfo=$_POST["productinfo"];
            $email=$_POST["email"];
            $udf1 = $_POST["udf1"];
            $udf2 = $_POST["udf2"];   
            $salt=$this->payu_payment->SALT; //Please change the value with the live salt for production environment
            $retHashSeq = '';
            
                //Validating the reverse hash
                If (isset($_POST["additionalCharges"])) {
                       $additionalCharges=$_POST["additionalCharges"];
                        $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||'.$udf2.'|'.$udf1.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
                }else {	  
                    $retHashSeq = $salt.'|'.$status.'|||||||||'.$udf2.'|'.$udf1.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
                }
                $hash = hash("sha512", $retHashSeq);
                if ($hash == $posted_hash) {
                    if ($status == "success") {
                        $this->updatePaymentRef($txnid, $retHashSeq, date(MYSQL_DATE_TIME_FORMATE));
                        redirect("payment/paymentSuccess/".$txnid);
                    } else {
                        redirect("payment/paymentFalied");
                    }
                }else{
                    redirect("payment/paymentFalied");
                }
    }
    public function payumoney($token){
        $this->data["trans_id"] = $token;
        $order = $this->orders_model->get_by_id("", $token);
        $this->load->model("user_model");
        $user = $this->user_model->get_by_id($order->user_id);

        $this->load->library("payu_payment");
        $posted = array();
        $posted["key"] = $this->payu_payment->MERCHANT_KEY;
        $posted["surl"] = site_url("payment/payUResult");
        $posted["furl"] = site_url("payment/payUResult");
        $posted["txnid"] =$token ;
        $posted["amount"] = $order->net_amount;
        $posted["firstname"] = $user->user_firstname;
        $posted["email"] = $user->user_email;
        $posted["phone"] = $user->user_phone;
        $posted["productinfo"] = "Food Order";
        $posted["udf1"] = $order->order_id;
        $posted["udf2"] = "sale";
        $posted["service_provider"] = "";

        $posted["hash"] = $this->payu_payment->get_hash($posted);
        echo $this->payu_payment->get_html($posted);
    }
}
