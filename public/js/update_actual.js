function update_actual_sold_value_vnd_child(id_join,value,date_filter,id_join_parent)
{
	
	id_join_parent2 = id_join_parent.split("_");
	id_join_parent2 = id_join_parent2[1];
	
	var total = 0;
	$.ajax({
		url: "/streak_new/public/report/ketoan/update-actual-sold-value-vnd-child",		
		type: 'POST',
		dataType: 'JSON',
		data: {'id_join': id_join,'value':value,'date_filter':date_filter},
		beforeSend : function() {																
		},
		success: function(result, status){	
								
			
		},
		complete: function(){
			
			
			$('.'+id_join_parent).each(function(){
				var temp = $(this).html();
				if(temp == null || temp == "NaN" || isNaN(temp))
				{					
					temp = "0";
					
				}
				temp = temp.replace(/\,/g,'');						
				total = parseInt(total) + parseInt(temp);						
			});			

			total = total.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
			$('#'+id_join_parent).html(total);
			
					
			update_actual_sold_value_vnd(id_join_parent2,total,date_filter);	
								
		}
	});
}  
function update_actual_sold_value_vnd(id_join_parent2,total,date_filter)
{
	$.ajax({
		url: "/streak_new/public/report/ketoan/update-actual-sold-value-vnd",		
		type: 'POST',
		dataType: 'JSON',
		data: {'id_join_parent': id_join_parent2,'value':total,'date_filter':date_filter},
		beforeSend : function() {																
		},
		success: function(result, status){	
			
		},
		complete: function(){									
		}
	});
} 
  
  
function update_actual_cost_child(id_join,value,date_filter,id_join_parent)
{
	var id_join_vnd = "actual-cost-child_"+id_join+"_vnd";
	id_join_parent2 = id_join_parent.split("_");
	id_join_parent2 = id_join_parent2[1];
	
	var total = 0;
	var total_vnd = 0;
	$.ajax({
		url: "/streak_new/public/report/ketoan/update-actual-cost-child",		
		type: 'POST',
		dataType: 'JSON',
		data: {'id_join': id_join,'value':value,'date_filter':date_filter},
		beforeSend : function() {																
		},
		success: function(result, status){	
												
			
		},
		complete: function(){
			var value_vnd = parseInt(value*22500).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
			$('#'+id_join_vnd).html(value_vnd);
			
			$('.'+id_join_parent).each(function(){
				var temp = $(this).html();
				
				if(temp == null || temp == "NaN" || isNaN(temp))
				{					
					temp = "0";
					
				}
				
				temp = temp.replace(/\,/g,'');
						
				total = parseFloat(total) + parseFloat(temp);
						
			});			
			total_vnd = parseInt(total * 22500).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
			total = total.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
			$('#'+id_join_parent).html(total);
						
			$('#'+id_join_parent+"_vnd").html(total_vnd);
			
			update_actual_cost(id_join_parent2,total,date_filter);						
		}
	});
}

function update_actual_cost(id_join_parent2,total,date_filter)
{
	$.ajax({
		url: "/streak_new/public/report/ketoan/update-actual-cost",		
		type: 'POST',
		dataType: 'JSON',
		data: {'id_join_parent': id_join_parent2,'value':total,'date_filter':date_filter},
		beforeSend : function() {																
		},
		success: function(result, status){	
		
		},
		complete: function(){									
		}
	});
}

function update_entertainment(id_join_parent,value,date_filter)
{
	console.log(value);
	$.ajax({
		url: "/streak_new/public/report/ketoan/update-entertainment",		
		type: 'POST',
		dataType: 'JSON',
		data: {'id_join_parent': id_join_parent,'value':value,'date_filter':date_filter},
		beforeSend : function() {																
		},
		success: function(result, status){	
			console.log(result.data);
		},
		complete: function(){									
		}
	});
	
}
 
function update_lobby(id_join_parent,value,date_filter)
{
	$.ajax({
		url: "/streak_new/public/report/ketoan/update-lobby",		
		type: 'POST',
		dataType: 'JSON',
		data: {'id_join_parent': id_join_parent,'value':value,'date_filter':date_filter},
		beforeSend : function() {																
		},
		success: function(result, status){	
		
		},
		complete: function(){									
		}
	});
} 
 
