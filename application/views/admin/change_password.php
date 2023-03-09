<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><?php echo _l("Change Password"); ?></h1>
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
                    <h3 class="card-title"><?php echo _l("Change Password"); ?></h3>
                   
                </div>

                <div class="card-body">
                <?php		
    	echo _get_flash_message();
    	echo form_open();
    	
    	echo _input_field("old_password", _l("Old Password")."<span class='text-danger'>*</span>", _get_post_back($field,'old_password'), 'password', array("data-validation" =>"length required","data-validation-length"=>"min6"),array(),"col-md-12");
    	
    	echo _input_field("new_password", _l("New Password")."<span class='text-danger'>*</span>", _get_post_back($field,'new_password'), 'password', array("data-validation" =>"length required","data-validation-length"=>"min6"),array(),"col-md-12");
    	echo _input_field("confirm_password", _l("Confirm Password")."<span class='text-danger'>*</span>", _get_post_back($field,'confirm_password'), 'password', array("data-validation" =>"length required","data-validation-length"=>"min6"),array(),"col-md-12");
    	echo '<div class="col-md-12">
		<button type="submit" class="btn btn-primary btn-flat" name="change_password">'._l("Change Password").'</button>&nbsp;';
			echo '</div>';
        	echo form_close();
        	?>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
    
