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
              <h3 class="card-title"><?php echo _l("Coupon"); ?> / <?php echo $updBtn; ?></h3>
              <div class="card-tools">
                <a href="<?php echo site_url($controller);?>" class="btn bg-gradient-info btn-sm"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
            </div>
            
             <div class="card-body">
         
                  <?php          
            echo _get_flash_message();
            echo form_open_multipart();
          
            echo "<div class='row'>";
            
              if(!empty($field) && !empty($field->$primary_key)){
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
            }       
			
            echo _input_field("coupon_code", _l("Coupon Code")."<span class='text-danger'>*</span>", _get_post_back($field,'coupon_code'), 'text', array("data-validation" =>"required","maxlength"=>50),array(),"col-md-4");
			      
   
            
            echo _input_field("discount", _l("Discount")."<span class='text-danger'>*</span>", _get_post_back($field,'discount'), 'number', array("data-validation" =>"required","step"=>"0.1","maxlength"=>20,"minvalue"=>"0"),array(),"col-md-4");
            echo _select("discount_type",$discount_types,_l("Discount Type")."<span class='text-danger'>*</span>",array("value"),_get_post_back($field,'discount_type'),array(),array("form_group_class"=>"col-md-4"));
            echo _input_field("max_discount_amount", _l("Maximum Applicable Amount")."<span class='text-danger'>*</span>", _get_post_back($field,'max_discount_amount','',0), 'number', array("data-validation" =>"required","step"=>"0.1","maxlength"=>255,"minvalue"=>"0"),array(),"col-md-4");
            
            echo _input_field("min_order_amount", _l("Min Order Amount")."<span class='text-danger'>*</span>", _get_post_back($field,'min_order_amount','',0), 'number', array("data-validation" =>"required","step"=>"0.1","maxlength"=>255,"minvalue"=>"0"),array(),"col-md-4");
            echo _input_field("validity", _l("Validity")."<span class='text-danger'>*</span>", _get_post_back($field,'validity'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4","daterangepicker_field");
            
           
            echo "<div class='col-md-4'>";
            echo _checkbox("multi_usage",_l("Allow Multi Usage"),"",array(),(isset($field) && isset($field->multi_usage) && $field->multi_usage == 1) ? true : false);
            echo "</div>";
            
            echo _textarea('description_en',_l("Description")._l("(En)"),_get_post_back($field,'description_en'),array(),array(),"col-md-12");
			echo _textarea('description_ar',_l("Description")._l("(Ar)"),_get_post_back($field,'description_ar'),array(),array(),"col-md-12");
			            
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
