<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payu_payment 
{
    public $hash_sequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
    public $MERCHANT_KEY;
    public $SALT;
    public $PAYU_BASE_URL = "https://test.payu.in";
    protected $options;  
    public function __construct()
    {
        $this->options = get_options(array("currency","payu_merchant_key","payu_salt","payu_enviroment"));
        if($this->options["payu_enviroment"] == "sandbox"){
            $this->PAYU_BASE_URL = "https://test.payu.in";
        }else{
            $this->PAYU_BASE_URL = "https://secure.payu.in";
        }
        $this->MERCHANT_KEY = $this->options["payu_merchant_key"];
        $this->SALT = $this->options["payu_salt"];
    }

    public function get_hash($posted){
            $hashSequence = $this->hash_sequence;
            $hash = '';
            $hash_string = '';
            if(empty($posted['hash']) && sizeof($posted) > 0) {
                
              if(
                      empty($posted['key'])
                      || empty($posted['txnid'])
                      || empty($posted['amount'])
                      || empty($posted['firstname'])
                      || empty($posted['email'])
                      || empty($posted['phone'])
                      || empty($posted['productinfo'])
                      || empty($posted['surl'])
                      || empty($posted['furl'])
                     
              ) {
                $formError = 1;
              } else {
                
            	$hashVarsSeq = explode('|', $hashSequence);
                $hash_string = '';
            	foreach($hashVarsSeq as $hash_var) {
                  $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
                  $hash_string .= '|';
                }
            
                $hash_string .= $this->SALT;
            
            
                $hash = strtolower(hash('sha512', $hash_string));
                
              }
            } elseif(!empty($posted['hash'])) {
              $hash = $posted['hash'];
              
            }
            return $hash;
    }
    public function get_html($data){
        ob_start();
        ?>
            <html>
              <head>
              
              </head>
              <body onload="submitPayuForm()">
                <h2>PayU Form</h2>
                <br/>
                <?php if(isset($formError)) { ?>
                  <span style="color:red">Please fill all mandatory fields.</span>
                  <br/>
                  <br/>
                <?php } ?>
                <form action="<?php echo $this->PAYU_BASE_URL."/_payment"; ?>" method="post" name="payuForm" >
                  <input type="hidden" name="key" value="<?php echo $this->MERCHANT_KEY; ?>" />
                  <input type="hidden" name="hash" value="<?php echo $data["hash"]; ?>"/>
                  <input type="hidden" name="txnid" value="<?php echo $data["txnid"]; ?>" />
                  <input type="hidden" name="surl" value="<?php echo $data["surl"]; ?>" />   <!--Please change this parameter value with your success page absolute url like http://mywebsite.com/response.php. -->
                  <input type="hidden" name="furl" value="<?php echo $data["furl"]; ?>" /><!--Please change this parameter value with your failure page absolute url like http://mywebsite.com/response.php. -->
            	  <input type="hidden" name="curl" value="<?php echo $data["furl"]; ?>" />
            	  <input type="hidden"  name="amount" value="<?php echo $data['amount']  ?>" /> 
            	  <input type="hidden" name="firstname" id="firstname" value="<?php echo $data['firstname']; ?>" />
            	  <input type="hidden" name="email" id="email" value="<?php echo $data['email']; ?>" /> 
            	  <input type="hidden" name="phone" value="<?php echo $data['phone']; ?>" />
                  <input type="hidden" name="productinfo" value="<?php echo $data['productinfo']; ?>" />
                  <input type="hidden" name="service_provider" value="<?php echo $data['service_provider']; ?>" />
                  <input type="hidden" name="udf1" value="<?php echo $data['udf1']; ?>" />
                  <input type="hidden" name="udf2" value="<?php echo $data['udf2']; ?>" />
                  <?php if(!$data["hash"]) { ?>
                        <td colspan="4"><input type="submit" value="Submit" /></td>
                  <?php } ?>
                  
                </form>
                
                <script>
                var hash = '<?php echo $data["hash"]; ?>';
                function submitPayuForm() {
                  if(hash == '') {
                    return;
                  }
                  var payuForm = document.forms.payuForm;
                  payuForm.submit();
                }
                submitPayuForm();
              </script>
              </body>
            </html>

        <?php
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}