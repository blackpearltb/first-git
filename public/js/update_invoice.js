function update_invoice(id_join,value,date_filter,id_join_parent)
{
	var id_join_vnd = "invoice_"+id_join+"_vnd";
	id_join_parent2 = id_join_parent.split("_");
	id_join_parent2 = id_join_parent2[1];
	
	var total = 0;
	var total_vnd = 0;
	$.ajax({
		url: "/streak_new/public/report/ketoan/update-invoice-child",		
		type: 'POST',
		dataType: 'JSON',
		data: {'id_join': id_join,'value':value,'date_filter':date_filter},
		beforeSend : function() {																
		},
		success: function(result, status){	
	
		},
		complete: function(){
			
			var value_vnd = parseInt(value*22500).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
			
			console.log(value_vnd);
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
			
			total = Math.round(total * 1000)/1000;	
			total_vnd = parseInt(total * 22500).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");					
			$('#'+id_join_parent).html(total);
			
			total_vnd = total_vnd.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
			$('#'+id_join_parent+"_vnd").html(total_vnd);
			
			
			update_invoice_parent(id_join_parent2,total,date_filter);						
			
		}
	});
}

function update_invoice_parent(id_join_parent2,total,date_filter)
{
	$.ajax({
		url: "/streak_new/public/report/ketoan/update-invoice-parent",		
		type: 'POST',
		dataType: 'JSON',
		data: {'id_join_parent': id_join_parent2,'value':total,'date_filter':date_filter},
		beforeSend : function() {																
		},
		success: function(result, status){	
		
		},
		complete: function(){	
			total = Math.round(total * 1000)/1000;	
			total_vnd = parseInt(total * 22500).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
			$('#invoice_'+id_join_parent2+"_vnd").html(total_vnd);								
		}
	});
}




function check_and_send_mail(stt,id_join)
{
		
	var streakID = id_join.split('-');
	streakID = streakID[0];
	
	var array_collected1 = $('#input-collected1').val().split(',');	
	var array_collected2 = $('#input-collected2').val().split(',');	
	var array_collected3 = $('#input-collected3').val().split(',');		
	var array_collected4 = $('#input-collected4').val().split(',');
	var array_collected_date1 = $('#input-collected-date1').val().split(',');
	var array_collected_date2 = $('#input-collected-date2').val().split(',');
	var array_collected_date3 = $('#input-collected-date3').val().split(',');
	var array_collected_date4 = $('#input-collected-date4').val().split(',');
	var array_dealsize = $('#input-dealsize').val().split(',');
	var array_remain = $('#input-remain').val().split(',');
	var position = 0;
	
	
	for(var i = 0;i < array_collected1.length;i++)
	{
		collected1_element = array_collected1[i];	
		collected1_element = collected1_element.split('|');
		collected1_element2 = collected1_element[0];
		if(streakID == collected1_element2)
		{
			collected1 = collected1_element[1];
			position = i;
		}
	
	}
			
	var collected2 = array_collected2[position];
		collected2  = collected2.split('|');
		collected2 = collected2[1];
		
	var collected3 = array_collected3[position];
		collected3  = collected3.split('|');
		collected3 = collected3[1];	
		
	var collected4 = array_collected4[position];
		collected4  = collected4.split('|');
		collected4 = collected4[1];
	
	var collected_date1 = array_collected_date1[position];
		collected_date1  = collected_date1.split('|');
		collected_date1 = collected_date1[1];			
	
	var collected_date2 = array_collected_date2[position];
		collected_date2  = collected_date2.split('|');
		collected_date2 = collected_date2[1];
		
	var collected_date3 = array_collected_date3[position];
		collected_date3  = collected_date3.split('|');
		collected_date3 = collected_date3[1];
		
	var collected_date4 = array_collected_date4[position];
		collected_date4  = collected_date4.split('|');
		collected_date4 = collected_date4[1];
		
	var dealsize = array_dealsize[position];
		dealsize  = dealsize.split('|');
		dealsize = dealsize[1];	
		
	var remain = array_remain[position];
		remain  = remain.split('|');
		remain = remain[1];			

		
	var collected = $('#collected'+stt+'_'+id_join).html().replace(/\,/g,'');
	var collected_date = $('#collected-date'+stt+'_'+id_join).html().replace(/\,/g,'');
	if((collected != "" && collected != "0" && collected != "NaN" && collected != null && collected != "null") && (collected_date != "" && collected_date != "0" && collected_date != "NaN" && collected_date != null && collected_date != "null"))
	{
		var campaign = $('#name_'+id_join+' a').html();
		
		
		
		$("body").addClass("loading");	
		$.ajax({
			url: "/streak_new/public/report/ketoan/send-mail-collected",		
			type: 'POST',
			dataType: 'JSON',
			data: {'collected1': collected1,'collected2': collected2,'collected3': collected3,'collected4': collected4,'collected_date1': collected_date1,'collected_date2': collected_date2,'collected_date3': collected_date3,'collected_date4': collected_date4,'campaign':campaign,'id_join':id_join,'dealsize':dealsize,'remain':remain},
			beforeSend : function() {																
			},
			success: function(result, status){	
				$("body").removeClass("loading");
				
			},
			complete: function(){
				
												
			}
		});
		
		
	}	
	
}


