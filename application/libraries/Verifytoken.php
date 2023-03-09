<?php   defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . 'third_party/vendor/autoload.php';

class Verifytoken
{
    public $code;
    public $personalToken;
    public $userAgent;
    public function __construct()
    {
        
        $this->personalToken = get_option("env_token");
        $this->userAgent = "https://dsinfoway.com";
    }
    function verify($code){
        $this->code = $code;
        $code = trim($this->code);

        // Make sure the code looks valid before sending it to Envato
        if (!preg_match("/^([a-f0-9]{8})-(([a-f0-9]{4})-){3}([a-f0-9]{12})$/i", $this->code)) {
            throw new Exception("Invalid code");
        }
        $header = array(
            "Authorization: Bearer $this->personalToken",
            "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:41.0) Gecko/20100101 Firefox/41.0"
        );
        
        // Build the request
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code=$this->code",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            
            CURLOPT_HTTPHEADER => $header
        ));

        // Send the request with warnings supressed
        $response = @curl_exec($ch);
       
        // Handle connection errors (such as an API outage)
        // You should show users an appropriate message asking to try again later
        if (curl_errno($ch) > 0) { 
            throw new Exception("Error connecting to API: " . curl_error($ch));
        }

        // If we reach this point in the code, we have a proper response!
        // Let's get the response code to check if the purchase code was found
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // HTTP 404 indicates that the purchase code doesn't exist
        if ($responseCode === 404) {
            return array("response"=>false,"data"=>"Purchase code Invalid");
        }

        // Anything other than HTTP 200 indicates a request or API error
        // In this case, you should again ask the user to try again later
        if ($responseCode !== 200) {
            return array("response"=>false,"data"=>"Failed to validate code due to an error: HTTP {$responseCode}");
        }

        // Parse the response into an object with warnings supressed
        $body = @json_decode($response);
       
        // Check for errors while decoding the response (PHP 5.3+)
        if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            return array("response"=>false,"data"=>"Error parsing response");
        }

        // Now we can check the details of the purchase code
        // At this point, you are guaranteed to have a code that belongs to you
        // You can apply logic such as checking the item's name or ID
       
        return  array("response"=>true,"data"=>$body->item->id); // (int) 17022701
        
    }
}