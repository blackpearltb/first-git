var array_columns_choice = [];
var string_columns_choice = "";

var streak = {
	'month': function(){
		var drag_columns = getCookie('drag_columns');
		
		if(drag_columns != "")
		{
			drag_columns = drag_columns.split(",");
			for(var i = 0;i < drag_columns.length - 1;i++)
			{
				array_columns_choice.push(drag_columns[i]);
				string_columns_choice += "<th>"+drag_columns[i]+"</th>";
				
			}
			
			import_columns(2);
		}
		
						
	},
	'total': function(){

		create_table_total();
						
	},
	
}

/*
function create_table_total()
{
	
	var columns = ["streakID","name","dealsize"];
	//var columns = ["streakID","name","sale","assigned_to","channel","insource_or_outsource","start_date","end_date","total_days","remain_days","actual_sold_value_vnd","actual_cost_usd","actual_cost_vnd","actual_profit","actual_gp","dealsize"];
	
	var string_columns_table_total = "";
	$("<tr></tr>").appendTo('table.table-show');
	
	for(var j = 0;j < columns.length;j++)
	{
		string_columns_table_total += "<th>"+columns[j]+"</th>";		
	}
	
	string_columns_table_total += "<th>vat</th><th>remain</th><th>collected 1</th><th>collected date 1</th><th>collected 2</th><th>collected date 2</th><th>collected 3</th><th>collected date 3</th><th>collected 4</th><th>collected date 4</th>";
	
	$('table.table-show tr:last').append(string_columns_table_total);
	
	$(document).ready(function(){
		$.ajax({
			url: "/streak/public/report/ketoan/total",		
			type: 'POST',
			dataType: 'JSON',
			data: {"columns":columns},
			beforeSend : function() {																
			},
			success: function(result, status){	
				var data = result.data;	
				
				
				for(var i = 0;i < data.length;i++)
					{
						var collect_count = 1;
						var collect_date_count = 1;
						var id_join_parent = data[i]['id_join'];
						var streakID = data[i]['streakID'];						
						$("<tr class='row-data' onclick = 'display_channel("+data[i]['streakID']+","+i+")' id='tr-campaign-"+i+"'></tr>").appendTo('table.table-show');
						jQuery.each( data[i], function( key, val ) {	
												
							if(key != 'child' && key != 'id_join' && key != 'id')
							{
								if(key != 'streakID')
								{		
									
									if(key == "collected_1" || key == "collected_2" || key == "collected_3" || key == "collected_4")
									{
										$("<td streakid='"+streakID+"' class='collected"+collect_count+"-edit' data-type='text'  id='collected"+collect_count+"_"+id_join_parent+"'>"+val+"</td>").appendTo('table.table-show tr:last');
										collect_count++;
									}
									else if(key == "collected_date1" || key == "collected_date2" || key == "collected_date3" || key == "collected_date4")
									{
										$("<td streakid='"+streakID+"' class='collected-date"+collect_date_count+"-edit' data-type='text' id='collected-date"+collect_date_count+"_"+id_join_parent+"'>"+val+"</td>").appendTo('table.table-show tr:last');
										collect_date_count++;
									}
									else if(key == "vat")
									{
										$("<td class='vat-edit' data-type='text'  id='vat_"+id_join_parent+"'>"+val+"</td>").appendTo('table.table-show tr:last');
										
									}
									else if(key == "dealsize")
									{
										$("<td class='dealsize-edit' data-type='text'  id='dealsize_"+id_join_parent+"'>"+val+"</td>").appendTo('table.table-show tr:last');
										
									}
									else if(key == "remain")
									{
										$("<td data-type='text'  id='remain_"+id_join_parent+"'>"+val+"</td>").appendTo('table.table-show tr:last');
										
									}
									else
									{							
										$('<td>'+val+'</td>').appendTo('table.table-show tr:last');
									}
																											
						  		}
						  		else
						  		{
							  		
							  		if(data[i]['child'].length > 0)
							  		{
							  			$('<td><img class="next-down" src="/streak/public/img/next.png" />&nbsp;&nbsp;&nbsp;'+val+'</td>').appendTo('table.table-show tr:last');
							  		}
							  		else
							  		{
								  		$('<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+val+'</td>').appendTo('table.table-show tr:last');
							  		}
							  		
							  		
						  		}
						  	}
						  	
						});
						
						for(var j = 0;j < data[i]['child'].length;j++)
						{
							
							
							$("<tr class='row-data tr-channel tr-channel-"+data[i]['streakID']+"'></tr>").appendTo('table.table-show');
							jQuery.each( data[i]['child'][j], function( key_child, val_child ) {
								
																
								if(key_child == 'id_join' || key_child == 'id')
								{
									
								}
								else if(key_child != 'streakID')
								{
									$('<td>'+val_child+'</td>').appendTo('table.table-show tr:last');
								
							  	}
							  	else
							  	{
								  	$('<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+val_child+'</td>').appendTo('table.table-show tr:last');
							  	}
							});
						}
						
						
					}
														
			},
			complete: function(){
								
					$('.collected1-edit').editable({
						mode:'inline',
						emptytext: '0',
						display: function(value) {			    		    
						  value = value.replace(/\,/g,'');	
						  value = eval(value);				  
						  value = (Math.round(value * 1000)/1000);	
						  value = value.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");		  
					      $(this).text(value);		      
					    }		
					    									
					});					
					$('.collected1-edit').on('save', function(e, params) {
						
											
						params_value_eval = eval(params.newValue);						
						var id_join = $(this).attr('id');						
						id_join = id_join.split("_");
						id_join = id_join[1];
																	
						
						update_collected_new(id_join,params_value_eval,1);						
					});
					
					$('.collected2-edit').editable({
						mode:'inline',
						emptytext: '0',
						display: function(value) {			    		    
						  value = value.replace(/\,/g,'');	
						  value = eval(value);				  
						  value = (Math.round(value * 1000)/1000);	
						  value = value.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");		  
					      $(this).text(value);		      
					    }		
					    									
					});					
					$('.collected2-edit').on('save', function(e, params) {

											
						params_value_eval = eval(params.newValue);						
						var id_join = $(this).attr('id');						
						id_join = id_join.split("_");
						id_join = id_join[1];																							
						update_collected_new(id_join,params_value_eval,2);						
					});
					
					$('.collected3-edit').editable({
						mode:'inline',
						emptytext: '0',
						display: function(value) {			    		    
						  value = value.replace(/\,/g,'');	
						  value = eval(value);				  
						  value = (Math.round(value * 1000)/1000);	
						  value = value.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");		  
					      $(this).text(value);		      
					    }		
					    									
					});					
					$('.collected3-edit').on('save', function(e, params) {

											
						params_value_eval = eval(params.newValue);						
						var id_join = $(this).attr('id');						
						id_join = id_join.split("_");
						id_join = id_join[1];
																	
						
						update_collected_new(id_join,params_value_eval,3);						
					});
					
					$('.collected4-edit').editable({
						mode:'inline',
						emptytext: '0',
						display: function(value) {			    		    
						  value = value.replace(/\,/g,'');	
						  value = eval(value);				  
						  value = (Math.round(value * 1000)/1000);	
						  value = value.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");		  
					      $(this).text(value);		      
					    }		
					    									
					});					
					$('.collected4-edit').on('save', function(e, params) {

											
						params_value_eval = eval(params.newValue);						
						var id_join = $(this).attr('id');						
						id_join = id_join.split("_");
						id_join = id_join[1];
																	
						
						update_collected_new(id_join,params_value_eval,4);						
					});
						
						
					$('.collected-date1-edit').editable({
						mode:'inline',																		    													emptytext: '0',
					});									
					$('.collected-date1-edit').on('save', function(e, params) {
											
						params_value_eval = params.newValue;						
						var id_join = $(this).attr('id');						
						id_join = id_join.split("_");
						id_join = id_join[1];
																	
						
						update_collected_date(id_join,params_value_eval,1);						
					});	
					
					$('.collected-date2-edit').editable({
						mode:'inline',
						emptytext: '0',		    									
					});									
					$('.collected-date2-edit').on('save', function(e, params) {
											
						params_value_eval = params.newValue;						
						var id_join = $(this).attr('id');						
						id_join = id_join.split("_");
						id_join = id_join[1];
																	
						
						update_collected_date(id_join,params_value_eval,2);						
					});
					
					$('.collected-date3-edit').editable({
						mode:'inline',														
					    emptytext: '0',									
					});									
					$('.collected-date3-edit').on('save', function(e, params) {
											
						params_value_eval = params.newValue;						
						var id_join = $(this).attr('id');						
						id_join = id_join.split("_");
						id_join = id_join[1];
																	
						
						update_collected_date(id_join,params_value_eval,3);						
					});	
					
					$('.collected-date4-edit').editable({
						mode:'inline',
						emptytext: '0',								
					    									
					});									
					$('.collected-date4-edit').on('save', function(e, params) {

											
						params_value_eval = params.newValue;						
						var id_join = $(this).attr('id');						
						id_join = id_join.split("_");
						id_join = id_join[1];
																	
						
						update_collected_date(id_join,params_value_eval,4);						
					});
					
					
					$('.vat-edit').editable({
						mode:'inline',
						emptytext: '0',												
					});
					$('.vat-edit').on('save', function(e, params) {
												
						params_value_eval = eval(params.newValue);
						var id_join = $(this).attr('id');						
						id_join = id_join.split("_");
						id_join = id_join[1];
																		
						update_vat_new(id_join,params_value_eval);
						
						
					});
					
												
														
			}
		});
	});
}
*/


