<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><?php echo _l("Email Settings"); ?></h1>
      </div>
      
    </div>
  </div>
</div>
        
<section class="content">
<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">

            <div class="card card-primary">
                <div class="card-header border-transparent">
                    <h3 class="card-title"><?php echo _l("Email settings for email services for sent emails"); ?></h3>
                   
                </div>

                <div class="card-body">
                <?php		
        	echo _get_flash_message();
          echo form_open();
          echo _input_field("email_sender", _l("Send From:")."<span class='text-danger'>*</span>", _get_post_back($field,'email_sender'), 'email', array("data-validation" =>"email","maxlength"=>255),array(),"col-md-4");
			
          
                ?>
                <div class="col-md-12">
				<label><?php echo _l("Enable"); ?></label>
				<div class="btn-group">
				<label class="btn">
					<input type="radio" name="mail_via" id="mail_via" value="smtp" onclick="active_email_method()" <?php echo (_get_post_back($field,'mail_via')=='smtp')?"checked":""; ?>> <?php echo _l("SMTP"); ?>
				</label>
				<label class="btn">
					<input type="radio" name="mail_via" id="mail_via" value="sendgrid" onclick="active_email_method()" <?php echo (_get_post_back($field,'mail_via')=='sendgrid')?"checked":""; ?>> <?php echo _l("Sendgrid"); ?> 
				</label>
				</div>
			</div>
                <?php
                

            echo "<div class='row'>";  
            echo '<div id="smtp_div" class="col-md-12">';
            echo "<blockquote>";
            echo _l("You may use any SMTP details for sending email.");
            echo "</blockquote>";
            echo '<div class="clearfix"></div>';
			echo _input_field("smtp_protocol", _l("Protocol")."<span class='text-danger'>*</span>", _get_post_back($field,'smtp_protocol'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo _input_field("smtp_host", _l("Host")."<span class='text-danger'>*</span>", _get_post_back($field,'smtp_host'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo _input_field("smtp_user", _l("User")."<span class='text-danger'>*</span>", _get_post_back($field,'smtp_user'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			    
			echo '<div class="clearfix"></div>';
			echo _input_field("smtp_pass", _l("Password")."<span class='text-danger'>*</span>", _get_post_back($field,'smtp_pass'), 'password', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo _input_field("smtp_port", _l("Port")."<span class='text-danger'>*</span>", _get_post_back($field,'smtp_port'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			
			echo _input_field("smtp_crypto", _l("Crypto(tls/ssl)")."<span class='text-danger'>*</span>", _get_post_back($field,'smtp_crypto'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo '<div class="clearfix"></div>';
            echo "</div>";

            echo '<div id="sendgrid_div">';
			echo "<blockquote>";
            echo _l("Send emails through send grid, You may get instruction how to create from https://sendgrid.com/docs/ui/account-and-settings/api-keys/#creating-an-api-key");
            echo "</blockquote>";
			
            echo '<div class="clearfix"></div>';
			
			echo _input_field("sendgrid_sender", _l("Sendgrid Sender Id")."<span class='text-danger'>*</span>", _get_post_back($field,'sendgrid_sender'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-6");
			echo _input_field("sendgrid_api_key", _l("Sendgrid Api Key")."<span class='text-danger'>*</span>", _get_post_back($field,'sendgrid_api_key'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-6");
			echo '</div>';

			echo '<div class="col-md-12">
				<button type="submit" class="btn btn-primary btn-flat">'._l("Save").'</button>&nbsp;';
			echo '</div></div>';
            
        	echo form_close();
        	?>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
