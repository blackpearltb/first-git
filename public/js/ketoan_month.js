var array_columns_choice = [];
var string_columns_choice = "";


var streak = {
	'month': function(){
				
		array_columns_choice = getCookie('array_columns_choice').split(",");
		array_columns_choice.pop();
		
		string_columns_choice = getCookie('string_columns_choice');
						
		if(string_columns_choice != "")
		{		
						
			import_columns(2);
		}
		
						
	},
	'autocomplete_client': function(){
		
		
		var clients = $('#hidden_clients').val();
		var streaksID = $('#hidden_streaksID').val();
		clients = JSON.parse(clients);
		streaksID = JSON.parse(streaksID);
		$('#client').autocomplete({
			lookup: clients,
			onSelect: function (suggestion) {
									      						     
			}
					    
		});
		
		$('#streakID').autocomplete({
			lookup: streaksID,
			onSelect: function (suggestion) {
					      						     
			}
					    
		});				
	}	
}


jQuery(document).ready(function(){

	var pathname = window.location.pathname;

	
	if(pathname.indexOf("add") == -1 && pathname.indexOf("total") == -1)
	{	
		streak.month();	
	}	
	if(pathname.search("edit-collection") > -1)
	{
		streak.autocomplete_client();
	}
	if(pathname.search("add-collection") > -1)
	{
		streak.autocomplete_client();
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
				url: "/streak_new/public/report/ketoan/export-excel",		
				type: 'POST',
				dataType: 'JSON',
				data: {'data_export': jsonString,'filename':'ketoan_month'},
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
					window.location = '/streak_new/ketoan_month.xls';					
				}
		  });
		  
				
	});
}



function exportTotalRev(){
	$(document).ready(function() {

		var data_export = [];	
					
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
				url: "/streak_new/public/report/ketoan/export-total-rev",		
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
					window.location = '/streak_new/ketoan-total-rev.xls';					
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
		
		
		array_columns_choice = getCookie('array_columns_choice').split(",");
		string_columns_choice = getCookie('string_columns_choice');
	   	   
	   if(string_columns_choice == "")
	   {
		   import_columns(1);
	   }	
	   else
	   {
		   
		   import_columns(2);   
	   }  
	   
	   
	   
	   //create_table_channel($(this).attr('id'));
	  	   
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
			url: "/streak_new/public/report/ketoan/get-actual-streak-child",		
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
		
		$('table.defaultTable tr.row-data').remove();
		
		array_columns_choice = getCookie('array_columns_choice').split(",");
		array_columns_choice.pop();
		string_columns_choice = getCookie('string_columns_choice');
		if(string_columns_choice != "")
		{											
			import_columns(3);
		}
															
	});
}




