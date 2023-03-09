
<script>
    $(function(){
                
        $("#add_product_options").submit(function(e) {

            e.preventDefault(); 

            var form = $(this);
            var url =  form.attr('action');
            var frmData = form.serializeArray();
            var count = $("#options_list tr").length + 1;
            frmData.push( {name: 'count' , value: count });
            
            $.ajax({
                   type: "POST",
                   url: url,
                   data: frmData, 
                   success: function(data)
                   {
                       $("#options_list").prepend(data);
                       $(".option_name_en").val('');
                       $(".option_name_ar").val('');
                       $(".option_price").val('');
                       $(".option_desc_en").val('');
                       $(".option_desc_ar").val('');
                       updateCellOption($("#r_index").val());
                   }
                 });


        });
    });
    function deleteTableRecord(url,table,record)
	  {	 
        	$.ajax({
				url:url,
				type:"get",
			}).done(function( data ) {
					$("#options_list .row_"+record).remove();
                    updateCellOption($("#r_index").val());
			});      
	  }
      
      $.validate({
        modules : 'date, logic'
    });
</script>