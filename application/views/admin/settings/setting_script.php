<script>
	active_sms_method();
function active_sms_method()
{
	if($("#sms_via:checked").val()=='twillio')
	{
		$("#twillio_div").show();
		$("#nexmo_div").hide();
		$("#general_div").hide();
	}
	else if($("#sms_via:checked").val()=='nexmo')
	{
		$("#twillio_div").hide();
		$("#nexmo_div").show();
		$("#general_div").hide();
	}
	else
	{
		$("#twillio_div").hide();
		$("#nexmo_div").hide();
		$("#general_div").show();
	}
}
active_email_method();
function active_email_method()
{
	if($("#mail_via:checked").val()=='smtp')
	{
		$("#smtp_div").show();
		$("#sendgrid_div").hide();
	}
	else
	{
		$("#smtp_div").hide();
		$("#sendgrid_div").show();
	}
}
active_payment_method();
function active_payment_method()
{
	if($("#pay_via:checked").val()=='paypal')
	{
		$("#paypal_div").show();
		$("#payu_div").hide();
	}
	else
	{
		$("#paypal_div").hide();
		$("#payu_div").show();
	}
}
$(function(){
       $("#default_timezone").change(function(){
            $('#date_default_timezone').html("");
            var time_zone = $(this).val();
            
            $.ajax({
              method: "POST",
              url: '<?php echo site_url("setting/date_time_zone_json"); ?>',
              data: { time_zone: time_zone }
            })
              .done(function( data ) {
                    
                     $.each(data, function(index, element) {
                                $('#date_default_timezone').append("<option value='"+element+"'>"+element+"</option>");
                            });
                            $("#date_default_timezone").trigger("select2:updated");
            }); 
       }); 
    });
</script>