var array_columns_choice = ['streakID','name','sale','channel','actual_sold_value_vnd','lobby','actual_sold_value_vnd_after_lobby','gp_plan','total_commission','status'];
var string_columns_choice = "<th>Streak ID</th><th>Campain Name</th><th>Sale</th><th>Channel</th><th>Actual Sold Value VND</th><th>Lobby</th><th>Actual Sold Value After Lobby</th><th>GP Plan</th><th>Total Commission</th><th>Note</th>";

var streak = {
	'totalcommissionsale': function(){										
	},	
}


$(document).ready(function() {   
	$('.a-filter-date').click(function(){	   
		$('.a-filter-date.active').removeClass('active');					
		$(this).addClass('active');
		filter_users_total_commission();
		filter_user_commission();	  		  	   
	});	 
	
	$('input[type=radio][name=choose-user]').change(function() {
        filter_user_commission();
    });
	
	filter_users_total_commission();
	function filter_users_total_commission()
	{	
		var filter_time = "";
			
		$('.a-filter-date').each(function(){
			if($(this).hasClass('active'))
			{
				filter_time = $(this).attr('id');					
			}								
		});
		
		
				$.ajax({
					url: "/streak_new/public/report/ketoan/total-commission-sales",		
					type: 'POST',
					dataType: 'JSON',
					data: {'filter_time':filter_time},
					beforeSend : function() {																		
																																																						
					},
					success: function(result, status){	
						
						$('table.table-total tr.row-data').remove();
						jQuery.each( result.data, function( key, val ) {
							$("<tr class='row-data'></tr>").appendTo('table.table-total');
							$("<td><a onclick=choose_user('"+key+"')>"+key+"</a></td>").appendTo('table.table-total tr:last');
							$("<td>"+val['total_actual_sold_after_lobby']+"</td>").appendTo('table.table-total tr:last');
							$("<td>"+val['gp_actual_total']+"</td>").appendTo('table.table-total tr:last');
							$("<td>"+val['average_actual_total']+"</td>").appendTo('table.table-total tr:last');
							$("<td>"+val['commission_total']+"</td>").appendTo('table.table-total tr:last');
					
						});
						
						
											
																		
					},				
					complete: function(){
						
						
				
					}
			  });

		

	}
	    

	function filter_user_commission()
	{		
		
			var email = "";  
			var filter_time = "";
			$('.choose-user').each(function(){
				if($(this).is(':checked')) { 				
					email = $(this).attr('id');
				}
			});
			$('.a-filter-date').each(function(){
				if($(this).hasClass('active'))
				{
					filter_time = $(this).attr('id');
					
				}	
				
			});
			
			if(email != "" && filter_time != "")
			{
				$.ajax({
					url: "/streak_new/public/report/ketoan/total-commission-sale",		
					type: 'POST',
					dataType: 'JSON',
					data: {'email':email,'filter_time':filter_time},
					beforeSend : function() {																		
																			
								$("body").addClass("loading");
								$('.table-show').html('');
								
								$('<table class="defaultTable sar-table"><tr>'+string_columns_choice+'</tr></table>').appendTo('.table-show');	
								jQuery.each(array_columns_choice, function( key, val ) {
									
																				
								});																	
																				
							
					},
					success: function(result, status){	
											
						var data = result.data;	
															
						$("<tr class='row-total'></tr>").appendTo('table.defaultTable');
						for(var i = 0;i < data.length;i++)
						{
																			
							var streakID = data[i]['streakID'];
							$("<tr class='row-data' onclick = 'display_channel("+data[i]['streakID']+","+i+")' id='tr-campaign-"+i+"'></tr>").appendTo('table.defaultTable');
							jQuery.each( array_columns_choice, function( key, val ) {
								if(val != 'streakID')
								{
									
									if(val == 'name')
										{
											
											if(data[i]['alternateLink'] != null)
											{
												$("<td><a href='"+data[i]['alternateLink']+"' target='_blank'>"+data[i][val]+"</a></td>").appendTo('table.defaultTable tr:last');
											}
											else
											{
												$("<td>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
											}
										}
									else if(val == 'status')
									{
										$("<td class='status-edit' date-paid='"+filter_time+"' streakID='"+data[i]['streakID']+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
									}	
									else
									{
										$('<td>'+data[i][val]+'</td>').appendTo('table.defaultTable tr:last');
									}
								}
								else
								{	
									$('<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+data[i][val]+'</td>').appendTo('table.defaultTable tr:last');
									/*
									if(data[i]['child'].length > 0)
								  	{
								  		$('<td><img class="next-down" src="/streak_new/public/img/next.png" />&nbsp;&nbsp;&nbsp;'+data[i][val]+'</td>').appendTo('table.defaultTable tr:last');
								  	}
								  	else
								  	{
									  		
										$('<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+data[i][val]+'</td>').appendTo('table.defaultTable tr:last');
								  	}
								  	*/
								}
							});
							
							/*
							for(var j = 0;j < data[i]['child'].length;j++)
							{
								var id_join_child = data[i]['child'][j]['id_join'];
								
								$("<tr class='row-data tr-channel tr-channel-"+data[i]['streakID']+"'></tr>").appendTo('table.defaultTable');
								jQuery.each( array_columns_choice, function( key, val ) {
									if(val == 'id_join' && val != 'id')
									{
										
									}								
								  	else
								  	{
									  	$('<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+data[i]['child'][j][val]+'</td>').appendTo('table.defaultTable tr:last');
								  	}
								});
							}
							*/
																			
						}
						
						$("body").removeClass("loading");										
					},				
					complete: function(){
						
						
						$('.status-edit').editable({
							mode:'inline',						
																			
						});
						
						
						$('.status-edit').on('save', function(e, params) {
																		
							var streakID = $(this).attr('streakID');						
							var date_paid = $(this).attr('date-paid');						
							update_commission_status(streakID,params.newValue,date_paid);
						});
						
				
					}
			  });
		 }	
			

	}
			

});
function display_channel(a,b)
	{
								
		var display_status = $('.tr-channel-'+a).css('display');
				
		if(display_status == "none")
		{		
	
			$('#tr-campaign-'+b+' td').css("font-weight","bold");
			$('#tr-campaign-'+b+' td img.next-down').attr("src","/streak_new/public/img/down.png");
			$('.tr-channel-'+a).css('display','table-row');
			$('.tr-channel-'+a).css('background','#000');
			$('.tr-channel-'+a).css('color','#fff');		
		}
		else{
						
			$('#tr-campaign-'+b+" td").css("font-weight","normal");
			$('#tr-campaign-'+b+" td img.next-down").attr("src","/streak_new/public/img/next.png");
			$('.tr-channel-'+a).css('display','none');
			$('.tr-channel-'+a).css('background','none');
			$('.tr-channel-'+a).css('color','#000');
						
		}
	}
function update_commission_status(streakID,value,date_paid)
{
	
	
	$.ajax({
		url: "/streak_new/public/report/ketoan/update-commission-status-sale",		
		type: 'POST',
		dataType: 'JSON',
		data: {'streakID': streakID,'value':value,'date_paid':date_paid},
		beforeSend : function() {																
		},
		success: function(result, status){	
			console.log(result.test);
		},
		complete: function(){									
		}
	});
	
}

function choose_user(email)
{
		$(document).ready(function() {			
			$("input[value='"+email+"']").prop("checked",true).trigger("change");			
		});
}



function exportFile(){
	$(document).ready(function() {
		
		
		var data_export = [];
		
		data_export.push(array_columns_choice);
		var remove_row = 0;		
					
		$('table.defaultTable tr').not(":first").not(":nth-child(2)").each(function() {
			var data_rows = [];
			$(this).find("td").each(function() {
				
					var td_data = $(this).html();								
					
					td_data = td_data.replace(/\&nbsp;/g,'');
					td_data = strip(td_data);
					data_rows.push(td_data);
			});			

			data_export.push(data_rows);
		});
		
		
		var jsonString = JSON.stringify(data_export);	
		
	    $.ajax({
				url: "/streak_new/public/report/ketoan/export-excel",		
				type: 'POST',
				dataType: 'JSON',
				data: {'data_export': jsonString,'filename':'commission'},
				beforeSend : function() {
											
				},
				success: function(result, status){												
					alert(result.data);
				},
				error : function(xhr, textStatus, errorThrown) {
					if (xhr.status === 0) {
						alert('Not connect.\n Verify Network.');
					} else if (xhr.status == 404) {
						alert('Requested page not found. [404]');
		            } else if (xhr.status == 500) {
		            	alert('Server Error [500].');
		            } else if (errorThrown === 'parsererror') {
		            	alert('Requested JSON parse failed.');
		            } else if (errorThrown === 'timeout') {
		            	alert('Time out error.');
		            } else if (errorThrown === 'abort') {
		            	alert('Ajax request aborted.');
		            } else {
		            	alert('There was some error. Try again.');
		            }
				},
				complete: function(){					
					window.location = '/streak_new/commission.xls';					
				}
		  });
		  
				
	});
}
function strip(html)
{
   var tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";
}
