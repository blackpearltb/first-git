var array_columns_choice = [];
var string_columns_choice = "";

var streak = {
	'total': function(){
				
		array_columns_choice = getCookie('array_columns_choice_total').split(",");
		array_columns_choice.pop();		
		string_columns_choice = getCookie('string_columns_choice_total');
						
		if(string_columns_choice != "")
		{		
						
			import_columns(2);
		}
		else
		{
			import_columns(1);
		}
						
	},
	
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
				data: {'data_export': jsonString,'filename':'ketoan_total'},
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
					window.location = '/streak_new/ketoan_total.xls';					
				}
		  });
		  
				
	});
}

function inputFilter()
{
	$(document).ready(function() { 
		
		$('table.defaultTable tr.row-data').remove();		
		array_columns_choice = getCookie('array_columns_choice_total').split(",");
		array_columns_choice.pop();
		string_columns_choice = getCookie('string_columns_choice_total');
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
			
			 array_columns_choice = ['stt','streakID','name','actual_sold_value_vnd','actual_cost_usd','actual_gp','start_date','end_date'];
			 string_columns_choice = "<th>STT</th><th>Streak ID</th><th>Campaign Name</th><th>Actual Sold Value VND</th><th>Actual Cost USD</th><th>Actual GP</th><th>Start Date</th><th>End Date</th>";
			  var string_columns_search = "";
			 var string_columns_remove = "";
			var string_columns_sort = "";	
			 						 
			 $('.column-streak').each(function(){
				   if($(this).is(':checked'))
				   { 	
					   array_columns_choice.push($(this).val());					   					
					   string_columns_choice += "<th>"+$(this).attr('data-title')+"</th>";				   
				   }
			    });
			 
		}
		
		
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
				url: "/streak_new/public/report/ketoan/total",		
				type: 'POST',
				dataType: 'JSON',
				data: {'input_filter':input_filter,'sortaction':sortaction},
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
					var clients = result.clients;
					
					var array_dealsize = [];
					var array_vat = [];
					var array_remain = [];
					var array_collected1 = [];
					var array_collected2 = [];
					var array_collected3 = [];
					var array_collected4 = [];
					var array_collected_date1 = [];
					var array_collected_date2 = [];
					var array_collected_date3 = [];
					var array_collected_date4 = [];
					
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
												
						if(data[i]['remain'] != null)
						{						
							var id_remain = data[i]['streakID']+"|"+(data[i]['remain'].replace(/\,/g,''));
						}
						else
						{
							var id_remain = data[i]['streakID']+"|"+data[i]['remain'];
						}
						
						if(data[i]['collected_1'] != null)
						{						
							var id_collected_1 = data[i]['streakID']+"|"+(data[i]['collected_1'].replace(/\,/g,''));
						}
						else
						{
							var id_collected_1 = data[i]['streakID']+"|"+data[i]['collected_1'];
						}
						if(data[i]['collected_2'] != null)
						{						
							var id_collected_2 = data[i]['streakID']+"|"+(data[i]['collected_2'].replace(/\,/g,''));
						}
						else
						{
							var id_collected_2 = data[i]['streakID']+"|"+data[i]['collected_2'];
						}
						if(data[i]['collected_3'] != null)
						{						
							var id_collected_3 = data[i]['streakID']+"|"+(data[i]['collected_3'].replace(/\,/g,''));
						}
						else
						{
							var id_collected_3 = data[i]['streakID']+"|"+data[i]['collected_3'];
						}
						
						if(data[i]['collected_4'] != null)
						{						
							var id_collected_4 = data[i]['streakID']+"|"+(data[i]['collected_4'].replace(/\,/g,''));
						}
						else
						{
							var id_collected_4 = data[i]['streakID']+"|"+data[i]['collected_4'];
						}
						
						if(data[i]['collected_date1'] != null)
						{						
							var id_collected_date1 = data[i]['streakID']+"|"+(data[i]['collected_date1'].replace(/\,/g,''));
						}
						else
						{
							var id_collected_date1 = data[i]['streakID']+"|"+data[i]['collected_date1'];
						}
						
						if(data[i]['collected_date2'] != null)
						{						
							var id_collected_date2 = data[i]['streakID']+"|"+(data[i]['collected_date2'].replace(/\,/g,''));
						}
						else
						{
							var id_collected_date2 = data[i]['streakID']+"|"+data[i]['collected_date2'];
						}
						
						if(data[i]['collected_date3'] != null)
						{						
							var id_collected_date3 = data[i]['streakID']+"|"+(data[i]['collected_date3'].replace(/\,/g,''));
						}
						else
						{
							var id_collected_date3 = data[i]['streakID']+"|"+data[i]['collected_date3'];
						}
						if(data[i]['collected_date4'] != null)
						{						
							var id_collected_date4 = data[i]['streakID']+"|"+(data[i]['collected_date4'].replace(/\,/g,''));
						}
						else
						{
							var id_collected_date4 = data[i]['streakID']+"|"+data[i]['collected_date4'];
						}
						
						
						array_dealsize.push(id_dealsize);
						array_vat.push(id_vat);
						array_remain.push(id_remain);
						array_collected1.push(id_collected_1);
						array_collected2.push(id_collected_2);
						array_collected3.push(id_collected_3);
						array_collected4.push(id_collected_4);
						array_collected_date1.push(id_collected_date1);
						array_collected_date2.push(id_collected_date2);
						array_collected_date3.push(id_collected_date3);
						array_collected_date4.push(id_collected_date4);
						
						var collect_count = 1;
						var collect_date_count = 1;
						var id_join_parent = data[i]['id_join'];
						var streakID = data[i]['streakID'];
						$("<tr class='row-data' onclick = 'display_channel("+data[i]['streakID']+","+i+")' id='tr-campaign-"+i+"'></tr>").appendTo('table.defaultTable');
						jQuery.each( array_columns_choice, function( key, val ) {
							if(val != 'streakID')
							{
									if(val == 'stt')
									{
										$("<td>"+stt+"</td>").appendTo('table.defaultTable tr:last');
									}									 
									else if(val == 'name')
									{
										
										if(data[i]['alternateLink'] != null)
										{
											$("<td id='name_"+id_join_parent+"'><a href='"+data[i]['alternateLink']+"' target='_blank'>"+data[i][val]+"</a></td>").appendTo('table.defaultTable tr:last');
										}
										else
										{
											$("<td id='name_"+id_join_parent+"'><a>"+data[i][val]+"</a></td>").appendTo('table.defaultTable tr:last');
										}
									}									
									else if( val == "collected_1")
									{
										$("<td streakid='"+streakID+"' class='collected1-edit' data-type='text'  id='collected1_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
										
									}
									else if( val == "collected_2")
									{
										$("<td streakid='"+streakID+"' class='collected2-edit' data-type='text'  id='collected2_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
										
									}
									else if( val == "collected_3")
									{
										$("<td streakid='"+streakID+"' class='collected3-edit' data-type='text'  id='collected3_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
										
									}
									else if( val == "collected_4")
									{
										$("<td streakid='"+streakID+"' class='collected4-edit' data-type='text'  id='collected4_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
										
									}
									else if(val == "collected_date1")
									{
										$("<td streakid='"+streakID+"' class='collected-date1-edit' data-type='text' id='collected-date1_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
										
									}
									else if(val == "collected_date2")
									{
										$("<td streakid='"+streakID+"' class='collected-date2-edit' data-type='text' id='collected-date2_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');										
									}
									else if(val == "collected_date3")
									{
										$("<td streakid='"+streakID+"' class='collected-date3-edit' data-type='text' id='collected-date3_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
										
									}
									else if(val == "collected_date4")
									{
										$("<td streakid='"+streakID+"' class='collected-date4-edit' data-type='text' id='collected-date4_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
										
									}									
									else if(val == "vat")
									{
										$("<td class='vat-edit' data-type='text'  id='vat_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
										
									}
									else if(val == "dealsize")
									{
										
										$("<td class='dealsize-edit' data-type='text'  id='dealsize_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
										
									}
									else if(val == "remain")
									{
										$("<td data-type='text'  id='remain_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
										
									}
									else if(val == "name")
									{
										$("<td id='name_"+id_join_parent+"'>"+data[i][val]+"</td>").appendTo('table.defaultTable tr:last');
										
									}
									else if(val == 'client')
									{										
										$("<td><input id='"+data[i]['streakID']+data[i]['name']+"' type='text' value='"+data[i][val]+"' name='client' class='client'/></td>").appendTo('table.defaultTable tr:last');
									}
									else
									{							
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
								if(val == 'id_join' && val != 'id')
								{
									
								}								
							  	else
							  	{
								  	$('<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+data[i]['child'][j][val]+'</td>').appendTo('table.defaultTable tr:last');
							  	}
							});
						}
																	
					}
					
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
					
					
					$('#input-dealsize').val(array_dealsize);
					$('#input-vat').val(array_vat);
					$('#input-remain').val(array_remain);
					$('#input-collected1').val(array_collected1);
					$('#input-collected2').val(array_collected2);
					$('#input-collected3').val(array_collected3);
					$('#input-collected4').val(array_collected4);
					$('#input-collected-date1').val(array_collected_date1);
					$('#input-collected-date2').val(array_collected_date2);
					$('#input-collected-date3').val(array_collected_date3);
					$('#input-collected-date4').val(array_collected_date4);
					$("body").removeClass("loading");
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
	 array_columns_choice = getCookie('array_columns_choice_total');
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
			setCookie("array_columns_choice_total",array_columns_choice,7);
			setCookie("string_columns_choice_total",string_columns_choice,7);
			location.reload();
			
		}
		
 }




jQuery(document).ready(function(){
	var pathname = window.location.pathname;
	if(pathname.indexOf("total") != -1)
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
		setCookie("array_columns_choice_total",array_columns_choice,7);
		setCookie("string_columns_choice_total",string_columns_choice,7);
		
	});
}

function setCookie(cname,cvalue,exdays) {
							
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