jQuery(document).ready(function(){
	var pathname = window.location.pathname;
	if(pathname.indexOf("add") == -1 && pathname.indexOf("total") == -1)
	{	
		streak.month();	
	}
	else if(pathname.indexOf("total") != -1)
	{
		streak.total();
	}

});

function clickChangeColumns(obj, url){
	$(document).ready(function() {
										
		import_columns(1);

	});
}

function clickChooseAll(){
	$(document).ready(function() {

	    $('.column-streak').each(function(){
		   this.checked = true;
	    });
				
	});
}

function strip(html)
{
   var tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";
}

function clickRemoveAll(){
	$(document).ready(function() {

	    $('.column-streak').each(function(){
		   this.checked = false;
	    });
				
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
				url: "/streak/public/report/ketoan/export-excel",		
				type: 'POST',
				dataType: 'JSON',
				data: {'data_export': jsonString},
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
					window.location = '/streak/ketoan.xls';					
				}
		  });
		  
				
	});
}



function exportTotalRev(){
	$(document).ready(function() {

		var data_export = [];
		
		//data_export.push(array_columns_choice);
		//var remove_row = 0;		
					
		$('table#table-total-channel tr').each(function() {
			var data_rows = [];
			$(this).find("td").each(function() {
				
					var td_data = $(this).html();								
										
					data_rows.push(td_data);
			});			

			data_export.push(data_rows);
		});
		
		
		var jsonString = JSON.stringify(data_export);	
		
		
	    $.ajax({
				url: "/streak/public/report/ketoan/export-total-rev",		
				type: 'POST',
				dataType: 'JSON',
				data: {'data_export': jsonString},
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
					window.location = '/streak/ketoan-total-rev.xls';					
				}
		  });
		
				
	});
}


