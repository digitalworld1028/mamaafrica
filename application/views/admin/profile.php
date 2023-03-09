<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><?php echo _l("Profile"); ?></h1>
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
                    <h3 class="card-title"><?php echo _l("Profile"); ?></h3>
                   
                </div>

                <div class="card-body">
                <?php		
            echo _get_flash_message();
            echo form_open_multipart();
            
            $path = PROFILE_IMAGE_PATH;	
			
            echo "<div class='row'><div class='col-md-10'><div class='row'>";            
			echo _input_field("user_firstname", _l("First Name")."<span class='text-danger'>*</span>", _get_post_back($field,'user_firstname'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-6");
            echo _input_field("user_lastname", _l("Last Name")."<span class='text-danger'>*</span>", _get_post_back($field,'user_lastname'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-6");
			echo "<div class='clearfix'></div>";
            echo _input_field("user_email", _l("Email ID")."<span class='text-danger'>*</span>", _get_post_back($field,'user_email'), 'email', array("data-validation" =>"email","maxlength"=>255),array(),"col-md-4");
            
            echo "</div></div>";
			
			echo "<div class='col-md-2'>";
                    echo "<div class='image-droper'>";
            ?>
            <div class="profile-container">
               <img class="profileImage" src="<?php if(isset($field->user_image)&& $field->user_image != ""){ echo base_url($path."/crop/small/".$field->user_image); }else{ echo base_url("themes/backend/img/choose-image.png"); } ?>" alt="<?php echo _l("Photo"); ?>" />
               <input class="imageUpload" type="file" name="profile_photo" placeholder="Photo" capture> 
            </div>
            
            <?php                          
					echo "</div>";
            echo "</div>";
		
			echo '<div class="col-md-12">
				<button type="submit" class="btn btn-primary btn-flat" name="update">'._l("Update").'</button>&nbsp;';
			echo '</div>';
            echo form_close();
        	?>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
