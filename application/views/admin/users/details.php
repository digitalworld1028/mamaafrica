<section class="content pt-15">

<div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
            
              <h3 class="card-title"><?php echo _l("App User"); ?> / <?php echo _l("Details"); ?></h3>
              <div class="card-tools">
                <a href="<?php echo site_url($controller."/appusers");?>" class="btn bg-gradient-info btn-sm"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
            </div>
            
             <div class="card-body">
             
            <table id="userDetail" class="table table-bordered table-striped">
                <tr>
					<th width="200px"><?php echo _l("Full Name"); ?></th><td><?php echo $data->user_firstname." ".$data->user_lastname; ?></td>
                </tr>
                <tr>
					<th><?php echo _l("Email ID"); ?></th><td><?php echo $data->user_email; ?></td>
                </tr>
                <tr>
                    <th><?php echo _l("Phone No"); ?></th><td><?php echo $data->user_phone; ?></td>
                </tr>
                 <tr>
                    <th><?php echo _l("Verified"); ?></th><td><?php echo ($data->is_mobile_verified == 1)? _l("Yes") : _l("No"); ?></td>
                </tr>                
                  
            </table>
                 
                   <h3 class="pt-15"><?php echo _l("Address"); ?></h3>
            <?php if(isset($addresses)){
                foreach($addresses as $address){
                ?>
            
            <table class="table table-bordered table-striped mb-15">
                
                <tr>
                    <th width="200px"><?php echo _l("Postal Code"); ?></th><td><?php echo $address->postal_code; ?></td>
                </tr>
                <tr>
                    <th><?php echo _l("Address"); ?></th><td><?php echo $address->address_line1." \n ".$address->address_line2; ?></td>
                </tr>
                
                <tr>
                    <th><?php echo _l("City"); ?></th><td><?php echo $address->city; ?></td>
                </tr>
                <tr>
                    <th><?php echo _l("Latitude"); ?></th><td><?php echo $address->latitude; ?></td>
                </tr>
                <tr>
                    <th><?php echo _l("Longitude"); ?></th><td><?php echo $address->longitude; ?></td>
                </tr>
            </table>
            <?php }
            } ?>
                 
            </div>
            
        </div>
    </div>
</div>
   
</section>
