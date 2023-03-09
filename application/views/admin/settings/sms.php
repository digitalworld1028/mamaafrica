<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><?php echo _l("SMS Settings"); ?></h1>
      </div>
      
    </div>
  </div>
</div>
<!-- Main content -->
<section class="content">
<div class="container-fluid">
    <!-- Default box -->
    <div class="row">
        <div class="col-md-12">

            <div class="card card-primary">
                <div class="card-header border-transparent">
                    <h3 class="card-title"><?php echo _l("SMS settings for sms services"); ?></h3>
                   
                </div>

                <div class="card-body">
            <?php
            
            echo _get_flash_message();
            echo form_open_multipart();
			?>
			<div class="col-md-12">
			<label><?php echo _l("Enable"); ?></label>
			<div class="btn-group">
				
				<label class="btn">
					<input type="radio" name="sms_via" id="sms_via" value="twillio" onclick="active_sms_method()" <?php echo (_get_post_back($field,'sms_via')=='twillio')?"checked":""; ?>> <?php echo _l("Twillio"); ?>
				</label>
				<label class="btn">
					<input type="radio" name="sms_via" id="sms_via" value="nexmo" onclick="active_sms_method()" <?php echo (_get_post_back($field,'sms_via')=='nexmo')?"checked":""; ?>> <?php echo _l("Nexmo"); ?>
				</label>
				<label class="btn">
					<input type="radio" name="sms_via" id="sms_via" value="general" onclick="active_sms_method()" <?php echo (_get_post_back($field,'sms_via')=='general')?"checked":""; ?>> <?php echo _l("General"); ?>
				</label>
			</div>
			</div>
			<?php
			echo '<div class="clearfix"></div>';
			echo '<div id="twillio_div">';
			echo "<blockquote>";
            echo _l("Twillio SMS");
            echo "</blockquote>";
            echo '<div class="clearfix"></div>';
			echo _input_field("twilio_sender_id", _l("Twillio Sender Id")."<span class='text-danger'>*</span>", _get_post_back($field,'twilio_sender_id'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo _input_field("twilio_account_sid", _l("Twillio Acc. SID")."<span class='text-danger'>*</span>", _get_post_back($field,'twilio_account_sid'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo _input_field("twilio_auth_token", _l("Twillio Auth Token")."<span class='text-danger'>*</span>", _get_post_back($field,'twilio_auth_token'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			 
			echo '</div>';
			
			echo '<div id="nexmo_div">';
			echo "<blockquote>";
            echo _l("Nexmo SMS");
            echo "</blockquote>";
			
            echo '<div class="clearfix"></div>';
			
			echo _input_field("nexmo_from", _l("From")."<span class='text-danger'>*</span>", _get_post_back($field,'nexmo_from'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo _input_field("nexmo_api_key", _l("Nexmo Api Key")."<span class='text-danger'>*</span>", _get_post_back($field,'nexmo_api_key'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo _input_field("nexmo_secret_key", _l("Nexmo Secret Key")."<span class='text-danger'>*</span>", _get_post_back($field,'nexmo_secret_key'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo '</div>';
			
			echo '<div id="general_div">';
			echo "<blockquote>";
            echo _l("General SMS");
            echo "</blockquote>";
			
            echo '<div class="clearfix"></div>';
			
			echo _input_field("sms_link", _l("Link")."<span class='text-danger'>*</span>", _get_post_back($field,'sms_link'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-6");
			echo "<div class='col-md-12'><span class='text-info'>Note : Use [mobile] for mobile no and [message] for message into link</span><br/>
			<span class='text-info'>For example :  https://www.yoursmsgateway.com?mobile=[mobile]&message=[message]&sender=XYZSENDR</span>
			</div>";
			echo '</div>';
			
			echo '<div class="col-md-12">
				<hr>
				<button type="submit" class="btn btn-primary btn-flat">'._l("Save").'</button>&nbsp;';
			echo '</div>';
            echo form_close();
            ?>
        </div>
	</div>
		</div>
	</div>
	</div>
    <!-- /.box -->
</section>
