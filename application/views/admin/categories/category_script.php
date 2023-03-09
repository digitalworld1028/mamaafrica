<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.6/css/rowReorder.dataTables.min.css">

<script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.2.6/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript">
$(function () {
    $(document).ready(function() {
        var table = $('#example1').datatable( {
            rowReorder: {
                selector: 'img'
            }
        } );
    
        table.on( 'row-reorder', function ( e, diff, edit ) {

            var update_data = [];
            $('#example1 > tbody  > tr').each(function(index, tr) { 
                console.log(index+" - "+tr.getAttribute("data-ref"));
                var data = { s_index : index , ref : tr.getAttribute("data-ref") };
                update_data.push(data);
            });
            console.log(update_data);

            $.ajax({
                url: '<?php echo site_url("admin/categories/updateOrder"); ?>',
                type: 'post',
                
                success: function (data) {
                    console.log(data);
                },
                data: { data : JSON.stringify(update_data) }
            });

        } );
    } );
});
</script>