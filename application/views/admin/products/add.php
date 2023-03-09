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
              <h3 class="card-title"><?php echo _l("Product"); ?> / <?php echo $updBtn; ?></h3>
              <div class="card-tools">
                <a href="<?php echo site_url($controller);?>" class="btn bg-gradient-info btn-sm"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
            </div>
            
             <div class="card-body">
         
                  <?php          
            echo _get_flash_message();
            echo form_open_multipart();
          
            echo "<div class='row'>";
            
             $is_edit = false;
             if(!empty($field) && !empty($field->$primary_key)){
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
               $is_edit = true;
            }       
			
            echo _select("category_id",$categories,_l("Category")."<span class='text-danger'>*</span>",array("category_id","cat_name_en"),_get_post_back($field,'category_id'),array("data-validation" =>"required"),array("form_group_class"=>"col-md-4","include_blank"=>_l("Select Category")));
            echo _input_field("product_name_en", _l("Product Name")._l("(En)")."<span class='text-danger'>*</span>", _get_post_back($field,'product_name_en'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
            echo _input_field("product_name_ar", _l("Product Name")._l("(Ar)")."<span class='text-danger'>*</span>", _get_post_back($field,'product_name_ar'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
            echo "<div class='clearfix'></div>";
          
            echo _input_field("calories", _l("Calories"), _get_post_back($field,'calories'), 'text', array("maxlength"=>100),array(),"col-md-4");
            echo _input_field("price", _l("Price")."<span class='text-danger'>*</span>", _get_post_back($field,'price'), 'number', array("data-validation" =>"required","step"=>"0.01","maxlength"=>20,"minvalue"=>"0"),array(),"col-md-4");
            echo _input_field("price_note", _l("Price Note"), _get_post_back($field,'price_note'), 'text', array("maxlength"=>255),array(),"col-md-4");
                     
            echo "<div class='col-md-4 col-xs-4'>";
            echo _checkbox("is_veg",_l("Is Veg"),"",array(),(isset($field) && isset($field->is_veg) && $field->is_veg == 1) ? true : false);
            echo "</div>";

            echo "<div class='col-md-4 col-xs-4'>";
            echo _checkbox("is_promotional",_l("Is Promotional"),"",array(),(isset($field) && isset($field->is_promotional) && $field->is_promotional == 1) ? true : false);
            echo "</div>";
            
            echo "<div class='col-md-4 col-xs-4'>";
            echo _checkbox("status",_l("Status"),"",array(),(isset($field) && isset($field->status) && $field->status == 1) ? true : false);
            echo "</div>";
            ?>
             <div class="col-md-2">
                  <div class='image-droper'>
                    <label><?php echo _l("Image"); ?></label>
                    <div class="profile-container">
                      
                      <img class="profileImage" src="<?php if(isset($field->product_image)&& $field->product_image != ""){ echo base_url(PRODUCT_IMAGE_PATH."/crop/small/".$field->product_image); }else{ echo base_url("themes/backend/img/choose-image.png"); } ?>" alt="<?php echo _l("Image"); ?>" />
                      <input class="imageUpload" type="file" name="product_image" placeholder="Photo" capture> 
                    </div>	
                  </div>
              </div>
                <div class="col-md-10">
                
                   <?php             
              if($is_edit){
                ?>
                 <label><?php echo _l("Product Options"); ?></label>
                <table id="example1" class="table table-bordered table-striped ">
                <thead>
                  <tr>
                      <th><?php echo _l("Option Name"); ?></th>
                      <th><?php echo _l("Description"); ?></th>
                      <th><?php echo _l("Price"); ?></th>
                      <th><?php echo _l("Allow Multiple"); ?></th>
                      <th width='130'><?php echo _l("Action"); ?>
                        <a href="javascript:;" class="btn btn-primary btn-xs float-right" data-toggle="modal" data-target="#addOptionModal"><i class='fa fa-plus'></i> <?php echo _l("Add"); ?></a>
                      </th>
                  </tr>
                </thead>
                <tbody id="options_list">
                    <?php
            $count = 0;
                    foreach($productoptions as $dt){
              $count++;	
                $this->load->view("admin/products/row_options",array("dt"=>$dt,"count"=>$count));
               } ?>
                </tbody>
                </table>
                   <?php
              }else{
               echo "<div class='row'>";
               echo _input_field("option_name_en", _l("Option Name")._l("(En)"), _get_post_back($field,'option_name_en'), 'text', array("maxlength"=>200),array(),"col-md-4");
               echo _input_field("option_name_ar", _l("Option Name")._l("(Ar)"), _get_post_back($field,'option_name_ar'), 'text', array("maxlength"=>200),array(),"col-md-4");
               echo _input_field("option_price", _l("Option Price"), _get_post_back($field,'option_price'), 'number', array("step"=>"0.01","maxlength"=>20,"minvalue"=>"0"),array(),"col-md-4");
                            
               echo _input_field("option_desc_en", _l("Description")._l("(En)"), _get_post_back($field,'option_desc_en'), 'text', array("maxlength"=>1000),array(),"col-md-6");
               echo _input_field("option_desc_ar", _l("Description")._l("(Ar)"), _get_post_back($field,'option_desc_ar'), 'text', array("maxlength"=>1000),array(),"col-md-6");
                              
               echo "<div class='col-md-4'>";
               echo _checkbox("multiple",_l("Allow Multiple"),"",array(),(isset($field) && isset($field->multiple) && $field->multiple == 1) ? true : false);
               echo "</div>";  
               echo "</div>";      
               }                       
            ?>
            </div>
               <div class="col-md-12">
              <ul class="nav nav-tabs" id="tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="descriptionen" data-toggle="pill" href="#tabdescriptionen" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true"><?php echo _l("Description")._l("(En)"); ?></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="descriptionar" data-toggle="pill" href="#tabdescriptionar" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false"><?php echo _l("Description")._l("(Ar)"); ?></a>
                  </li>                
                </ul>
             
              <div class="tab-content" id="tabContent">
                  <div class="tab-pane fade show active" id="tabdescriptionen" role="tabpanel" aria-labelledby="tabdescriptionen">
                       <?php
                echo _textarea('product_desc_en',"",_get_post_back($field,'product_desc_en'),array(),array(),"col-md-12");
                ?> 
                  </div>
                  <div class="tab-pane fade" id="tabdescriptionar" role="tabpanel" aria-labelledby="tabdescriptionar">
                       <?php echo _textarea('product_desc_ar',"",_get_post_back($field,'product_desc_ar'),array(),array(),"col-md-12");
			    ?> 
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

<!-- Modal -->
<div class="modal fade" id="addOptionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
              <?php echo "<form id='add_product_options' action='".site_url("admin/products/set_options")."' enctype='multipart/form-data'>";
               ?>
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo _l("Product Option") ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
              <?php
            echo "<div class='row'>"; 
           echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden");
              
           echo _input_field("option_name_en", _l("Option Name")._l("(En)")."<span class='text-danger'>*</span>", _get_post_back($field,'option_name_en'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");
           echo _input_field("option_name_ar", _l("Option Name")._l("(Ar)")."<span class='text-danger'>*</span>", _get_post_back($field,'option_name_ar'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");
           echo _input_field("option_price", _l("Price")."<span class='text-danger'>*</span>", _get_post_back($field,'option_price'), 'number', array("data-validation" =>"required","step"=>"0.01","maxlength"=>20,"minvalue"=>"0"),array(),"col-md-4");
          
           echo _input_field("option_desc_en", _l("Description")._l("(En)"), _get_post_back($field,'option_desc_en'), 'text', array("maxlength"=>1000),array(),"col-md-6");
           echo _input_field("option_desc_ar", _l("Description")._l("(Ar)"), _get_post_back($field,'option_desc_ar'), 'text', array("maxlength"=>1000),array(),"col-md-6");
           
           echo "<div class='col-md-4 col-xs-4'>";
           echo _checkbox("multiple",_l("Allow Multiple"),"",array(),(isset($field) && isset($field->multiple) && $field->multiple == 1) ? true : false);
           echo "</div></div>";   
           
           ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal"><?php echo _l("Close"); ?></button>
        <?php echo '<button type="submit" class="btn btn-primary btn-flat">'._l("Add").'</button>';
                 ?>
      </div>
      <?php echo "</form>"; ?>
    </div>
  </div>
</div>
