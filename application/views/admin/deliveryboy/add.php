<section class="content pt-15">

<div class="row">
        <div class="col-md-12">
    <!-- Default box -->
	<?php echo form_open_multipart(); ?>
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
            <h3 class="card-title"><?php echo _l("Delivery Boy"); ?> / <?php echo $updBtn; ?></h3>

            <div class="card-tools">
				<a href="<?php echo site_url($controller);?>" class="btn bg-gradient-info btn-sm"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
        </div>
        <div class="card-body">
            <?php
            echo "<div class='row'>";
            echo _get_flash_message();
            
            if(!empty($field) && !empty($field->$primary_key)){
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
            }else{
                echo _input_field("boy_phone", _l("Boy Phone")."<span class='text-danger'>*</span>", _get_post_back($field,'boy_phone'), 'text', array("data-validation" =>"phone","maxlength"=>200),array(),"col-md-4");
            
                echo _input_field("boy_password", _l("Login Password")."<span class='text-danger'>*</span>", _get_post_back($field,'boy_password'), 'password', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			    
            }

            echo _input_field("boy_name", _l("Boy Name")."<span class='text-danger'>*</span>", _get_post_back($field,'boy_name'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");
            echo _input_field("boy_email", _l("Email ID"), _get_post_back($field,'boy_email'), 'email', array("maxlength"=>255),array(),"col-md-4");
            echo _input_field("vehicle_no", _l("Vehical No.")."<span class='text-danger'>*</span>", _get_post_back($field,'vehicle_no'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
            
            echo _input_field("boy_licence", _l("Driving Licence")."<span class='text-danger'>*</span>", _get_post_back($field,'boy_licence'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
            echo _input_field("boy_id_proof", _l("ID Proof")."<span class='text-danger'>*</span>", _get_post_back($field,'boy_id_proof'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
            
			echo "<div class='col-md-12'></div>";
			?>
                <div class='col-md-2'>
					<div class='image-droper'>
						<label><?php echo _l("Photo"); ?></label>
						<div class="profile-container">
							
						   <img class="profileImage" src="<?php if(isset($field->boy_photo)&& $field->boy_photo != ""){ echo base_url(PROFILE_IMAGE_PATH."/crop/small/".$field->boy_photo); }else{ echo base_url("themes/backend/img/choose-image.png"); } ?>" alt="<?php echo _l("Image"); ?>" />
						   <input class="imageUpload" type="file" name="boy_photo" placeholder="Photo" capture> 
						</div>	
					</div>
				</div>
                <div class='col-md-2'>
					<div class='image-droper'>
						<label><?php echo _l("Licence Photo"); ?></label>
						<div class="profile-container">
							
						   <img class="profileImage" src="<?php if(isset($field->licence_photo)&& $field->licence_photo != ""){ echo base_url(PROFILE_IMAGE_PATH."/crop/small/".$field->licence_photo); }else{ echo base_url("themes/backend/img/choose-image.png"); } ?>" alt="<?php echo _l("Image"); ?>" />
						   <input class="imageUpload" type="file" name="licence_photo" placeholder="Photo" capture> 
						</div>	
					</div>
				</div>
                <div class='col-md-2'>
					<div class='image-droper'>
						<label><?php echo _l("ID Proof Photo"); ?></label>
						<div class="profile-container">
							
						   <img class="profileImage" src="<?php if(isset($field->id_photo)&& $field->id_photo != ""){ echo base_url(PROFILE_IMAGE_PATH."/crop/small/".$field->id_photo); }else{ echo base_url("themes/backend/img/choose-image.png"); } ?>" alt="<?php echo _l("Image"); ?>" />
						   <input class="imageUpload" type="file" name="id_photo" placeholder="Photo" capture> 
						</div>	
					</div>
				</div>
            <?php
			echo '<div class="col-md-12">
				<br>
				<button type="submit" class="btn btn-primary btn-flat">'.$updBtn.'</button>&nbsp;';
			echo "<a class='btn btn-danger btn-flat' href='".site_url($controller)."'>"._l("Cancel")."</a>";
			echo '</div>';
            echo '</div>';
            ?>
        </div>
    </div>
	<?php echo form_close(); ?>
	<!-- /.box -->
		</div>
</div>
</section>