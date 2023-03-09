<section class="content pt-15">

<div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?php echo _l("App User"); ?> / <?php echo _l("List"); ?></h3>
            </div>
            
             <div class="card-body">
			<div class="col-md-12" id="messages"><?php echo _get_flash_message(); ?></div>
            <table id="example1" class="table table-bordered table-hover datatable">
                <thead>
                <tr>
				   <th><?php echo _l("Full Name"); ?></th>
					<th><?php echo _l("Email ID"); ?></th>
                    <th><?php echo _l("Phone No"); ?></th>
                    <th><?php echo _l("Verified"); ?></th>                   
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
						<td><?php echo $dt->user_firstname." ".$dt->user_lastname; ?></td>
                        <td><?php echo $dt->user_email; ?></td>
                        <td><?php echo $dt->user_phone; ?></td>
                        <td><?php echo ($dt->is_mobile_verified == 1)? _l("Yes") : _l("No"); ?></td>                      
                        <td>
                            <div class="btn-group">
                                <a href="<?php echo site_url($controller."/details/"._encrypt_val($dt->$primary_key)); ?>" class="btn btn-success btn-xs"><?php echo _l("Details") ?></a>
								<a href="javascript:deleteRecord('<?php echo site_url($controller."/delete/"._encrypt_val($dt->$primary_key)); ?>',<?php echo $count; ?>)" class="btn btn-danger btn-xs"><i class="fas fa-times"></i></a>
							
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
