<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><?php echo _l("SMS Settings"); ?></h1>
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
                    <h3 class="card-title"><?php echo _l("SMS settings for sms services"); ?></h3>
                   
                </div>

                <div class="card-body">
                <?php		
        	echo _get_flash_message();
        	echo form_open();
    	
            echo "<div class='row'>";  
    	   	echo _input_field("username", _l("UserName")."<span class='text-danger'>*</span>", _get_post_back($field,'username'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo _input_field("password", _l("Password")."<span class='text-danger'>*</span>", _get_post_back($field,'password'), 'text', array("data-validation" =>"required"),array(),"col-md-4");
            echo _input_field("sender", _l("Sender ID")."<span class='text-danger'>*</span>", _get_post_back($field,'sender'), 'text', array("data-validation" =>"required"),array(),"col-md-4");
            echo _input_field("mobile_prefix", _l("Mobile Prefix")."<span class='text-danger'>*</span>", _get_post_back($field,'mobile_prefix'), 'text', array("data-validation" =>"required","maxlength"=>6),array(),"col-md-4");
			
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
