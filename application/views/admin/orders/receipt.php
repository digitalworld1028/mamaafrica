<section class="content pt-15">

<div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">            
              <h3 class="card-title"><?php echo _l("Order") ; ?> / <?php echo _l("Detail"); ?></h3>
              <div class="card-tools">
              
              		<?php if($order->order_type == "delivery"){ ?>
								<a href="javascript:open_map('<?php echo $order->latitude ?>','<?php echo $order->longitude ?>','<?php echo $order->user_firstname." ".$order->user_lastname; ?>')" class="btn btn-default btn-sm"><i class="fa fa-map"></i> <?php echo _l("Map"); ?></a> 
								<a href="javascript:myNavFunc('<?php echo $order->latitude ?>','<?php echo $order->longitude ?>')" class="btn btn-default btn-sm"><i class="fa fa-link"></i> <?php echo _l("Map Link"); ?></a>   
                                 
                                <?php } ?>    
                                
				<button class="btn btn-default btn-sm" type="button" onclick="window.print()"><?php echo _l("Print"); ?></button>
                <a href="<?php echo site_url($controller);?>" class="btn bg-gradient-info btn-sm"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>         
            </div>
            </div>
            
             <div class="card-body">
         
        <h3 class="text-bold text-center"><?php echo _l("Receipt"); ?></h3>
							<table class="table table-bordered">
								<tr>
									<td width="60%">
										<h6 class="text-bold"><?php echo $setting["billing_name"]; ?></h6>
                                        <p>
                                        <?php echo $setting["billing_address"]; ?><br/>
                                        <?php echo _l("Phone:")." ".$setting["billing_contact"]." "._l("Email:")." ".$setting["billing_email"]; ?><br/>
                                        <?php echo $setting["tax_id"]; ?>
                                        </p>
									</td>
									<td width="40%">
										<h6>
											<span class="text-bold"><?php echo _l("Order Date :"); ?></span> 
											<?php echo date(DEFAULT_DATE_FORMATE,strtotime($order->order_date)); ?><br/>
										
											<span class="text-bold"><?php echo _l("Order No.:"); ?></span> 
											<?php echo $order->order_no; ?>
										</h6>
                                        <h6>
												<span class="text-bold"><?php echo _l("To:"); ?></span><br/>
											
												<?php echo $order->user_firstname." ".$order->user_lastname; ?><br/>
                                                <?php echo $order->address_line1; ?><br/>
												<?php echo _l("Phone:")." ".$order->user_phone; ?><br/>
												
												<?php if($order->order_type == "pickup"){
													echo '<span class="text-bold">'._l("Pickup Branch").":</span> ".$order->branch_name_en;
												} ?>
										</h6>
									</td>
								</tr>	
									
							</table>	
							<table class="table ">
								<thead>
									<tr>
										<th><?php echo _l("Item"); ?></th>
										<th><?php echo _l("Qty"); ?></th>
										<th><?php echo _l("Price / Qty"); ?></th>
                                        <th><?php echo _l("Total"); ?></th>
									</tr>
								</thead>
								<tbody>
								<?php 
									
									if(!empty($items)){ 
                                        foreach($items as $item){ 									
								?>                                
									<tr>
										<td><?php echo $item->product_name_en; ?>										
										</td>
										<td><?php echo $item->order_qty; ?></td>
										<td><?php echo MY_Controller::$site_settings["currency_symbol"]." ".sprintf( "%.2f",$item->price); ?></td>
                                        <td><?php echo MY_Controller::$site_settings["currency_symbol"]." ".$item->price * $item->order_qty; ?></td>
									</tr>
                                    
                                    	<?php 
									
									if(!empty($item->option_items)){ 
                                        foreach($item->option_items as $option_item){ 
                                            	?>
										<tr class="optionitem">
										<td><?php echo $option_item->option_name_en; ?>										
										</td>
										<td><?php echo $option_item->order_qty; ?></td>
										<td><?php echo MY_Controller::$site_settings["currency_symbol"]." ".sprintf( "%.2f",$option_item->price); ?></td>
                                        <td><?php echo MY_Controller::$site_settings["currency_symbol"]." ".$option_item->price * $option_item->order_qty; ?></td>
									</tr>
                                    <?php }} ?>	
                                                                       
								<?php }} ?>	
								</tbody>
								<tfoot>
									<?php
										if($order->discount_amount > 0){
									?>
									<tr>
										<th colspan="3" class="text-right"><?php echo _l("(-)Discount"); ?></th>
										<th><?php echo MY_Controller::$site_settings["currency_symbol"]." ".$order->discount_amount; ?></th>
									</tr>
									<?php
										}
									?>
									<?php
										if($order->delivery_amount > 0){
									?>
									<tr>
										<th colspan="3" class="text-right"><?php echo _l("Delivery Charges"); ?></th>
										<th><?php echo MY_Controller::$site_settings["currency_symbol"]." ".$order->delivery_amount; ?></th>
									</tr>
									<?php
										}
									?>
									<?php
										if($order->gateway_charges > 0){
									?>
									<tr>
										<th colspan="3" class="text-right"><?php echo _l("Gateway Charges"); ?></th>
										<th><?php echo MY_Controller::$site_settings["currency_symbol"]." ".$order->gateway_charges; ?></th>
									</tr>
									<?php
										}
									?>
									<tr>
										<th colspan="3" class="text-right"><?php echo _l("Total Amount"); ?></th>
										
										<th><?php echo MY_Controller::$site_settings["currency_symbol"]." ".$order->net_amount; ?></th>
									</tr>
								</tfoot>
							</table>
							<small class="">
								<?php echo $setting["billing_note"]; ?>
							</small>
							<div class="row">
								<div class="col-md-9 col-sm-9 col-xs-9 col-xl-9"></div>		
								<div class="col-md-3 col-sm-3 col-xs-3 col-xl-3">
									<br>
									<hr>
									<h6 class="text-bold"><?php echo _l("Receiver's Signature"); ?></h6>
									<br>
									<hr>
									<h6 class="text-bold"><?php echo _l("Date & Time"); ?></h6>
								</div>		
							</div>
         
             </div>
             
          </div>
    </div>
</div>
   
</section>
