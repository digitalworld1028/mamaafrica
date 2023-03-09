<section class="content pt-15">

<div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?php echo _l("Order"); ?> / <?php echo _l("List"); ?></h3>
              
            </div>
            
             <div class="card-body">
			<div class="col-md-12" id="messages"><?php echo _get_flash_message(); ?></div>
            <table id="example1" class="table table-bordered table-hover datatable">
                <thead>
                <tr>
               	    <th><?php echo _l("Order No"); ?></th>
                    <th><?php echo _l("Order Date"); ?></th>
                    <th><?php echo _l("Branch"); ?></th>
                    <th><?php echo _l("Customer"); ?></th>
                    <th><?php echo _l("Customer Phone"); ?></th>
                    <th><?php echo _l("Type"); ?></th>
                    <th><?php echo _l("Area"); ?></th>
                    <th><?php echo _l("Total Amounts"); ?></th>
                    <th><?php echo _l("Status"); ?></th>
					<th width='120'><?php echo _l("Action"); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
				$count = 0;
                foreach($data as $dt){
					$count++;	
                    ?>
                  <tr id="row_<?php echo $count; ?>">
						<td><?php echo $dt->order_no; ?></td>
                        <td><?php echo date(DEFAULT_DATE_TIME_FORMATE,strtotime($dt->order_date)); ?></td>
                        <td><?php echo $dt->branch_name_en; ?></td>
                        <td><?php echo $dt->user_firstname." ".$dt->user_lastname; ?></td>
                        <td><?php echo $dt->user_phone; ?></td>
                        <td><?php echo $dt->order_type; ?></td>
                        <td><?php echo $dt->city; ?></td>
                        <td><?php echo MY_Controller::$site_settings["currency_symbol"]." ".$dt->net_amount; ?></td>
                        <td>
                            <?php $this->load->view("admin/orders/order_status_label",array("dt"=>$dt,"count"=>$count)); ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <?php if($dt->order_type == "delivery"){ ?>
                                <a href="javascript:open_map('<?php echo $dt->latitude ?>','<?php echo $dt->longitude ?>','<?php echo $dt->user_firstname." ".$dt->user_lastname; ?>')" class="btn btn-default btn-xs"><i class="fa fa-map"></i> <?php echo _l("Map"); ?></a> 
                                <a href="javascript:myNavFunc('<?php echo $dt->latitude ?>','<?php echo $dt->longitude ?>')" class="btn btn-default btn-xs"><i class="fa fa-link"></i> <?php echo _l("Map Link"); ?></a>   
                                <?php } ?>    
                            </div>
                            <div class="btn-group">
                                <a href="<?php echo site_url("admin/orders/receipt/"._encrypt_val($dt->order_id)); ?>" class="btn btn-default btn-xs"><i class="fa fa-print"></i> <?php echo _l("Print"); ?></a>                              
                                <a href="javascript:deleteRecord('<?php echo site_url("admin/orders/delete/"._encrypt_val($dt->order_id)); ?>',<?php echo $count; ?>)" class="btn btn-danger btn-xs"><i class="fa fa-times"></i><?php echo _l("Delete"); ?></a>								
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

<div class="modal fade" id="assignDeliveryBoyModal" tabindex="-2" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo _l("Assign Delivery Boy") ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body delivery-boy-content">
      <?php
      
					echo form_open_multipart("admin/orders/assign_deliveryboy",array("id"=>"form_assign_delivery_boy"));
                    echo _input_field("assign_order_id","","","hidden");
                    echo _input_field("row_index","","","hidden"); 
                    echo _select("delivery_boy_id",$deliveryboys,_l("Delivery Boy")."<span class='text-danger'>*</span>",array("delivery_boy_id","boy_name"),"",array("data-validation"=>"required"),array("form_group_class"=>"col-md-6","include_blank"=>_l("Select Delivery Boy")));
					echo '<div class="col-md-2">
							<br />
							<button type="submit" class="btn btn-primary btn-flat">'._l("Assign").'</button>&nbsp;';
					echo '</div>';
					echo form_close();
				?>
                <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>