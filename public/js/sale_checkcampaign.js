function import_columns()
{
	 $(document).ready(function() {
	 	$('table.defaultTable tr.row-data').remove();  				
		var input_filter = {};
		$('.input-filter').each(function(){
			if($(this).val() != "")
			{
				input_filter[$(this).attr('id')] = $(this).val();
									
			}
		});
		input_filter = JSON.stringify(input_filter);
			
		 $.ajax({
				url: "/streak_new/public/report/sale/checkcampaign",		
				type: 'POST',
				dataType: 'JSON',
				data: {'input_filter':input_filter},
				beforeSend : function() {
												

				},
				success: function(result, status){							
					
					var data = result.data;
					for(var i = 0;i < data.length;i++)
					{
																									  		
						$("<tr class='row-data'></tr>").appendTo('table.defaultTable');
						$('<td>'+data[i]['streakID']+'</td>').appendTo('table.defaultTable tr:last');						
						$('<td>'+data[i]['name']+'</td>').appendTo('table.defaultTable tr:last');
						$('<td>'+data[i]['sale']+'</td>').appendTo('table.defaultTable tr:last');
						$('<td>'+data[i]['media']+'</td>').appendTo('table.defaultTable tr:last');												
					}
					
					
				},				
				complete: function(){
																														
				}
		  });
		  
	});
				 	
 }
 
 
 


