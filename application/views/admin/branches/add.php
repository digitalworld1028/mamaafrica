<section class="content pt-15">

<div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
            	<?php
				if(!empty($field) && !empty($field->$primary_key))
				{
					$updBtn=_l("Update");
				}
				else
				{
					$updBtn=_l("Add");
				}
			?>
              <h3 class="card-title"><?php echo _l("Branch"); ?> / <?php echo $updBtn; ?></h3>
              <div class="card-tools">
                <a href="<?php echo site_url($controller);?>" class="btn bg-gradient-info btn-sm"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
            </div>
            
             <div class="card-body">
         
                  <?php          
            echo _get_flash_message();
            echo form_open();
          
            echo "<div class='row'>";
            
              if(!empty($field) && !empty($field->$primary_key)){
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
            }       
			
            echo _input_field("branch_name_en", _l("Name")._l("(En)")."<span class='text-danger'>*</span>", _get_post_back($field,'branch_name_en'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
		    echo _input_field("branch_name_ar", _l("Name")._l("(Ar)")."<span class='text-danger'>*</span>", _get_post_back($field,'branch_name_ar'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo _input_field("phone", _l("Phone")."<span class='text-danger'>*</span>", _get_post_back($field,'phone'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-3");
		  
            echo _input_field("postal_code", _l("Postal Code")."<span class='text-danger'>*</span>", _get_post_back($field,'postal_code'), 'text', array("data-validation" =>"required","maxlength"=>30),array(),"col-md-4");
            echo _input_field("opening_time", _l("Opening Time")."<span class='text-danger'>*</span>", _get_post_back($field,'opening_time'), 'time', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-3");
			echo _input_field("closing_time", _l("Closing Time")."<span class='text-danger'>*</span>", _get_post_back($field,'closing_time'), 'time', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-3");
			            
            echo _input_field("address_en", _l("Address")._l("(En)")."<span class='text-danger'>*</span>", _get_post_back($field,'address_en'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-6");
		    echo _input_field("address_ar", _l("Address")._l("(Ar)")."<span class='text-danger'>*</span>", _get_post_back($field,'address_ar'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-6");
            
            echo _input_field("area_en", _l("Area")._l("(En)")."<span class='text-danger'>*</span>", _get_post_back($field,'area_en'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-3");
            echo _input_field("area_ar", _l("Area")._l("(Ar)")."<span class='text-danger'>*</span>", _get_post_back($field,'area_ar'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-3");
            echo _input_field("latitude", _l("Latitude")."<span class='text-danger'>*</span>", _get_post_back($field,'latitude','',0), 'text', array("data-validation" =>"required","maxlength"=>30),array(),"col-md-3");
			echo _input_field("longitude", _l("Longitude")."<span class='text-danger'>*</span>", _get_post_back($field,'longitude','',0), 'text', array("data-validation" =>"required","maxlength"=>30),array(),"col-md-3");
            
			echo _input_field("delivery_area_in_km", _l("Delivery Area in Km.")."<span class='text-danger'>*</span>", _get_post_back($field,'delivery_area_in_km','',0), 'number', array("data-validation" =>"required","step"=>0.1,"maxlength"=>30),array(),"col-md-3");
			echo "<div class='col-md-9'></div>";
            
			echo "<div class='col-md-4'>";
            echo _checkbox("is_active",_l("Active"),"",array(),(isset($field) && isset($field->is_active) && $field->is_active == 1) ? true : false);
            echo "</div>";
            
			echo '<div class="col-md-12">
				<br>
				<button type="submit" class="btn btn-primary btn-flat">'.$updBtn.'</button>&nbsp;';
			echo "<a class='btn btn-danger btn-flat' href='".site_url($controller)."'>"._l("Cancel")."</a>";
			echo '</div></div>';
            echo form_close();
            ?>
         
             </div>
             
          </div>
    </div>
</div>
   
</section>
