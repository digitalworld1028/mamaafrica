<?php echo "<form id='add_product_options' action='".site_url("admin/products/set_options")."' enctype='multipart/form-data'>";
               
               echo "<div class='row'>";
               echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden");
               echo _input_field("r_index","",$r_index,"hidden");
             
               echo _input_field("option_name_en", _l("Option Name")._l("(En)")."<span class='text-danger'>*</span>", _get_post_back($field,'option_name_en'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");
               echo _input_field("option_name_ar", _l("Option Name")._l("(Ar)")."<span class='text-danger'>*</span>", _get_post_back($field,'option_name_ar'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");
               echo _input_field("option_price", _l("Price")."<span class='text-danger'>*</span>", _get_post_back($field,'option_price'), 'number', array("data-validation" =>"required","step"=>"0.01","maxlength"=>20,"minvalue"=>"0"),array(),"col-md-4");
              
               echo _input_field("option_desc_en", _l("Description")._l("(En)"), _get_post_back($field,'option_desc_en'), 'text', array("maxlength"=>1000),array(),"col-md-6");
               echo _input_field("option_desc_ar", _l("Description")._l("(Ar)"), _get_post_back($field,'option_desc_ar'), 'text', array("maxlength"=>1000),array(),"col-md-6");
               
               echo "<div class='col-md-4 col-xs-4'>";
               echo _checkbox("multiple",_l("Allow Multiple"),"",array(),(isset($field) && isset($field->multiple) && $field->multiple == 1) ? true : false);
               echo "</div>";   
               echo "</div>";                
              ?>
<?php echo '<button type="submit" class="btn btn-primary btn-flat">'._l("Add").'</button>';
                 ?>
<?php echo "</form>"; ?>
<label><?php echo _l("Product Options"); ?></label>
<table id="example1" class="table table-bordered table-striped ">
    <thead>
        <tr>
            <th><?php echo _l("Option Name"); ?></th>
                      <th><?php echo _l("Description"); ?></th>
                      <th><?php echo _l("Price"); ?></th>
                      <th><?php echo _l("Allow Multiple"); ?></th>
                      <th width='100'><?php echo _l("Action"); ?></th>
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