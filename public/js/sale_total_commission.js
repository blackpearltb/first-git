var array_columns_choice = ['streakID','name','sale','channel','actual_sold_value_vnd','lobby','actual_sold_value_vnd_after_lobby','gp_plan','total_commission','status'];
var string_columns_choice = "<th>Streak ID</th><th>Campaign Name</th><th>Sale</th><th>Channel</th><th>Actual Sold Value VND</th><th>Lobby</th><th>Actual Sold Value After Lobby</th><th>GP Plan</th><th>Total Commission</th><th>Note</th>";
var filter_time = "";
var streak = {
	'totalcommissionsale': function(){												
	},	
}


$(document).ready(function() { 
	
	$('.a-filter-date').each(function(){
		if($(this).hasClass('active'))
		{
			filter_time = $(this).attr('id');
					
		}	
				
	});
	
	
	var position = $("input[id='input-position']").val();
	if(position != "admin")
	{  
		filter_user_commission();
	}
	else
	{
		get_info_commission_seniors();
	}
	
	$('.a-filter-date').click(function(){	   
		$('.a-filter-date.active').removeClass('active');					
		$(this).addClass('active');
		filter_time = $(this).attr('id');
		get_info_commission_seniors();
		$('table.table-team-group').css('display','none');
		$('#team_choice').html("");
		$('.div-sale').css('display','none');
		filter_user_commission();	  		  	   
	});	 
	    

	function filter_user_commission()
	{																
			
			if(filter_time != "")
			{
				$.ajax({
					url: "/streak_new/public/report/sale/total-commission-sale",		
					type: 'POST',
					dataType: 'JSON',
					data: {'filter_time':filter_time},
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
								
								$('<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+data[i][val]+'</td>').appendTo('table.defaultTable tr:last');
							});
							
							
																			
						}
						
						$("body").removeClass("loading");										
					},				
					complete: function(){
																		
				
					}
			  });
		 }	
			

	}	
	
	
	function get_info_commission_seniors()
	{
										
			$.ajax({
				url: "/streak_new/public/report/sale/get-info-commission-seniors-sale",		
				type: 'POST',
				dataType: 'JSON',
				data: {'filter_time':filter_time},
				beforeSend : function() {																		
																																																
								
				},
				success: function(result, status){	
					$('table.table-head tr.row-data').remove();
						jQuery.each( result.data, function( key, val ) {
							$("<tr class='row-data'></tr>").appendTo('table.table-head');
							$("<td><a onclick=choose_team('"+key+"','"+val['id_senior']+"')>"+key+"</a></td>").appendTo('table.table-head tr:last');
							$("<td>"+val['total_actual_sold_after_lobby']+"</td>").appendTo('table.table-head tr:last');
							$("<td>"+val['gp_actual_total']+"</td>").appendTo('table.table-head tr:last');
							$("<td>"+val['average_actual_total']+"</td>").appendTo('table.table-head tr:last');
							$("<td>"+val['commission_total']+"</td>").appendTo('table.table-head tr:last');
					
						});
					
																			
				},				
				complete: function(){	
					
				}
			});
			
	}
	
	
	
			
});


function choose_team(email,id_senior)
{
				
		$.ajax({
				url: "/streak_new/public/report/sale/get-info-commission-team-group",		
				type: 'POST',
				dataType: 'JSON',
				data: {'filter_time':filter_time,'id_senior':id_senior},
				beforeSend : function() {																		
				
				},
				success: function(result, status){	
					
					$('#team_choice').html(email);
					$('table.table-team-group').css('display','block');
					$('table.table-team-group tr.row-data').remove();
						jQuery.each( result.data, function( key, val ) {
							$("<tr class='row-data'></tr>").appendTo('table.table-team-group');
							$("<td><a onclick=choose_user('"+key+"')>"+key+"</a></td>").appendTo('table.table-team-group tr:last');
							$("<td>"+val['total_actual_sold_after_lobby']+"</td>").appendTo('table.table-team-group tr:last');
							$("<td>"+val['gp_actual_total']+"</td>").appendTo('table.table-team-group tr:last');
							$("<td>"+val['average_actual_total']+"</td>").appendTo('table.table-team-group tr:last');
							$("<td>"+val['commission_total']+"</td>").appendTo('table.table-team-group tr:last');
					
						});														
				},				
				complete: function(){	
					
				}
			});
	
}

function choose_user(email)
{
	
				$.ajax({
					url: "/streak_new/public/report/sale/total-commission-sale",		
					type: 'POST',
					dataType: 'JSON',
					data: {'filter_time':filter_time,'email':email},
					beforeSend : function() {																		
																			
								
								$('.div-sale').html('');
								
								$('<table class="table-sale"><tr>'+string_columns_choice+'</tr></table>').appendTo('.div-sale');	
								jQuery.each(array_columns_choice, function( key, val ) {
									
																				
								});																	
																				
							
					},
					success: function(result, status){	
						
																	
						var data = result.data;	
						$('.div-sale').css('display','block');
						$("<tr class='row-total'></tr>").appendTo('table.table-sale');
						for(var i = 0;i < data.length;i++)
						{
																			
							var streakID = data[i]['streakID'];
							$("<tr class='row-data' onclick = 'display_channel("+data[i]['streakID']+","+i+")' id='tr-campaign-"+i+"'></tr>").appendTo('table.table-sale');
							jQuery.each( array_columns_choice, function( key, val ) {
								
								$('<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+data[i][val]+'</td>').appendTo('table.table-sale tr:last');
							});
							
							
																			
						}
																						
					},				
					complete: function(){
																		
				
					}
			  });
		 
}


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
