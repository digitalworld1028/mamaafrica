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
              <h3 class="card-title"><?php echo _l("Category"); ?> / <?php echo $updBtn; ?></h3>
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
			
            echo _input_field("cat_name_en", _l("Title")._l("(En)")."<span class='text-danger'>*</span>", _get_post_back($field,'cat_name_en'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");
            echo _input_field("cat_name_ar", _l("Title")._l("(Ar)")."<span class='text-danger'>*</span>", _get_post_back($field,'cat_name_ar'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");
           	echo "<div class='col-md-4 col-xs-4'><label></label>";
            echo _checkbox("status",_l("Status"),"",array(),(isset($field) && isset($field->status) && $field->status == 1) ? true : false,"pt-15");
			echo "</div>";
			echo "<div class='clearfix'></div>";
			?>	
				<div class='col-md-2'>
					<div class='image-droper'>
						<label><?php echo _l("Image"); ?></label>
						<div class="profile-container">
							
						   <img class="profileImage" src="<?php if(isset($field->cat_image)&& $field->cat_image != ""){ echo base_url(CATEGORY_IMAGE_PATH."/crop/small/".$field->cat_image); }else{ echo base_url("themes/backend/img/choose-image.png"); } ?>" alt="<?php echo _l("Image"); ?>" />
						   <input class="imageUpload" type="file" name="cat_image" placeholder="Photo" capture> 
						</div>	
					</div>
				</div>
                <div class='col-md-2'>
					<div class='image-droper'>
						<label><?php echo _l("Banner"); ?></label>
						<div class="profile-container">
							
						   <img class="profileImage" src="<?php if(isset($field->cat_banner)&& $field->cat_banner != ""){ echo base_url(CATEGORY_IMAGE_PATH."/crop/small/".$field->cat_banner); }else{ echo base_url("themes/backend/img/choose-image.png"); } ?>" alt="<?php echo _l("Banner"); ?>" />
						   <input class="imageUpload" type="file" name="cat_banner" placeholder="Photo" capture> 
						</div>	
					</div>
				</div>
			<?php
            
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