<script>

function quickOption(quickid,r_index){
    
    $.ajax({
        url:'<?php echo site_url("admin/products/quickoption/"); ?>'+quickid,
        data:{ id : quickid, r_index : r_index },
        type:"post",
        success:function(res){
            $("#quickOptionModal").modal("show");
            $('#quickOptionModal').on('shown.bs.modal', function (e) {
                $(".quick-option").html(res);
            });
        }
    });
    
}
function updateCellOption(row){
    row = row -1;
    var option = "";
        $('#options_list > tr').each(function(index, tr) { 
            var td = tr.cells[0];
                if(option != ""){
                    option = option +",";
                }
                option = option + td.innerHTML;
                
            });
            datatable.cell( row, 2 ).data( option );
    }
</script>