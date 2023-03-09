<?php

if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') == 0){
	//Request hash
	$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';	
	if(strcasecmp($contentType, 'application/json') == 0){
		$data = json_decode(file_get_contents('php://input'));
		$hash=hash('sha512', $data->key.'|'.$data->txnid.'|'.$data->amount.'|'.$data->pinfo.'|'.$data->fname.'|'.$data->email.'|||||'.$data->udf5.'||||||'.$data->salt);
		$json=array();
		$json['success'] = $hash;
    	echo json_encode($json);
	
	}
	exit(0);
}
 
function getCallbackUrl()
{
    return site_url("payment/payUResult");
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PayUmoney</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<!-- this meta viewport is required for BOLT //-->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" >
<?php if($options["payu_enviroment"] == "sandbox"){?>
<!-- BOLT Sandbox/test //-->
<script id="bolt" src="https://sboxcheckout-static.citruspay.com/bolt/run/bolt.min.js" bolt-
color="e34524" bolt-logo="http://boltiswatching.com/wp-content/uploads/2015/09/Bolt-Logo-e14421724859591.png"></script>
<?php }else { ?>
<!-- BOLT Production/Live //-->
<script id="bolt" src="https://checkout-static.citruspay.com/bolt/run/bolt.min.js" bolt-color="e34524" bolt-logo="http://boltiswatching.com/wp-content/uploads/2015/09/Bolt-Logo-e14421724859591.png"></script>
<?php } ?>
</head>
<style type="text/css">
	.main {
		margin-left:30px;
		font-family:Verdana, Geneva, sans-serif, serif;
	}
	.text {
		float:left;
		width:180px;
	}
	.dv {
		margin-bottom:5px;
	}
</style>
<body>
<div class="main">
	<form action="#" id="payment_form">
    <div>
    	<h3>Please wait we are processing....</h3>
    </div>
    <div class="dv">
        <span><input type="hidden" id="hash" name="hash" placeholder="Hash" value="" /></span>
    </div>
    <div><input type="submit" value="Pay" onclick="return false;" style="display:none;" /></div>
	</form>
</div>
<script type="text/javascript">
function generateHash(){
    $.ajax({
          url: '<?php echo site_url("payment/payumoney/".$trans_id); ?>',
          type: 'post',
          data: JSON.stringify({ 
            key: '<?php echo $options["payu_merchant_key"]; ?>',
			salt: '<?php echo $options["payu_salt"]; ?>',
			txnid: '<?php echo $trans_id; ?>',
			amount: <?php echo $order->net_amount; ?>,
		    pinfo: 'Food Order',
            fname: '<?php echo $user->user_firstname; ?>',
			email: '<?php echo $user->user_email; ?>',
			mobile: '<?php echo $user->user_phone; ?>',
			udf5: 'BOLT_KIT_PHP7'
          }),
		  contentType: "application/json",
          dataType: 'json',
          success: function(json) {
            if (json['error']) {
			 $('#alertinfo').html('<i class="fa fa-info-circle"></i>'+json['error']);
            }
			else if (json['success']) {	
				$('#hash').val(json['success']);
                launchBOLT();
            }
          }
        }); 
}
generateHash();
</script>
<script type="text/javascript">
function launchBOLT()
{
	bolt.launch({
	key: '<?php echo $options["payu_merchant_key"]; ?>',
	txnid: '<?php echo $trans_id; ?>', 
	hash: $('#hash').val(),
	amount: <?php echo $order->net_amount; ?>,
	firstname: '<?php echo $user->user_firstname; ?>',
	email: '<?php echo $user->user_email; ?>',
	phone: '<?php echo $user->user_phone; ?>',
	productinfo: 'Food Order',
	udf5: 'BOLT_KIT_PHP7',
	surl : '<?php echo getCallbackUrl(); ?>',
	furl: '<?php echo getCallbackUrl();?>',
	mode: 'dropout'	
},{ responseHandler: function(BOLT){
	console.log( BOLT.response.txnStatus );		
	if(BOLT.response.txnStatus != 'CANCEL')
	{
		//Salt is passd here for demo purpose only. For practical use keep salt at server side only.
		var fr = '<form action=\"<?php echo getCallbackUrl(); ?>\" method=\"post\">' +
		'<input type=\"hidden\" name=\"key\" value=\"'+BOLT.response.key+'\" />' +
		'<input type=\"hidden\" name=\"salt\" value=\"<?php echo $options["payu_salt"]; ?>\" />' +
		'<input type=\"hidden\" name=\"txnid\" value=\"'+BOLT.response.txnid+'\" />' +
		'<input type=\"hidden\" name=\"amount\" value=\"'+BOLT.response.amount+'\" />' +
		'<input type=\"hidden\" name=\"productinfo\" value=\"'+BOLT.response.productinfo+'\" />' +
		'<input type=\"hidden\" name=\"firstname\" value=\"'+BOLT.response.firstname+'\" />' +
		'<input type=\"hidden\" name=\"email\" value=\"'+BOLT.response.email+'\" />' +
		'<input type=\"hidden\" name=\"udf5\" value=\"'+BOLT.response.udf5+'\" />' +
		'<input type=\"hidden\" name=\"mihpayid\" value=\"'+BOLT.response.mihpayid+'\" />' +
		'<input type=\"hidden\" name=\"status\" value=\"'+BOLT.response.status+'\" />' +
		'<input type=\"hidden\" name=\"hash\" value=\"'+BOLT.response.hash+'\" />' +
		'</form>';
		var form = jQuery(fr);
		jQuery('body').append(form);								
		form.submit();
        $("#payment_form").hide();
	}
},
	catchException: function(BOLT){
 		alert( BOLT.message );
	}
});
}


</script>	

</body>
</html>