<table class="table table-bordered">
    <tr>
        <td width="60%">
            <h5 class="text-bold"><?php echo $setting["billing_name"]; ?></h5>
            <p>
                <?php echo $setting["billing_address"]; ?><br />
                <?php echo _l("Phone:")." ".$setting["billing_contact"]." "._l("Email:")." ".$setting["billing_email"]; ?><br />
                <?php echo $setting["tax_id"]; ?>
            </p>
        </td>
        <td width="40%">
            <h5>
                <span class="text-bold"><?php echo _l("Order Date :"); ?></span>
                <?php echo date(DEFAULT_DATE_FORMATE,strtotime($order->order_date)); ?><br />

                <span class="text-bold"><?php echo _l("Order No.:"); ?></span>
                <?php echo $order->order_no; ?>
            </h5>
            <h5>
                <span class="text-bold"><?php echo _l("To:"); ?></span><br />

                <?php echo $order->user_firstname." ".$order->user_lastname; ?><br />
                <?php echo $order->address_line1; ?><br />
                <?php echo _l("Phone:")." ".$order->user_phone; ?><br />
                <?php if($order->order_type == "pickup"){
													echo '<span class="text-bold">'._l("Pickup Branch").":</span> ".$order->branch_name_en;
												} ?>
            </h5>
        </td>
    </tr>

</table>
<table class="table ">
    <thead>
        <tr>
            <th style="text-align: left;"><?php echo _l("Item"); ?></th>
            <th style="text-align: left;"><?php echo _l("Qty"); ?></th>
            <th style="text-align: left;"><?php echo _l("Price / Qty"); ?></th>
            <th style="text-align: left;"><?php echo _l("Total"); ?></th>
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
            <td><?php echo $setting["currency_symbol"]." ".sprintf( "%.2f",$item->price); ?></td>
            <td><?php echo $setting["currency_symbol"]." ".$item->price * $item->order_qty; ?></td>
        </tr>

        <?php 
									
									if(!empty($item->option_items)){ 
                                        foreach($item->option_items as $option_item){ 
                                            	?>
        <tr class="optionitem">
            <td><?php echo $option_item->option_name_en; ?>
            </td>
            <td><?php echo $option_item->order_qty; ?></td>
            <td><?php echo $setting["currency_symbol"]." ".sprintf( "%.2f",$option_item->price); ?>
            </td>
            <td><?php echo $setting["currency_symbol"]." ".$option_item->price * $option_item->order_qty; ?>
            </td>
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
            <th><?php echo $setting["currency_symbol"]." ".$order->discount_amount; ?></th>
        </tr>
        <?php
										}
									?>
        <?php
										if($order->delivery_amount > 0){
									?>
        <tr>
            <th colspan="3" class="text-right"><?php echo _l("Delivery Charges"); ?></th>
            <th><?php echo $setting["currency_symbol"]." ".$order->delivery_amount; ?></th>
        </tr>
        <?php
										}
									?>
        <?php
										if($order->gateway_charges > 0){
									?>
        <tr>
            <th colspan="3" class="text-right"><?php echo _l("Gateway Charges"); ?></th>
            <th><?php echo $setting["currency_symbol"]." ".$order->gateway_charges; ?></th>
        </tr>
        <?php
										}
									?>
        <tr>
            <th colspan="3" class="text-right"><?php echo _l("Total Amount"); ?></th>

            <th><?php echo $setting["currency_symbol"]." ".$order->net_amount; ?></th>
        </tr>
    </tfoot>
</table>
<small class="">
    <?php echo $setting["billing_note"]; ?>
</small>