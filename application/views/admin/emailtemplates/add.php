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
              <h3 class="card-title"><?php echo _l("Email Template"); ?> / <?php echo $updBtn; ?></h3>
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
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden");                
            }
            //echo _select("id",$templates,_l("Template")."<span class='text-danger'>*</span>",array(),$field->$primary_key,array("data-validation"=>"required"),array("form_group_class"=>"col-md-4"));
   
            echo _input_field("lang","",(!empty($lang)) ? $lang : "english","hidden");     
            echo _input_field("email_subject", _l("Subject")."<span class='text-danger'>*</span>", _get_post_back($field,'email_subject'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");
			
            echo _textarea('email_message',_l("Message"),_get_post_back($field,'email_message'),array(),array(),"col-md-12");
            echo '<p class="help-block col-md-12">'._l("You may use html tags for design template, please not. if unsupported tags will be removed by editor").'</p>';
			echo "<div class='clearfix'></div>";
			?>
            <div class="col-md-12">
            <?php
                $tags = explode(",",$field->email_tags);
                foreach($tags as $tag){
            ?>
                <a href="#" class="btn btn-default" onclick="insertAtCaret('email_message', '##<?php echo trim($tag); ?>##');return false;">##<?php echo trim($tag) ?>##</a>
            <?php
                }
            ?>
            <br />
            <small><?php echo _l("Note : this tags you may insert in your template design this will take values from server while send email") ?></small>
                                        </div>
            <?php
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