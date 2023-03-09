<section class="content pt-15">

    <div class="card">
        <div class="card-body">
        <?php            
           
            echo "<form id='filter_product' method='post' action=''>";
            echo "<div class='row'>";
            echo _select("category_id",
                     $categories,_l("Category")."<span class='text-danger'>*</span>",
                     array("category_id","cat_name_en"),
                     _get_post_back($field,'category_id'),
                     array("data-validation"=>"required"),
                     array("form_group_class"=>"col-md-3","include_blank"=>_l("Select Category")));
          
            echo '<div class="col-md-3"><button type="submit" style="margin-top:30px;" class="btn btn-primary btn-flat">'._l("Filter").'</button></div>';
             echo "</div>";
            echo "</form>";
           
        ?>
        </div>
    </div>
    
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?php echo _l("Product"); ?> / <?php echo _l("List"); ?></h3>
              
              <div class="card-tools">
               <a href="<?php echo site_url($controller."/add");?>" class="btn bg-gradient-info btn-sm"><i class="fa fa-plus-circle"></i> <?php echo _l("Add"); ?></a>
              </div>
            
            </div>
            
             <div class="card-body">
			<div class="col-md-12" id="messages"><?php echo _get_flash_message(); ?></div>
            <table id="example1" class="table table-bordered table-hover datatable">
                <thead>
                <tr>
                    <th><?php echo _l("Image"); ?></th>
					<th><?php echo _l("Product Name"); ?></th>
                    <th><?php echo _l("Category Name"); ?></th>
                    <th><?php echo _l("Calories"); ?></th>                   	
                    <th><?php echo _l("Price"); ?></th>
                    <th><?php echo _l("Veg/Non Veg"); ?></th>   
                    <th><?php echo _l("Promotional"); ?></th>                 
                    <th><?php echo _l("Status"); ?></th>
					<th width='60'><?php echo _l("Action"); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
				$count = 0;
                foreach($data as $dt){
					$count++;	
                    ?>
                  <tr id="row_<?php echo $count; ?>">
                        <td><?php if ($dt->product_image!="" && file_exists(PRODUCT_IMAGE_PATH."/crop/small/".$dt->product_image)){ ?>
						<img class="profileImage" src="<?php if(isset($dt->product_image) && $dt->product_image != ""){ echo base_url(PRODUCT_IMAGE_PATH."/crop/small/".$dt->product_image); } ?>" alt="<?php echo _l("Preview"); ?>" height="50"/>
						<?php } ?>
						</td>
                        <td><?php echo $dt->product_name_en; ?></td>
                        <td><?php echo $dt->cat_name_en; ?></td>
                        <td><?php echo $dt->calories; ?></td>                        
                        <td><?php echo MY_Controller::$site_settings["currency_symbol"]." ".$dt->price; ?></td>
                        <td><?php echo ($dt->is_veg) ? _l("Veg") : _l("Non Veg"); ?></td>
                        <td><?php echo ($dt->is_promotional == 1)? "<span class='badge badge-success'>"._l("Yes")."</span>" : "<span class='badge badge-danger'>"._l("No")."</span>"; ?></td>
                        <td><?php echo ($dt->status == 1)? "<span class='badge badge-success'>"._l("Enable")."</span>" : "<span class='badge badge-danger'>"._l("Disable")."</span>"; ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="javascript:quickOption('<?php echo _encrypt_val($dt->$primary_key); ?>','<?php echo $count; ?>')" class="btn btn-primary btn-xs"><i class="fa fa-list"></i></a>
                               <a href="<?php echo site_url($controller."/edit/"._encrypt_val($dt->$primary_key)); ?>" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
							   <a href="javascript:deleteRecord('<?php echo site_url($controller."/delete/"._encrypt_val($dt->$primary_key)); ?>',<?php echo $count; ?>)" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></a>
							 </div>
                        </td>
                    </tr>
                    <?php
                } ?>

                </tbody>
            </table>
        </div>
    </div>   
</section>


<div class="modal fade" id="quickOptionModal" tabindex="-2" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo _l("Product Option") ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body quick-option">
      </div>
    </div>
  </div>
</div>