function update_remain_total(id_join)
{
	
	var streakID = id_join.split('-');
	streakID = streakID[0];

	var array_vat = $('#input-vat').val().split(',');
	var array_dealsize = $('#input-dealsize').val().split(',');	
	var array_remain = $('#input-remain').val().split(',');	
	var array_collected1 = $('#input-collected1').val().split(',');	
	var array_collected2 = $('#input-collected2').val().split(',');	
	var array_collected3 = $('#input-collected3').val().split(',');		
	var array_collected4 = $('#input-collected4').val().split(',');
	var position = 0;
	var vat = 0;
	
	
	for(var i = 0;i < array_vat.length;i++)
	{
		vat_element = array_vat[i];	
		vat_element = vat_element.split('|');
		vat_element2 = vat_element[0];
		if(streakID == vat_element2)
		{
			vat = vat_element[1];
			position = i;
		}
	
	}
	
	var collected1 = array_collected1[position];
		collected1  = collected1.split('|');
		collected1 = collected1[1];
	
	var collected2 = array_collected2[position];
		collected2  = collected2.split('|');
		collected2 = collected2[1];
		
	var collected3 = array_collected3[position];
		collected3  = collected3.split('|');
		collected3 = collected3[1];	
		
	var collected4 = array_collected4[position];
		collected4  = collected4.split('|');
		collected4 = collected4[1];
	
	

	if(collected1 == 'null' || collected1 == "" || collected1 == "NaN" || collected1 == "Empty")
	{
		collected1 = 0;
	}
	if(collected2 == 'null' || collected2 == "" || collected2 == "NaN" || collected2 == "Empty")
	{
		collected2 = 0;
	}
	if(collected3 == 'null' || collected3 == "" || collected3 == "NaN" || collected3 == "Empty")
	{
		collected3 = 0;
	}
	if(collected4 == 'null' || collected4 == "" || collected4 == "NaN" || collected4 == "Empty")
	{
		collected4 = 0;
	}
	
	var all_collected = parseInt(collected1) + parseInt(collected2) + parseInt(collected3) + parseInt(collected4);	
	var dealsize = array_dealsize[position];
		dealsize  = dealsize.split('|');
		dealsize = dealsize[1];
	
	var total_rev = 0;
	
	if(vat == null || vat == "" || vat == "NaN" || vat == "Empty")
	{
		vat = 0;
	}
	
	if(vat == 0)
	{
		total_rev =  dealsize;
		
	}
	else
	{
		vat = parseInt(vat)/100;
		
		total_rev = (parseFloat(dealsize)*parseFloat(vat)) + parseFloat(dealsize);			
	}
	
	
	
	
	var remain = (parseInt(total_rev) - parseInt(all_collected)).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
	
	$.ajax({
		url: "/streak_new/public/report/ketoan/update-remain-total",		
		type: 'POST',
		dataType: 'JSON',
		data: {'id_join': id_join,'value':remain},
		beforeSend : function() {																
		},
		success: function(result, status){	
			var data = result.data;								
		},
		complete: function(){	
			$('#remain_'+id_join).html(remain);								
		}
	});	
		
	
}


