<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><?php echo _l("Payment Settings"); ?></h1>
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
                    <h3 class="card-title"><?php echo _l("Payment gateway for accept payment from"); ?></h3>
                   
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
					<input type="radio" name="pay_via" id="pay_via" value="paypal" onclick="active_payment_method()" <?php echo (_get_post_back($field,'pay_via')=='paypal')?"checked":""; ?>> <?php echo _l("Paypal"); ?>
				</label>
				<label class="btn">
					<input type="radio" name="pay_via" id="pay_via" value="payumoney" onclick="active_payment_method()" <?php echo (_get_post_back($field,'pay_via')=='payumoney')?"checked":""; ?>> <?php echo _l("PayU Money"); ?> 
				</label>
                </div>
                
                <div class="btn-group float-md-right">
				<label class="btn">
					<input type="checkbox" name="enable_cod" id="enable_cod"  <?php echo (_get_post_back($field,'enable_cod')=='on')?"checked":""; ?>> <?php echo _l("Cash On Delivery"); ?>
				</label>
				<label class="btn">
					<input type="checkbox" name="enable_payonline" id="enable_payonline"   <?php echo (_get_post_back($field,'enable_payonline')=='on')?"checked":""; ?>> <?php echo _l("Pay Online"); ?> 
				</label>
                </div>
			</div>
			<?php
			echo '<div class="clearfix"></div>';
			echo '<div id="paypal_div">';
			echo "<blockquote>";
            echo _l("You may use following payment gateway to accept payments.");
            echo "</blockquote>";
            echo '<div class="clearfix"></div>';
            ?>
            <div class="col-md-12">
				<label><?php echo _l("Enviroment"); ?></label>
				<div class="btn-group">
				<label class="btn">
					<input type="radio" name="paypal_enviroment" id="paypal_enviroment" value="sandbox"  <?php echo (_get_post_back($field,'paypal_enviroment')=='sandbox')?"checked":""; ?>> <?php echo _l("Sandbox"); ?>
				</label>
				<label class="btn">
					<input type="radio" name="paypal_enviroment" id="paypal_enviroment" value="production"  <?php echo (_get_post_back($field,'paypal_enviroment')=='production')?"checked":""; ?>> <?php echo _l("Production"); ?> 
				</label>
				</div>
			</div>
            <?php
			echo _input_field("paypal_client_id", _l("Client ID")."<span class='text-danger'>*</span>", _get_post_back($field,'paypal_client_id'), 'text', array("data-validation" =>"required"),array(),"col-md-12");
			echo _input_field("paypal_client_secret", _l("Client Secret")."<span class='text-danger'>*</span>", _get_post_back($field,'paypal_client_secret'), 'text', array("data-validation" =>"required"),array(),"col-md-12");
            
            echo '<div class="clearfix"></div>';
			echo '</div>';
			
			echo '<div id="payu_div">';
			echo "<blockquote>";
            echo _l("Payumoney Payment gateway to receive money");
            echo "</blockquote>";
            ?>
            <div class="col-md-12">
				<label><?php echo _l("Enviroment"); ?></label>
				<div class="btn-group">
				<label class="btn">
					<input type="radio" name="payu_enviroment" id="payu_enviroment" value="sandbox"  <?php echo (_get_post_back($field,'payu_enviroment')=='sandbox')?"checked":""; ?>> <?php echo _l("Sandbox"); ?>
				</label>
				<label class="btn">
					<input type="radio" name="payu_enviroment" id="payu_enviroment" value="production"  <?php echo (_get_post_back($field,'payu_enviroment')=='production')?"checked":""; ?>> <?php echo _l("Production"); ?> 
				</label>
				</div>
			</div>
            <?php
            echo '<div class="clearfix"></div>';
			
			echo _input_field("payu_merchant_key", _l("Merchant Key")."<span class='text-danger'>*</span>", _get_post_back($field,'payu_merchant_key'), 'text', array("data-validation" =>"required"),array(),"col-md-6");
			echo _input_field("payu_salt", _l("Salt")."<span class='text-danger'>*</span>", _get_post_back($field,'payu_salt'), 'text', array("data-validation" =>"required"),array(),"col-md-6");
			echo '</div>';
			//https://sendgrid.com/
			echo '<div class="col-md-12">
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