<section class="content pt-15">

<div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?php echo _l("Contact"); ?> / <?php echo _l("List"); ?></h3>
              
            </div>
            
             <div class="card-body">
			<div class="col-md-12" id="messages"><?php echo _get_flash_message(); ?></div>
            <table id="example1" class="table table-bordered table-hover datatable">
                <thead>
                <tr>
				    <th><?php echo _l("Full Name"); ?></th>
                    <th><?php echo _l("Phone"); ?></th>
                    <th><?php echo _l("Message"); ?></th>
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
						
                        <td><?php echo $dt->fullname; ?></td>
                        <td><?php echo $dt->phone; ?></td>
                        <td><?php echo $dt->message; ?></td>
                        <td>
                            <div class="btn-group">
                                
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
