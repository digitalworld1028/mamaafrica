<?php
class Payment_lib
{
    protected $options;
    private $_CI;
    public function __construct()
    {
        $this->_CI = &get_instance();
        $this->options = get_options_by_type("payment_settings");    
    }
    function doPayment($order_id,$amount){
        if (isset($this->options["pay_via"]) && $this->options["pay_via"] == "paypal") {
            $this->_CI->load->library("paypal_payment");
            $response = $this->_CI->paypal_payment->createOrder($order_id, $amount);
            if ($response->statusCode == 201) {
                $orderId = $response->result->id;
                $links = $response->result->links;
                foreach ($links as $link) {
                    if ($link->rel == "approve") {
                        return array("payment_ref"=>$orderId, "redirect_url"=>$link->href,"response"=>true);
                    }
                }
            }
        }else if(isset($this->options["pay_via"]) && $this->options["pay_via"] == "payumoney"){
            $ref_id = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
            return array("payment_ref"=>$ref_id, "redirect_url"=>site_url("payment/payumoney/".$ref_id),"response"=>true);
        }
        return array("response"=>false);
    }
}