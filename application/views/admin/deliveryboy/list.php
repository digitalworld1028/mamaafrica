<!-- Main content -->
<section class="content pt-15">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo _l("Delivery Boy"); ?> / <?php echo _l("List"); ?></h3>

                    <div class="card-tools">
                        <a href="<?php echo site_url($controller."/add");?>" class="btn bg-gradient-info btn-sm"><i
                                class="fa fa-plus-circle"></i> <?php echo _l("Add"); ?></a>
                    </div>

                </div>

                <div class="card-body">
                    <div class="col-md-12"><?php echo _get_flash_message(); ?></div>
                    <table id="example1" class="table table-bordered table-striped datatable">
                        <thead>
                            <tr>

                                <th><?php echo _l("Driver Boy"); ?></th>
                                <th><?php echo _l("Boy Phone"); ?></th>
                                <th><?php echo _l("Vehicle No"); ?></th>
                                <th><?php echo _l("Availibility"); ?></th>
                                <th width='90'><?php echo _l("Action"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
				$count = 0;
                foreach($data as $dt){
					$count++;	
                    ?>
                            <tr id="row_<?php echo $count; ?>">

                                <td><?php echo $dt->boy_name; ?></td>
                                <td><?php echo $dt->boy_phone; ?></td>
                                <td><?php echo $dt->vehicle_no; ?></td>
                                <td><?php echo ($dt->status == 1) ? _l("Available") : _l("Not Available"); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?php echo site_url($controller."/login/"._encrypt_val($dt->$primary_key)); ?>"
                                            class="btn btn-info btn-xs"><i class="fa fa-lock"></i></a>
                                        <a href="<?php echo site_url($controller."/edit/"._encrypt_val($dt->$primary_key)); ?>"
                                            class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                        <?php if(_is_admin()){ ?>
                                        <a href="javascript:deleteRecord('<?php echo site_url($controller."/delete/"._encrypt_val($dt->$primary_key)); ?>',<?php echo $count; ?>)"
                                            class="btn btn-danger btn-xs"><i class="fa fa-times"></i></a>
                                        <?php } ?>
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
    <!-- /.box -->
</section>