function import_columns(level)
  {
	  
	 $(document).ready(function() {
		
		 if(level == 1)
		 {		 
			
			 array_columns_choice = ['stt','streakID','name'];
			 string_columns_choice = "<th>STT</th><th>Streak ID</th><th>Campaign Name</th>";
			 var string_columns_remove = "";
			 var string_columns_search = "";
			 var string_columns_sort = "";						 
			 $('.column-streak').each(function(){
				   if($(this).is(':checked'))
				   { 	
					   array_columns_choice.push($(this).val());					   					
					   string_columns_choice += "<th>"+$(this).attr('data-title')+"</th>";				   
				   }
			    });
			 			 					 
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
		
		var sortaction = $('#sortaction').val();
		
		var array_remove_string_choice_temp = string_columns_choice.split("</th>");
		var array_remove_string_choice = [];
		var string_columns_drag = "";
		for(var i = 0;i < array_remove_string_choice_temp.length - 1;i++)
		{	
			string_columns_drag += "<td>"+array_columns_choice[i]+"</td>";		
			array_remove_string_choice.push(array_remove_string_choice_temp[i].replace("<th>","").replace(/\ /g,"_"));
		}
		
		 $.ajax({
				url: "/streak_new/public/report/ketoan/process-ajax-request",		
				type: 'POST',
				dataType: 'JSON',
				data: {'date_filter':date_filter,'input_filter':input_filter,'sortaction':sortaction},
				beforeSend : function() {
						$("body").addClass("loading");
						if(level == 1 || level == 2)
						{													
							
							$('.table-show').html('');
							$('<table class="defaultTable sar-table"><tr>'+string_columns_choice+'</tr></table>').appendTo('.table-show');
							
							jQuery.each(array_columns_choice, function( key, val ) {								
							
								if(val != 'streakID' && val != 'name' && val != 'stt')
								{
									
									string_columns_remove += "<td class='remove-column'><a onclick=remove_column('"+val+"','"+array_remove_string_choice[key]+"')>X</a></td>";
									/*
									string_columns_remove += "<td class='remove-column'>";
									string_columns_remove += "<a onclick=remove_column('"+val+"','"+array_remove_string_choice[key]+"')>";
									string_columns_remove += "X";
									string_columns_remove += "</a>";
									string_columns_remove += "</td>";
									*/
								}
								else
								{
									string_columns_remove += "<td></td>";
								}
								
								if(val != 'client' && val != 'supplier' && val != 'stt')
								{
									string_columns_search += "<td><ul><li style='float:left;'><input onchange='inputFilter()' type='text' name='input-filter' id='"+val+"' class='input-filter' /></li><li style='float:left;'><a onclick=sortAction('"+val+"','asc')><img class='btn-sortable-asc' id='"+val+"' src='/streak_new/public/img/sortasc.png' width='18px' /></a></li><li style='float:left;'><a onclick=sortAction('"+val+"','desc')><img class='btn-sortable-desc' id='"+val+"' src='/streak_new/public/img/sortdesc.png' width='18px' /></a></li></ul></td>";			
								}
								else
								{
									string_columns_search += "<td></td>";
								}
							});		
							
							
							
							$('<tr class="tr-remove"></tr>').appendTo('table.defaultTable');
							$('table.defaultTable tr:last').append(string_columns_remove);
																
							$('<tr class="tr-search"></tr>').appendTo('table.defaultTable');
							$('table.defaultTable tr:last').append(string_columns_search);
							
							$('<tr class="tr-drag" style="display:none"></tr>').appendTo('table.defaultTable');
							$('table.defaultTable tr:last').append(string_columns_drag);							
						}						

				},
				success: function(result, status){							
					var data = result.data;		
					console.log(result.date_filter);
					$('#table-total-campaign #total-actual-sold').html(result.total_actual_sold);						
					$('#table-total-campaign #total-actual-cost').html(result.total_actual_cost);						
					$('#table-total-campaign #total-lobby').html(result.total_lobby);						
					$('#table-total-campaign #total-entertainment').html(result.total_entertainment);
					$('#table-total-campaign #total-invoice-usd').html(result.total_invoice_usd);						
					
					var array_dealsize = [];
					var array_vat = [];					
					var clients = result.clients;
					var suppliers = result.suppliers;
											
					
					$("<tr class='row-total'></tr>").appendTo('table.defaultTable');
										
					for(var i = 0;i < data.length;i++)
					{
						var stt = parseInt(i) + parseInt("1");
						if(data[i]['dealsize'] != null)
						{
							var id_dealsize = data[i]['streakID']+"|"+(data[i]['dealsize'].replace(/\,/g,''));
						}
						else
						{
							var id_dealsize = data[i]['streakID']+"|"+data[i]['dealsize'];
						}
						
						if(data[i]['vat'] != null)
						{
							var id_vat = data[i]['streakID']+"|"+(data[i]['vat'].replace(/\,/g,''));
						}
						else
						{
							var id_vat = data[i]['streakID']+"|"+data[i]['vat'];
						}
						array_dealsize.push(id_dealsize);
						array_vat.push(id_vat);
						
						
						var id_join_parent = data[i]['id_join'];
						var percent = data[i]['percent'];
						if(percent > 5)
						{
							$("<tr class='row-data highlight' onclick = 'display_channel("+data[i]['streakID']+","+i+")' id='tr-campaign-"+i+"'></tr>").appendTo('table.defaultTable');							
						}
						else
						{
							$("<tr class='row-data' onclick = 'display_channel("+data[i]['streakID']+","+i+")' id='tr-campaign-"+i+"'></tr>").appendTo('table.defaultTable');
						}
						
						
						jQuery.each( array_columns_choice, function( key, val ) {
							
							if(val != 'streakID')
							{	
									if(val == 'stt')
									{
										$("<td>"+stt+"</td>").appendTo('table.defaultTable tr:last');
									}
									else if(val == 'name')
									{
										$("<td><a href='"+data[i]['alternateLink']+"' target='_blank'>"+data[i][val]+"</a></td>").appendTo('table.defaultTable tr:last');
									}
									else if(val == 'actual_cost_usd')
									{
										$("<td class='actual-cost-parent-edit' id='actual-cost_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
									}
									else if(val == 'actual_cost_vnd')
									{
										$("<td id='actual-cost_"+id_join_parent+"_vnd'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
									}
									else if(val == 'actual_sold_value_vnd')
									{
										$("<td class='actual-sold-parent-edit' id='actual-sold-value-vnd_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
									}
									else if(val == 'invoice_value')
									{
										$("<td   class='invoice-parent-edit' id='invoice_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
									}
									else if(val == 'invoice_vnd')
									{
										$("<td id='invoice_"+id_join_parent+"_vnd'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
									}									
									else if(val == 'collected_value')
									{
										$("<td class='collected-edit' id='collected_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
									}
									else if(val == 'lobby')
									{
										$("<td class='lobby-edit' id='lobby_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
									}
									else if(val == 'entertainment')
									{
										$("<td class='entertainment-edit' id='entertainment_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
									}
									else if(val == 'dealsize')
									{
										$("<td   class='dealsize-edit' id='dealsize_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
									}
									else if(val == 'vat')
									{
										$("<td class='vat-edit' id='vat_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
									}									
									else if(val == 'total_rev')
									{
										val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
										$("<td id='total-rev_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
									}									
									else if(val == 'remain')
									{
										val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
										$("<td id='remain_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
									}									
									else if(val == 'client')
									{										
										$("<td><input id='"+data[i]['streakID']+data[i]['name']+"' type='text' value='"+data[i][val]+"' name='client' class='client'/></td>").appendTo('table.defaultTable tr:last');
									}	
									else if(val == 'supplier')
									{										
										$("<td><input id='"+data[i]['streakID']+data[i]['channel']+"' type='text' value='"+data[i][val]+"' name='supplier' class='supplier'/></td>").appendTo('table.defaultTable tr:last');
									}								
									else																							  											{
						  				$('<td>'+data[i][val]+'</td>').appendTo('table.defaultTable tr:last');
						  			}
						  			
						  	}
						  	else
						  	{
							  		
							  		if(data[i]['child'].length > 0)
							  		{
							  			$('<td><img class="next-down" src="/streak_new/public/img/next.png" />&nbsp;&nbsp;&nbsp;'+data[i][val]+'</td>').appendTo('table.defaultTable tr:last');
							  		}
							  		else
							  		{
								  		
								  		$('<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+data[i][val]+'</td>').appendTo('table.defaultTable tr:last');
							  		}
						  	}
						  	
							
							
						});
						
						for(var j = 0;j < data[i]['child'].length;j++)
						{
							var id_join_child = data[i]['child'][j]['id_join'];
							
							$("<tr class='row-data tr-channel tr-channel-"+data[i]['streakID']+"'></tr>").appendTo('table.defaultTable');														
							jQuery.each( array_columns_choice, function( key, val ) {	
								if(val != 'streakID')
									{
										if(val == "actual_cost_usd")
										{
											$("<td id='actual-cost-child_"+id_join_child+"' class='actual-cost-usd-edit actual-cost_"+id_join_parent+"' id_parent='actual-cost_"+id_join_parent+"'>"+data[i]['child'][j][val]+"</td>").appendTo('table.defaultTable tr:last');						  				
							  			}
							  			else if(val == "actual_cost_vnd")
							  			{
								  			$("<td id='actual-cost-child_"+id_join_child+"_vnd' class='actual-cost_"+id_join_parent+"_vnd'  id_parent='actual-cost_"+id_join_parent+"_vnd'>"+data[i]['child'][j][val]+"</td>").appendTo('table.defaultTable tr:last');
							  			}
							  			else if(val == "actual_sold_value_vnd")
										{
											$("<td id='actual-sold-value-vnd-child_"+id_join_child+"' class='actual-sold-value-vnd-child-edit actual-sold-value-vnd_"+id_join_parent+"' id_parent='actual-sold-value-vnd_"+id_join_parent+"'>"+data[i]['child'][j][val]+"</td>").appendTo('table.defaultTable tr:last');											
										
							  			}
							  			else if(val == "invoice_value")
							  			{
								  			$("<td data-type='text' id='invoice_"+id_join_child+"' class='invoice-edit invoice_"+id_join_parent+"' id_parent='invoice_"+id_join_parent+"'>"+data[i]['child'][j][val]+"</td>").appendTo('table.defaultTable tr:last');
							  			}
							  			else if(val == "invoice_vnd")
										{
											$("<td id='invoice_"+id_join_child+"_vnd' class='invoice_"+id_join_parent+"_vnd' id_parent='invoice_"+id_join_parent+"_vnd'>"+data[i]['child'][j][val]+"</td>").appendTo('table.defaultTable tr:last');						  				
							  			}						  										  								  											else if(val == 'supplier')
										{										
											$("<td><input id='"+data[i]['child'][j]['streakID']+data[i]['child'][j]['channel']+"' type='text' value='"+data[i]['child'][j][val]+"' name='supplier' class='supplier'/></td>").appendTo('table.defaultTable tr:last');
										}
							  			else
							  			{								 	
									 		$('<td>'+data[i]['child'][j][val]+'</td>').appendTo('table.defaultTable tr:last');								 
									 	}
									
								  	}
								  	else
								  	{
									  	$('<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+data[i]['child'][j][val]+'</td>').appendTo('table.defaultTable tr:last');
								  	}
								
							});
							
						}
																		
					}
					
					$('#input-dealsize').val(array_dealsize);
					$('#input-vat').val(array_vat);

					
					$('.client').autocomplete({
					    lookup: clients,
					    onSelect: function (suggestion) {
					      	
					       var id_client = suggestion.data;
					       var key_match = $(this).attr('id');
					       
					       update_client(key_match,id_client);
					    },
					    onInvalidateSelection: function() {
						    clear_client($(this).attr('id'));						  	
				        }
					});
					
					
					$('.supplier').autocomplete({
					    lookup: suppliers,
					    onSelect: function (suggestion) {
					      	
					       var id_supplier = suggestion.data;
					       var key_match = $(this).attr('id');
					       update_supplier(key_match,id_supplier);
					    },
					    onInvalidateSelection: function() {
						    clear_supplier($(this).attr('id'));						  	
				        }
					});
					
					$("body").removeClass("loading");
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
						  //value = eval(value);				  
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
					
					
					$('.lobby-edit').editable({
						mode:'inline'
																		
					});
					$('.lobby-edit').on('save', function(e, params) {
												
						//params_value_eval = eval(params.newValue);
						var id_join = $(this).attr('id');						
						id_join = id_join.split("_");
						id_join = id_join[1];
																		
						update_lobby(id_join,params.newValue,date_filter);
						
						
					});
					
					$('.entertainment-edit').editable({
						mode:'inline'
						
					});
					
					$('.entertainment-edit').on('save', function(e, params) {
												
						//params_value_eval = eval(params.newValue);
						var id_join = $(this).attr('id');						
						id_join = id_join.split("_");
						id_join = id_join[1];
																		
						update_entertainment(id_join,params.newValue,date_filter);
												
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
 
 
 function sortAction(column,action)
 {
	 $(document).ready(function(){
		 var id_sort = "";
		 if(action == 'asc')
		 {
			 $('#'+column+'.btn-sortable-asc').css('display','none');
			 $('#'+column+'.btn-sortable-desc').css('display','block');
			 id_sort = column +"-asc";
		 }
		 else
		 {
			 $('#'+column+'.btn-sortable-asc').css('display','block');
			 $('#'+column+'.btn-sortable-desc').css('display','none');
			 id_sort = column + "-desc";
		 }
		 	
			$('#sortaction').val(id_sort);
													
			$('table.defaultTable tr.row-data').remove();						
			import_columns(3);
	 });
	 
 }

 
 
 function remove_column(column,column2)
 {
	
	column2 = column2.replace(/\_/g," ");
	 array_columns_choice = getCookie('array_columns_choice');
	 if(array_columns_choice != "")
		{
			array_columns_choice = array_columns_choice.split(",");
			
			
			for(var i = 0;i < array_columns_choice.length - 1;i++)
			{
				if(array_columns_choice[i] == column)
				{
					var index = array_columns_choice.indexOf(column);
					if (index > -1) {
					    array_columns_choice.splice(index, 1);
					    string_columns_choice = string_columns_choice.replace("<th>"+column2+"</th>","");
					}
					
					
				}
			}						
			setCookie("array_columns_choice",array_columns_choice,7);
			setCookie("string_columns_choice",string_columns_choice,7);
			location.reload();
			
		}
		
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


  

function saveCookieColumns()
{
	$(document).ready(function(){				
		
		
		array_columns_choice = [];		
		string_columns_choice = "";
		$('.defaultTable tr th').each(function() { 								
			string_columns_choice += "<th>"+$(this).html()+"</th>";		
		});
		$('.defaultTable tr.tr-drag td').each(function() { 								
			array_columns_choice += $(this).html()+",";		
		});				
		setCookie("array_columns_choice",array_columns_choice,7);
		setCookie("string_columns_choice",string_columns_choice,7);
		
	});
}

function setCookie(cname,cvalue,exdays) {
						
	//cname = cname.replace(/\=/g,'');
	//cvalue = cvalue.replace(/\=/g,'');
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