$(document).ready(function() {   
   $('.a-filter-date').click(function(){	   
	   $('.a-filter-date.active').removeClass('active');					
	   $(this).addClass('active');
	   
	   $('.input-filter').each(function(){
			$(this).val("");
		});
	   	   
	   if(string_columns_choice == "")
	   {
		   import_columns(1);
	   }	
	   else
	   {
		   import_columns(2);   
	   }  
	   
	   
	   
	   create_table_channel($(this).attr('id'));
	  	   
   });
});


function create_table_channel(date_filter)
{
	$(document).ready(function() { 
		
		var total_facebook_actual_sold = 0;
		var total_dsp_actual_sold = 0;
		var total_dfp_actual_sold = 0;
		var total_google_actual_sold = 0;
		var total_entertaiment_actual_sold = 0;
		var total_outsource_actual_sold = 0;
		
		
		var total_facebook_actual_cost = 0;
		var total_dsp_actual_cost = 0;
		var total_dfp_actual_cost = 0;
		var total_google_actual_cost = 0;
		var total_entertaiment_actual_cost = 0;
		var total_outsource_actual_cost = 0;
		
		var total_facebook_invoice = 0;
		var total_dsp_invoice = 0;
		var total_dfp_invoice = 0;
		var total_google_invoice = 0;
		var total_entertaiment_invoice = 0;
		var total_outsource_invoice = 0;
		
		
		$.ajax({
			url: "/streak/public/report/ketoan/get-actual-streak-child",		
			type: 'POST',
			dataType: 'JSON',
			data: {'date_filter':date_filter},
			beforeSend : function() {																
			},
			success: function(result, status){	
				var data = result.data;
				for(var i = 0;i < data.length;i++)
				{
					var channel = data[i]['channel'];
					if((channel.indexOf("FB") != -1) || (channel.indexOf("Facebook") != -1))
					{
						
						total_facebook_actual_sold = parseFloat(total_facebook_actual_sold) + parseFloat(data[i]['actual_sold_value_vnd']);						
						total_facebook_actual_cost = parseFloat(total_facebook_actual_cost) + parseFloat(data[i]['actual_cost_usd']);						
						total_facebook_invoice = parseFloat(total_facebook_invoice) + parseFloat(data[i]['invoice_value']);						
					}
					else if((channel.indexOf("DSP") != -1))
					{
						total_dsp_actual_sold = parseFloat(total_dsp_actual_sold) + parseFloat(data[i]['actual_sold_value_vnd']);
						total_dsp_actual_cost = parseFloat(total_dsp_actual_cost) + parseFloat(data[i]['actual_cost_usd']);
						total_dsp_invoice = parseFloat(total_dsp_invoice) + parseFloat(data[i]['invoice_value']);
					}
				}
				
				$('#fb-actual-sold').html((Math.round(total_facebook_actual_sold * 1000)/1000).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")); 
				$('#fb-actual-cost').html((Math.round(total_facebook_actual_cost * 1000)/1000).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
				$('#fb-invoice').html((Math.round(total_facebook_invoice * 1000)/1000).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
				
				$('#dsp-actual-sold').html((Math.round(total_dsp_actual_sold * 1000)/1000).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")); 
				$('#dsp-actual-cost').html((Math.round(total_dsp_actual_cost * 1000)/1000).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
				$('#dsp-invoice').html((Math.round(total_dsp_invoice * 1000)/1000).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
				
				
								
			},
			complete: function(){									
			}
		});
	})
}

function inputFilter()
{
	$(document).ready(function() { 
		array_columns_choice = [];
		string_columns_choice = "";
		$('table.defaultTable tr.row-data').remove();
		//$('table.defaultTable tr.tr-total').remove();
		var drag_columns = getCookie('drag_columns');
		
		if(drag_columns != "")
		{
			drag_columns = drag_columns.split(",");
			for(var i = 0;i < drag_columns.length - 1;i++)
			{
				array_columns_choice.push(drag_columns[i]);
				string_columns_choice += "<th>"+drag_columns[i]+"</th>";				
			}
			
			array_columns_choice.push('start_date');
			array_columns_choice.push('end_date');
			string_columns_choice += "<th>start_date</th><th>end_date</th>";
						
			import_columns(3);
		}										
		
	});
}




function import_columns(level)
  {
	  
	 $(document).ready(function() {
		
		 if(level == 1)
		 {		 
			
			 array_columns_choice = ['streakID','name'];
			 string_columns_choice = "<th>streakID</th><th>name</th>";
			 var string_columns_remove = "";
			 var string_columns_search = "";
			 						 
			 $('.column-streak').each(function(){
				   if($(this).is(':checked'))
				   { 	
					   array_columns_choice.push($(this).val());
					   string_columns_choice += "<th>"+$(this).val()+"</th>";				   
				   }
			    });
			 
			 			
			 array_columns_choice.push('start_date');
			 array_columns_choice.push('end_date');
			 string_columns_choice += "<th>start_date</th><th>end_date</th>";
 
		}	 
		
		var date_filter = "";		
		$('.a-filter-date').each(function(){
			if($(this).hasClass('active'))
			{
				date_filter = $(this).attr('id');
			}
			
		});
		
		
		var input_filter = {};
		$('.input-filter').each(function(){
			if($(this).val() != "")
			{
				input_filter[$(this).attr('id')] = $(this).val();
									
			}
		});
		input_filter = JSON.stringify(input_filter);

		 $.ajax({
				url: "/streak/public/report/ketoan/process-ajax-request",		
				type: 'POST',
				dataType: 'JSON',
				data: {'array_columns_choice': array_columns_choice,'date_filter':date_filter,'input_filter':input_filter},
				beforeSend : function() {
						
						if(level == 1 || level == 2)
						{													
							
							$('.table-show').html('');
							$('<table class="defaultTable sar-table"><tr>'+string_columns_choice+'</tr></table>').appendTo('.table-show');	
							jQuery.each(array_columns_choice, function( key, val ) {
								if(val != 'streakID' && val != 'name' && val != 'start_date' && val != 'end_date')
								{
									string_columns_remove += "<td class='remove-column'><a onclick=remove_column('"+val+"')>X</a></td>"
								}
								else
								{
									string_columns_remove += "<td></td>";
								}
								string_columns_search += "<td><ul><li style='float:left;'><input onchange='inputFilter()' type='text' name='input-filter' id='"+val+"' class='input-filter'  /></li></ul></td>";			
							});		
							
							$('<tr class="tr-remove"></tr>').appendTo('table.defaultTable');
							$('table.defaultTable tr:last').append(string_columns_remove);
																
							$('<tr class="tr-search"></tr>').appendTo('table.defaultTable');
							$('table.defaultTable tr:last').append(string_columns_search);
														
						}						

				},
				success: function(result, status){							
					var data = result.data;
					
					
					$("<tr class='row-total'></tr>").appendTo('table.defaultTable');
					var total_actual_sold = 0;
					
					for(var i = 0;i < data.length;i++)
					{
						var id_join_parent = data[i]['id_join'];
						
						$("<tr class='row-data' onclick = 'display_channel("+data[i]['streakID']+","+i+")' id='tr-campaign-"+i+"'></tr>").appendTo('table.defaultTable');
						jQuery.each( data[i], function( key, val ) {	
												
							if(key != 'child' && key != 'id_join')
							{
								if(key != 'streakID')
								{	
									
									if(key == 'actual_cost_usd')
									{
										$("<td class='actual-cost-parent-edit' id='actual-cost_"+id_join_parent+"'>"+val+"</td>").appendTo('table.defaultTable tr:last');
									}
									else if(key == 'actual_cost_vnd')
									{
										$("<td id='actual-cost_"+id_join_parent+"_vnd'>"+val+"</td>").appendTo('table.defaultTable tr:last');
									}
									else if(key == 'actual_sold_value_vnd')
									{
										$("<td class='actual-sold-parent-edit' id='actual-sold-value-vnd_"+id_join_parent+"'>"+val+"</td>").appendTo('table.defaultTable tr:last');
									}
									else if(key == 'invoice_value')
									{
										$("<td data-type='number' class='invoice-parent-edit' id='invoice_"+id_join_parent+"'>"+val+"</td>").appendTo('table.defaultTable tr:last');
									}
									else if(key == 'invoice_vnd')
									{
										$("<td id='invoice_"+id_join_parent+"_vnd'>"+val+"</td>").appendTo('table.defaultTable tr:last');
									}
									
									else if(key == 'collected_value')
									{
										$("<td class='collected-edit' id='collected_"+id_join_parent+"'>"+val+"</td>").appendTo('table.defaultTable tr:last');
									}
									
									else if(key == 'dealsize')
									{
										$("<td data-type='number' class='dealsize-edit' id='dealsize_"+id_join_parent+"'>"+val+"</td>").appendTo('table.defaultTable tr:last');
									}
									else if(key == 'vat')
									{
										$("<td class='vat-edit' id='vat_"+id_join_parent+"'>"+val+"</td>").appendTo('table.defaultTable tr:last');
									}
									else if(key == 'total_rev')
									{
										val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
										$("<td id='total-rev_"+id_join_parent+"'>"+val+"</td>").appendTo('table.defaultTable tr:last');
									}
									
									else if(key == 'remain')
									{
										val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
										$("<td id='remain_"+id_join_parent+"'>"+val+"</td>").appendTo('table.defaultTable tr:last');
									}
									
									else																							  											{
						  				$('<td>'+val+'</td>').appendTo('table.defaultTable tr:last');
						  			}
						  		}
						  		else
						  		{
							  		if(data[i]['child'].length > 0)
							  		{
							  			$('<td><img class="next-down" src="/streak/public/img/next.png" />&nbsp;&nbsp;&nbsp;'+val+'</td>').appendTo('table.defaultTable tr:last');
							  		}
							  		else
							  		{
								  		
								  		$('<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+val+'</td>').appendTo('table.defaultTable tr:last');
							  		}
						  		}
						  	}
						  	
						});
						
						for(var j = 0;j < data[i]['child'].length;j++)
						{
							var id_join_child = data[i]['child'][j]['id_join'];
							
							$("<tr class='row-data tr-channel tr-channel-"+data[i]['streakID']+"'></tr>").appendTo('table.defaultTable');
							jQuery.each( data[i]['child'][j], function( key_child, val_child ) {
								
																
								if(key_child == 'id_join')
								{
									
								}
								else if(key_child != 'streakID')
								{
									if(key_child == "actual_cost_usd")
									{
										$("<td id='actual-cost-child_"+id_join_child+"' class='actual-cost-usd-edit actual-cost_"+id_join_parent+"' id_parent='actual-cost_"+id_join_parent+"'>"+val_child+"</td>").appendTo('table.defaultTable tr:last');						  				
						  			}
						  			else if(key_child == "actual_cost_vnd")
						  			{
							  			$("<td id='actual-cost-child_"+id_join_child+"_vnd' class='actual-cost_"+id_join_parent+"_vnd'  id_parent='actual-cost_"+id_join_parent+"_vnd'>"+val_child+"</td>").appendTo('table.defaultTable tr:last');
						  			}
						  			else if(key_child == "actual_sold_value_vnd")
									{
										$("<td id='actual-sold-value-vnd-child_"+id_join_child+"' class='actual-sold-value-vnd-child-edit actual-sold-value-vnd_"+id_join_parent+"' id_parent='actual-sold-value-vnd_"+id_join_parent+"'>"+val_child+"</td>").appendTo('table.defaultTable tr:last');
										

										total_actual_sold = parseFloat(total_actual_sold) + parseFloat(val_child.replace(/\,/g,''));
									
						  			}
						  			else if(key_child == "invoice_value")
						  			{
							  			$("<td data-type='number' id='invoice_"+id_join_child+"' class='invoice-edit invoice_"+id_join_parent+"' id_parent='invoice_"+id_join_parent+"'>"+val_child+"</td>").appendTo('table.defaultTable tr:last');
						  			}
						  			else if(key_child == "invoice_vnd")
									{
										$("<td id='invoice_"+id_join_child+"_vnd' class='invoice_"+id_join_parent+"_vnd' id_parent='invoice_"+id_join_parent+"_vnd'>"+val_child+"</td>").appendTo('table.defaultTable tr:last');						  				
						  			}						  										  								  			
						  			else
						  			{								 	
								 		$('<td>'+val_child+'</td>').appendTo('table.defaultTable tr:last');								 
								 	}
								
							  	}
							  	else
							  	{
								  	$('<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+val_child+'</td>').appendTo('table.defaultTable tr:last');
							  	}
							});
						}
						
						
					}
					
				
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
					
					
					$('.vat-edit').editable({
						mode:'inline',
						emptytext: '0',
						display: function(value) {			    		    
						  value = value.replace(/\,/g,'');	
						  value = eval(value);				  
						  value = Math.round(value * 1000)/1000;			  
					      $(this).text(value);		      
					    }												
					});
					$('.vat-edit').on('save', function(e, params) {
												
						params_value_eval = eval(params.newValue);
						var id_join = $(this).attr('id');						
						id_join = id_join.split("_");
						id_join = id_join[1];
																		
						update_vat(id_join,params_value_eval,date_filter);
						
						
					});
										
					$('.actual-cost-parent-edit').editable({
						mode:'inline',
						emptytext: '0',
						display: function(value) {			    		    
						  value = value.replace(/\,/g,'');	
						  value = eval(value);				  
						  value = Math.round(value * 1000)/1000;			  
					      $(this).text(value);		      
					    }												
					});
					$('.actual-cost-parent-edit').on('save', function(e, params) {
												
						params_value_eval = eval(params.newValue);
						var id_join = $(this).attr('id');						
						id_join = id_join.split("_");
						id_join = id_join[1];

						update_actual_cost(id_join,params_value_eval,date_filter)
					});
					
					$('.actual-sold-parent-edit').editable({
						mode:'inline',
						emptytext: '0',
						display: function(value) {			    		    
						  value = value.replace(/\,/g,'');	
						  value = eval(value);				  
						  value = Math.round(value * 1000)/1000;			  
					      $(this).text(value);		      
					    }												
					});
					$('.actual-sold-parent-edit').on('save', function(e, params) {
												
						params_value_eval = eval(params.newValue);
						var id_join = $(this).attr('id');						
						id_join = id_join.split("_");
						id_join = id_join[1];

						update_actual_sold_value_vnd(id_join,params_value_eval,date_filter)
					});
					
					
					
					$('.invoice-parent-edit').editable({
						mode:'inline',
						emptytext: '0',
						display: function(value) {			    		    
						  value = value.replace(/\,/g,'');	
						  value = eval(value);				  
						  //value = Math.round(value * 1000)/1000;			  
					      $(this).text(value);		      
					    }												
					});
					$('.invoice-parent-edit').on('save', function(e, params) {
												
						params_value_eval = eval(params.newValue);
						var id_join = $(this).attr('id');						
						id_join = id_join.split("_");
						id_join = id_join[1];

						update_invoice_parent(id_join,params_value_eval,date_filter);
					});
					
					$('.invoice-edit').editable({
						mode:'inline',
						emptytext: '0',
						display: function(value) {			    		    
						  value = value.replace(/\,/g,'');	
						  value = eval(value);				  
						  //value = Math.round(value * 1000)/1000;			  
					      $(this).text(value);		      
					    }												
					});
					$('.invoice-edit').on('save', function(e, params) {
												
						params_value_eval = eval(params.newValue);
						var id_join = $(this).attr('id');						
						id_join = id_join.split("_");
						id_join = id_join[1];
						
						var id_join_parent = $(this).attr('id_parent');						

						
						update_invoice(id_join,params_value_eval,date_filter,id_join_parent);
					});
					
					$('.dealsize-edit').editable({
						mode:'inline',
						emptytext: '0',
						display: function(value) {			    		    
						  value = value.replace(/\,/g,'');	
						  //value = eval(value);				  
						  value = Math.round(value * 1000)/1000;		
						  value = value.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");	  
					      $(this).text(value);		      
					    }												
					});
					$('.dealsize-edit').on('save', function(e, params) {
												
						params_value_eval = eval(params.newValue);
						var id_join = $(this).attr('id');						
						id_join = id_join.split("_");
						id_join = id_join[1];
																		
						update_dealsize(id_join,params_value_eval,date_filter);
						
						
					});
					
					$('.actual-cost-usd-edit').editable({
						mode:'inline',
						emptytext: '0',
						display: function(value) {			    		    
						  value = value.replace(/\,/g,'');	
						  value = eval(value);				  
						  value = Math.round(value * 1000)/1000;			  
					      $(this).text(value);		      
					    }												
					});
					$('.actual-cost-usd-edit').on('save', function(e, params) {
						params_value_eval = eval(params.newValue);
						var id_join = $(this).attr('id');						
						id_join = id_join.split("_");
						id_join = id_join[1];
						
						var id_join_parent = $(this).attr('id_parent');						

						
						update_actual_cost_child(id_join,params_value_eval,date_filter,id_join_parent);
						
						
					});
					
					
					$('.actual-sold-value-vnd-child-edit').editable({
						mode:'inline',
						emptytext: '0',
						display: function(value) {			    		    
						  value = value.replace(/\,/g,'');	
						  value = eval(value);				  
						  value = Math.round(value * 1000)/1000;			  
					      $(this).text(value);		      
					    }												
					});
					$('.actual-sold-value-vnd-child-edit').on('save', function(e, params) {
						params_value_eval = eval(params.newValue);
						var id_join = $(this).attr('id');						
						id_join = id_join.split("_");
						id_join = id_join[1];						
						var id_join_parent = $(this).attr('id_parent');																						
						update_actual_sold_value_vnd_child(id_join,params_value_eval,date_filter,id_join_parent);
						
					});
					
					saveCookieColumns();
					$('.defaultTable').dragtable({
						persistState: function(table) { 
							saveCookieColumns();							
						  } 	
					});					
				}
		  });
	
	});
				 	
 }
 
 function remove_column(column)
 {
	 
	
	var cookie_columns = "";

	 var drag_columns = getCookie('drag_columns');
		
		if(drag_columns != "")
		{
			drag_columns = drag_columns.split(",");
			
			
			for(var i = 0;i < drag_columns.length - 1;i++)
			{
				if(drag_columns[i] != column)
				{
					cookie_columns += drag_columns[i] + ",";
				}
			}
			
			console.log(cookie_columns);
			
			setCookie("drag_columns",cookie_columns,7);
			location.reload();
			
		}
	 
 }


function display_channel(a,b)
{
							
	var display_status = $('.tr-channel-'+a).css('display');
			
	if(display_status == "none")
	{		

		$('#tr-campaign-'+b+' td').css("font-weight","bold");
		$('#tr-campaign-'+b+' td img.next-down').attr("src","/streak/public/img/down.png");
		$('.tr-channel-'+a).css('display','table-row');
		$('.tr-channel-'+a).css('background','#000');
		$('.tr-channel-'+a).css('color','#fff');		
	}
	else{
					
		$('#tr-campaign-'+b+" td").css("font-weight","normal");
		$('#tr-campaign-'+b+" td img.next-down").attr("src","/streak/public/img/next.png");
		$('.tr-channel-'+a).css('display','none');
		$('.tr-channel-'+a).css('background','none');
		$('.tr-channel-'+a).css('color','#000');
					
	}
}


  

function saveCookieColumns()
{
	$(document).ready(function(){
		
		array_columns_choice = [];
		var cookie_columns = "";
		string_columns_choice = "";
		$('.defaultTable tr th').each(function() { 
			cookie_columns += $(this).html() + ",";
			array_columns_choice.push($(this).html());
			string_columns_choice += "<th>"+$(this).html()+"</th>";
		});
						
		setCookie("drag_columns",cookie_columns,7);
	});
}

function setCookie(cname,cvalue,exdays) {
						
	cname = cname.replace(/\=/g,'');
	cvalue = cvalue.replace(/\=/g,'');
	var d = new Date();
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	var expires = "expires=" + d.toGMTString();
	document.cookie = cname+"="+cvalue+"; "+expires;
		    		    		    
}

function getCookie(cname) {
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++) {
	    var c = ca[i];
	    while (c.charAt(0)==' ') c = c.substring(1);
	    if (c.indexOf(name) == 0) {
	        return c.substring(name.length, c.length);
		}
    }
	    return "";
}


