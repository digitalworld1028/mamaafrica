<section class="content pt-15">
  <div class="container-fluid">
  
    <div class="row">
        <?php foreach($order_with_status as $order){
            
            $url = "";
            $box_bg = "";
            $box_icon = "";
            $box_title = "";
            $box_desc = "";

            switch($order->status){
                case ORDER_PENDING:
                    $url = site_url("admin/orders/pending");
                    $box_bg = "bg-info";
                    $box_icon = "ion ion-ios-cart";
                    $box_title = _l("Pending Orders");
                    $box_desc = $order->order_total;
                break;
                case ORDER_CONFIRMED:
                    $url = site_url("admin/orders/confirmed");
                    $box_bg = "bg-warning";
                    $box_icon = "ion ion-bag";
                    $box_title = _l("Confirmed Orders");
                    $box_desc = $order->order_total;
                break;
                case ORDER_OUT_OF_DELIVEY:
                    $url = site_url("admin/orders/outdelivery");
                    $box_bg = "bg-orange";
                    $box_icon = "ion ion-android-bus";
                    $box_title = _l("Out of Delivery Orders");
                    $box_desc = $order->order_total;
                break;
                case ORDER_DELIVERED:
                    $url = site_url("admin/orders/delivered");
                    $box_bg = "bg-success";
                    $box_icon = "ion ion-android-send";
                    $box_title = _l("Delivered Orders");
                    $box_desc = $order->order_total;
                break;
            }
            if($box_bg != ""){
            ?>
            <div class="col-lg-3 col-6">
            <div class="small-box <?php echo $box_bg; ?>">
           
            <div class="inner">
                <h3><?php echo $box_desc; ?></h3>

                <p><?php echo $box_title; ?></p>
              </div>
              <div class="icon">
                <i class="<?php echo $box_icon; ?>"></i>
              </div>              
                    <a href="<?php echo $url; ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>      
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            </div>
        <?php
            }
        } ?>
        
    </div>
    
    </div>
</section>