<section class="content pt-15">

<div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?php echo _l("Category"); ?> / <?php echo _l("List"); ?></h3>
              
              <div class="card-tools">
               <a href="<?php echo site_url($controller."/add");?>" class="btn bg-gradient-info btn-sm"><i class="fa fa-plus-circle"></i> <?php echo _l("Add"); ?></a>
              </div>
            
            </div>
            
             <div class="card-body">
			<div class="col-md-12" id="messages"><?php echo _get_flash_message(); ?></div>
            <table id="example1" class="table table-bordered table-hover datatable">
                <thead>
                <tr>
				    <th width='15'><?php echo _l("Ref"); ?></th>
					<th width="110"><?php echo _l("Image"); ?></th>
					<th><?php echo _l("Category"); ?></th>
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
                    <tr id="row_<?php echo $count; ?>" data-ref="<?php echo _encrypt_val($dt->$primary_key); ?>">
						<td><?php echo $count; ?></td>
                        <td><?php if ($dt->cat_image!="" && file_exists(CATEGORY_IMAGE_PATH."/crop/small/".$dt->cat_image)){ ?>
						<img class="profileImage" src="<?php if(isset($dt->cat_image) && $dt->cat_image != ""){ echo base_url(CATEGORY_IMAGE_PATH."/crop/small/".$dt->cat_image); } ?>" alt="<?php echo _l("Preview"); ?>" height="50"/>
						<?php } ?>
						</td>
                        <td><?php echo $dt->cat_name_en; ?></td>
                        <td><?php echo ($dt->status == 1)? "<span class='badge badge-success'>"._l("Enable")."</span>" : "<span class='badge badge-danger'>"._l("Disable")."</span>"; ?></td>
                         <td>
                            <div class="btn-group">
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
    </div>
</div>
   
</section>