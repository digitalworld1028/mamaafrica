<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><?php echo _l("App Settings"); ?></h1>
            </div>

        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <?php  echo _get_flash_message(); ?>
        <div class="row">
            <div class="col-md-12">

                <div class="card card-primary">
                    <div class="card-header border-transparent">
                        <h3 class="card-title"><?php echo _l("App Settings"); ?></h3>

                    </div>

                    <div class="card-body">
                        <?php
           
            echo form_open();
        
            echo "<div class='row'>";
            echo _input_field("app_contact", _l("Contact No.")."<span class='text-danger'>*</span>", _get_post_back($field, 'app_contact'), 'text', array("data-validation" =>"number","maxlength"=>255), array(), "col-md-4");
            echo _input_field("app_whatsapp", _l("Whatsapp No")."<span class='text-danger'>*</span>", _get_post_back($field, 'app_whatsapp'), 'text', array("data-validation" =>"number","maxlength"=>255), array(), "col-md-4");
            echo _input_field("app_email", _l("Email ID")."<span class='text-danger'>*</span>", _get_post_back($field, 'app_email'), 'email', array("data-validation" =>"email","maxlength"=>255), array(), "col-md-4");
            
            echo '<div class="col-md-12">
				<button type="submit" class="btn btn-primary btn-flat">'._l("Save").'</button>&nbsp;';
            echo '</div></div>';
            
            echo form_close();
            ?>
                    </div>
                </div>

                <div class="card card-primary">
                    <div class="card-header border-transparent">
                        <h3 class="card-title"><?php echo _l("Mobile App API KEY"); ?></h3>

                    </div>

                    <div class="card-body">
                        <?php
            
            echo form_open();
        
            echo "<div class='row'>";
            echo _input_field("item_id", _l("Item ID")."<span class='text-danger'>*</span>", _get_post_back($field, 'item_id'), 'text', array("data-validation" =>"number","maxlength"=>30), array(), "col-md-12");
            echo _input_field("api_key", _l("Item Purchase Code")."<span class='text-danger'>*</span>", _get_post_back($api, 'key'), 'text', array("data-validation" =>"required","maxlength"=>255), array(), "col-md-12");
            echo "<div class='col-md-12'>Replace <strong>ITEM_PURCHASE_CODE=''</strong> value with Item Purchase Code value in <strong>'keystore.properties'</strong> properties file</div>";
            echo "<div class='col-md-12'><small>Note : this is important else app will not connect with backend</small></div>";
            echo '<div class="col-md-12">
				    <button type="submit" class="btn btn-primary btn-flat">'._l("Save").'</button>&nbsp;';
            echo '</div></div>';
            
            echo form_close();
            ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>