function update_collected_new(id_join,total,stt)
{
	
	$.ajax({
		url: "/streak_new/public/report/ketoan/update-collected",		
		type: 'POST',
		dataType: 'JSON',
		data: {'id_join': id_join,'value':total,'stt':stt},
		beforeSend : function() {																
		},
		success: function(result, status){	
			
		},
		complete: function(){
			
			var streakID = id_join.split('-');
			streakID = streakID[0];
			
			
			var array_collected = $('#input-collected'+stt).val();
			array_collected = array_collected.split(",");
			var array_collected_new = [];
			for(var i = 0;i < array_collected.length;i++)
			{
				var id_collected = '';
				collected_element = array_collected[i];	
				collected_element = collected_element.split('|');
				collected_element = collected_element[0];
				if(streakID == collected_element)
				{
					
					id_collected = streakID+"|"+params_value_eval;
				}
				else
				{
					id_collected = array_collected[i];
				}
				
				array_collected_new.push(id_collected);
			}
			
			$('#input-collected'+stt).val(array_collected_new);
			
			
			//check_and_send_mail(stt,id_join);	
			update_remain_total(id_join);								
		}
	});
}

function update_collected_date(id_join,total,stt)
{
	
	$.ajax({
		url: "/streak_new/public/report/ketoan/update-collected-date",		
		type: 'POST',
		dataType: 'JSON',
		data: {'id_join': id_join,'value':total,'stt':stt},
		beforeSend : function() {																
		},
		success: function(result, status){	
				
		},
		complete: function(){	
			
			
			var streakID = id_join.split('-');
			streakID = streakID[0];
			
			
			var array_collected_date = $('#input-collected-date'+stt).val();
			array_collected_date = array_collected_date.split(",");
			var array_collected_date_new = [];
			for(var i = 0;i < array_collected_date.length;i++)
			{
				var id_collected = '';
				collected_element = array_collected_date[i];	
				collected_element = collected_element.split('|');
				collected_element = collected_element[0];
				if(streakID == collected_element)
				{
					
					id_collected = streakID+"|"+params_value_eval;
				}
				else
				{
					id_collected = array_collected_date[i];
				}
				
				array_collected_date_new.push(id_collected);
			}
			
			$('#input-collected-date'+stt).val(array_collected_date_new);
			
			//check_and_send_mail(stt,id_join);
		}
	});
}

/*
function update_collected(id_join,total,date_filter)
{
	$.ajax({
		url: "/streak_new/public/report/ketoan/update-collected",		
		type: 'POST',
		dataType: 'JSON',
		data: {'id_join': id_join,'value':total,'date_filter':date_filter},
		beforeSend : function() {																
		},
		success: function(result, status){	
														
		},
		complete: function(){	
			update_remain(id_join,date_filter);								
		}
	});
}
*/

function update_vat_new(id_join,params_value_eval)
{
	$.ajax({
		url: "/streak_new/public/report/ketoan/update-vat-total",		
		type: 'POST',
		dataType: 'JSON',
		data: {'id_join': id_join,'value':params_value_eval},
		beforeSend : function() {																
		},
		success: function(result, status){	
															
		},
		complete: function(){
			
			var streakID = id_join.split('-');
			streakID = streakID[0];
			
			
			var array_vat = $('#input-vat').val();
			array_vat = array_vat.split(",");
			var array_vat_new = [];
			for(var i = 0;i < array_vat.length;i++)
			{
				var id_vat = '';
				vat_element = array_vat[i];	
				vat_element = vat_element.split('|');
				vat_element = vat_element[0];
				if(streakID == vat_element)
				{
					
					id_vat = streakID+"|"+params_value_eval;
				}
				else
				{
					id_vat = array_vat[i];
				}
				
				array_vat_new.push(id_vat);
			}
			
			$('#input-vat').val(array_vat_new);
			
			update_remain_total(id_join);	
		}
	});
}

