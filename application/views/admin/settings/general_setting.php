<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><?php echo _l("General Settings"); ?></h1>
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
                    <h3 class="card-title"><?php echo _l("General Settings"); ?></h3>
                   
                </div>

                <div class="card-body">
                <?php		
        	echo _get_flash_message();
        	echo form_open();
    	
      echo "<div class='row'>";  
    	echo _input_field("name", _l("Name")."<span class='text-danger'>*</span>", _get_post_back($field,'name'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo _input_field("copyright", _l("Copy Right")."<span class='text-danger'>*</span>", _get_post_back($field,'copyright'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
      echo _input_field("website", _l("Website")."<span class='text-danger'>*</span>", _get_post_back($field,'website'), 'url', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
		  echo _input_field("currency", _l("Currency")."<span class='text-danger'>*</span>", _get_post_back($field,'currency'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo _input_field("currency_symbol", _l("Currency Symbol")."<span class='text-danger'>*</span>", _get_post_back($field,'currency_symbol'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo _input_field("mobile_prefix", _l("Mobile Country Code")."<span class='text-danger'>*</span>", _get_post_back($field,'mobile_prefix'), 'text', array("data-validation" =>"required","maxlength"=>5),array(),"col-md-4");
			echo _input_field("gateway_charges", _l("Gateway Charges")."<span class='text-danger'>*</span>", _get_post_back($field,'gateway_charges'), 'number', array("data-validation" =>"required","step"=>"0.01","maxlength"=>255),array(),"col-md-4");
			echo _input_field("delivery_charge", _l("Delivery Charges")."<span class='text-danger'>*</span>", _get_post_back($field,'delivery_charge'), 'number', array("data-validation" =>"required","step"=>"0.01","maxlength"=>255),array(),"col-md-4");
      echo _select("default_timezone",$time_zones,_l("Time Zone"),array("key"),_get_post_back($field,'default_timezone'),array(),array("form_group_class"=>"col-md-4"));
      echo _select("date_default_timezone",$date_time_zone,_l("Date Time Zone"),array("value"),_get_post_back($field,'date_default_timezone'),array(),array("form_group_class"=>"col-md-4"));
      
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