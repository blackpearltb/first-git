function update_client(key_match,id_client)
{
	console.log(key_match);
	console.log(id_client);
	$.ajax({
		url: "/streak_new/public/report/ketoan/update-client",		
		type: 'POST',
		dataType: 'JSON',
		data: {'key_match': key_match,'id_client':id_client},
		beforeSend : function() {																
		},
		success: function(result, status){	
								
			
		},
		complete: function(){																		
		}
	});
}  

function clear_client(key_match)
{
	
	$.ajax({
		url: "/streak_new/public/report/ketoan/clear-client",		
		type: 'POST',
		dataType: 'JSON',
		data: {'key_match': key_match},
		beforeSend : function() {																
		},
		success: function(result, status){	
								
			
		},
		complete: function(){																		
		}
	});
}


function update_supplier(key_match,id_supplier)
{
		
	$.ajax({
		url: "/streak_new/public/report/ketoan/update-supplier",		
		type: 'POST',
		dataType: 'JSON',
		data: {'key_match': key_match,'id_supplier':id_supplier},
		beforeSend : function() {																
		},
		success: function(result, status){	
								
			
		},
		complete: function(){																		
		}
	});
}  
 function clear_supplier(key_match)
{

	$.ajax({
		url: "/streak_new/public/report/ketoan/clear-supplier",		
		type: 'POST',
		dataType: 'JSON',
		data: {'key_match': key_match},
		beforeSend : function() {																
		},
		success: function(result, status){	
								
			
		},
		complete: function(){																		
		}
	});
}
