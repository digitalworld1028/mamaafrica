<section class="content pt-15">

<div class="row">
        <div class="col-12">
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
              <h3 class="card-title"><?php echo _l("App Page"); ?> / <?php echo $updBtn; ?></h3>
              <div class="card-tools">
                <a href="<?php echo site_url($controller);?>" class="btn bg-gradient-info btn-sm"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
            </div>
            
             <div class="card-body">
              <ul class="nav nav-pills ml-auto">
                    <li class="nav-item">
                     <a type="button" href="?lang=english" class="nav-link <?php if($lang == "english"){ echo "active";  } ?>">English</a>
                     </li>
                    <li class="nav-item">                      
                      <a type="button" href="?lang=arabic" class="nav-link <?php if($lang == "arabic"){ echo "active";  } ?>">Arabic</a>
                    </li>
                  </ul>
           
                    <?php
            echo _get_flash_message();
            echo form_open_multipart();
         if(!empty($field) && !empty($field->$primary_key)){
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
            }       
			echo _input_field("lang","",(!empty($lang)) ? $lang : "english","hidden");     
            
            echo _input_field("page_title", _l("Title")."<span class='text-danger'>*</span>", _get_post_back($field,'page_title'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");
			
            echo _textarea('page_content',_l("Page Content"),_get_post_back($field,'page_content'),array(),array(),"col-md-12");
            echo '<p class="help-block col-md-12">'._l("You may use html tags for design template, please not. if unsupported tags will be removed by editor").'</p>';
			echo "<div class='clearfix'></div>";
			
			echo '<div class="col-md-12">
				<br>
				<button type="submit" class="btn btn-primary btn-flat">'.$updBtn.'</button>&nbsp;';
			echo "<a class='btn btn-danger btn-flat' href='".site_url($controller)."'>"._l("Cancel")."</a>";
			echo '</div>';
            echo form_close();
            ?>
        </div>
          </div>
    </div>
</div>
   
</section>

<script>
function insertAtCaret(areaId, text) {
        var txtarea = document.getElementById(areaId);
        if (!txtarea) {
            return;
        }
        CKEDITOR.instances[areaId].insertText(text);
}
</script>