function update_vat(id_join,params_value_eval,date_filter)
{
	$.ajax({
		url: "/streak_new/public/report/ketoan/update-vat",		
		type: 'POST',
		dataType: 'JSON',
		data: {'id_join': id_join,'value':params_value_eval,'date_filter':date_filter},
		beforeSend : function() {																
		},
		success: function(result, status){	
															
		},
		complete: function(){
			
			var streakID = id_join.split('-');
			streakID = streakID[0];
			
			
			var array_vat = $('#input-vat').val();
			array_vat = array_vat.split(",");
			var array_vat_new = [];
			for(var i = 0;i < array_vat.length;i++)
			{
				var id_vat = '';
				vat_element = array_vat[i];	
				vat_element = vat_element.split('|');
				vat_element = vat_element[0];
				if(streakID == vat_element)
				{
					
					id_vat = streakID+"|"+params_value_eval;
				}
				else
				{
					id_vat = array_vat[i];
				}
				
				array_vat_new.push(id_vat);
			}
			
			$('#input-vat').val(array_vat_new);
		
			update_total_rev(id_join,date_filter);		
		}
	});
}

function update_dealsize(id_join,params_value_eval,date_filter)
{
	$.ajax({
		url: "/streak_new/public/report/ketoan/update-dealsize",		
		type: 'POST',
		dataType: 'JSON',
		data: {'id_join': id_join,'value':params_value_eval,'date_filter':date_filter},
		beforeSend : function() {																
		},
		success: function(result, status){	
															
		},
		complete: function(){

			var streakID = id_join.split('-');
			streakID = streakID[0];
			
			
			var array_dealsize = $('#input-dealsize').val();
			array_dealsize = array_dealsize.split(",");
			var array_dealsize_new = [];
			for(var i = 0;i < array_dealsize.length;i++)
			{
				var id_vat = '';
				dealsize_element = array_dealsize[i];	
				dealsize_element = dealsize_element.split('|');
				dealsize_element = dealsize_element[0];
				if(streakID == dealsize_element)
				{
					
					id_dealsize = streakID+"|"+params_value_eval;
				}
				else
				{
					id_dealsize = array_dealsize[i];
				}
				
				array_dealsize_new.push(id_dealsize);
			}
			
			$('#input-dealsize').val(array_dealsize_new);

			update_total_rev(id_join,date_filter);		
		}
	});
}


function update_total_rev(id_join,date_filter)
{
	
	var streakID = id_join.split('-');
	streakID = streakID[0];

	var array_vat = $('#input-vat').val().split(',');
	var array_dealsize = $('#input-dealsize').val().split(',');	
	var position = 0;
	var vat = 0;
	
	
	for(var i = 0;i < array_vat.length;i++)
	{
		vat_element = array_vat[i];	
		vat_element = vat_element.split('|');
		vat_element2 = vat_element[0];
		if(streakID == vat_element2)
		{
			vat = vat_element[1];
			position = i;
		}
	
	}

	var dealsize = array_dealsize[position];
		dealsize  = dealsize.split('|');
		dealsize = dealsize[1];
	
	console.log(vat);
	console.log(dealsize);
	
	if(vat == null || vat == "" || vat == "NaN" || vat == "Empty")
	{
		vat = 0;
	}
	
	if(vat == 0)
	{
		total_rev =  dealsize;
		
	}
	else
	{
		vat = parseInt(vat)/100;		
		total_rev = (parseFloat(dealsize)*parseFloat(vat)) + parseFloat(dealsize);	
		
	}
	total_rev = total_rev.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
	$('#total-rev_'+id_join).html(total_rev);
	
	$.ajax({
		url: "/streak_new/public/report/ketoan/update-total-rev",		
		type: 'POST',
		dataType: 'JSON',
		data: {'id_join': id_join,'value':total_rev,'date_filter':date_filter},
		beforeSend : function() {																
		},
		success: function(result, status){												
		},
		complete: function(){	
			
			//update_remain(id_join,date_filter);								
		}
	});
	
	
}
