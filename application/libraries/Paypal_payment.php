<?php
require APPPATH . '/third_party/vendor/autoload.php';
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalHttp\HttpException;
ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
class Paypal_payment
{
    protected $enviroment;
    protected $client_id;
    protected $client_secret;
    protected $client;
    protected $options;
    public function __construct()
    {
        //parent::__construct();
        $this->options = get_options(array("currency","paypal_enviroment","paypal_client_id","paypal_client_secret"));
        $this->client_id = $this->options["paypal_client_id"];
        $this->client_secret = $this->options["paypal_client_secret"];
        $this->enviroment = $this->options["paypal_enviroment"];


        if ($this->enviroment == "sandbox") {
            $environment = new SandboxEnvironment($this->client_id, $this->client_secret);
            $this->client = new PayPalHttpClient($environment);
        } elseif ($this->enviroment == "production") {
            $environment = new ProductionEnvironment($this->client_id, $this->client_secret);
            $this->client = new PayPalHttpClient($environment);
        }
    }
    public function createOrder($order_id,$amount)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
                     "intent" => "CAPTURE",
                     "purchase_units" => [[
                         "reference_id" => $order_id,
                         "amount" => [
                             "value" => $amount,
                             "currency_code" => $this->options["currency"]
                         ]
                     ]],
                     "application_context" => [
                          "cancel_url" => site_url("payment/paypalCancel"),
                          "return_url" => site_url("payment/paypalResult")
                     ]
                 ];

        try {
            // Call API with your client and get a response for your call
            $response = $this->client->execute($request);
            return $response;
        } catch (HttpException $ex) {
            echo $ex->statusCode;
            print_r($ex->getMessage());
        }
    }
    public function orderCapture($order_id)
    {
        $request = new OrdersCaptureRequest($order_id);
        $request->prefer('return=representation');
        try {
            // Call API with your client and get a response for your call
            $response = $this->client->execute($request);
    
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            return $response;
        } catch (HttpException $ex) {
            return $ex;
        }
    }
}
