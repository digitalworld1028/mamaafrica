<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><?php echo _l("Keys"); ?></h1>
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
                    <h3 class="card-title"><?php echo _l("Different keys used in website settings"); ?></h3>
                   
                </div>

                <div class="card-body">
                <?php		
        	echo _get_flash_message();
        	echo form_open();
    	
            echo "<div class='row'>";  
    	   	echo _input_field("google_api_key", _l("Google Map Key")."<span class='text-danger'>*</span>", _get_post_back($field,'google_api_key'), 'text', array("data-validation" =>"required"),array(),"col-md-12");
            
            echo '<div class="clearfix"></div>';
            echo "<h3>"._l("One Signal Settings:")."</h3>";
            echo _input_field("one_signal_id", _l("One Signal ID")."<span class='text-danger'>*</span>", _get_post_back($field,'one_signal_id'), 'text', array("data-validation" =>"required"),array(),"col-md-12");
			echo _input_field("one_signal_key", _l("One Signal Key")."<span class='text-danger'>*</span>", _get_post_back($field,'one_signal_key'), 'text', array("data-validation" =>"required"),array(),"col-md-12");
			
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
