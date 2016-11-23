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
}


jQuery(document).ready(function(){
	var pathname = window.location.pathname;
	if(pathname.indexOf("add") == -1 && pathname.indexOf("total") == -1)
	{	
		streak.month();	
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

setSelectSenior();
function setSelectSenior()
{
	$(document).ready(function() { 
		var position = $('#position').val();
		if(position == 'normal')
		{	
			$('#senior').prop("disabled", false);
		}
		else
		{
								
			$('#senior').prop("disabled", true);
		}
	});
}

$(document).ready(function() {  
	
	$( "#position" ).change(function () {
		setSelectSenior();
	});
	 
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
	  	   
   });
});


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
				data: {'data_export': jsonString,'filename':'media_month'},
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
					window.location = '/streak_new/media_month.xls';					
				}
		  });
		  
				
	});
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
				url: "/streak_new/public/report/media/process-ajax-request",		
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
					
					
					$("<tr class='row-total'></tr>").appendTo('table.defaultTable');
					var total_actual_sold = 0;
					
					for(var i = 0;i < data.length;i++)
					{
						var stt = parseInt(i) + parseInt("1");
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
								if(val != 'streakID')
									{
										$('<td>'+data[i]['child'][j][val]+'</td>').appendTo('table.defaultTable tr:last');										
									
								  	}
								  	else
								  	{
									  	$('<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+data[i]['child'][j][val]+'</td>').appendTo('table.defaultTable tr:last');
								  	}
								
							});
							
						}
																		
					}
				
					$("body").removeClass("loading");
				},				
				complete: function(){
															
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


