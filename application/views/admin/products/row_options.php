 <tr id="row_<?php echo $count; ?>" class="row_<?php echo $count; ?>">
						<td><?php echo $dt->option_name_en; ?></td>
                        <td><?php echo $dt->option_desc_en; ?></td>
                        <td><?php echo MY_Controller::$site_settings["currency_symbol"]." ".$dt->price; ?></td>
                        <td><?php echo ($dt->multiple == 1)? _l("Yes") : _l("No"); ?></td>
                        <td>
                            <div class="btn-group">
                              
                                <a href="javascript:deleteTableRecord('<?php echo site_url($controller."/delete_option/"._encrypt_val($dt->product_option_id)); ?>','#option_list',<?php echo $count; ?>)" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo _l("Are you sure to delete..?"); ?>')"><i class="fa fa-times"></i></a>
								
                            </div>
                        </td>
</tr>