<section class="content pt-15">

<div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?php echo _l("Discount"); ?> / <?php echo _l("List"); ?></h3>
              
              <div class="card-tools">
               <a href="<?php echo site_url($controller."/add");?>" class="btn bg-gradient-info btn-sm"><i class="fa fa-plus-circle"></i> <?php echo _l("Add"); ?></a>
              </div>
            
            </div>
            
             <div class="card-body">
			<div class="col-md-12" id="messages"><?php echo _get_flash_message(); ?></div>
            <table id="example1" class="table table-bordered table-hover datatable">
                <thead>
                <tr>
               	    <th><?php echo _l("Product"); ?></th>
                    <th><?php echo _l("Discount"); ?></th>
                    <th><?php echo _l("Discount Type"); ?></th>
					<th><?php echo _l("Validity"); ?></th>
                    <th><?php echo _l("Multi Usage"); ?></th>
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
						<td><?php echo $dt->product_name_en; ?></td>
                        <td><?php   if($dt->discount_type == "flat" || $dt->discount_type == "flatcombo"){
                                        echo $dt->discount;
                                    }else{
                                        echo $dt->discount . "%";
                                    }?>
                        </td>
                        <td><?php echo $dt->discount_type; ?></td>
                        <td><?php echo date(DEFAULT_DATE_FORMATE,strtotime($dt->start_date)) . _l(" TO ") . date(DEFAULT_DATE_FORMATE,strtotime($dt->end_date)); ?></td>
                        <td><?php echo ($dt->status == 1)? _l("Published") : _l("Pending"); ?></td>
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
