<?php
	
function formatDate($date)
{
	$array_date = explode("/", $date);
	$date_format = $array_date[2]."-".$array_date[0]."-".$array_date[1];
	return $date_format;
}	

function filter_campaign_belong_month($filter_time,$streaks)
{
		
	    if($filter_time == "")
	     {
		     $thismonth =  date( "Y-m");
		     $filter_time = date( "Y-m", strtotime( "$thismonth - 1 month" ));
	     }
	     
	    $date_start_format = $filter_time."-"."01";
		$date_end_format = date("Y-m-t", strtotime($date_start_format));										
		$DayDiff2 = strtotime($date_end_format) - strtotime($date_start_format);
		$days_filter =  date('z', $DayDiff2);
	    
	    $result_final = array();
	    foreach($streaks as $streak){					    
			$date_start_log_format = formatDate($streak['start_date']);
			$date_end_log_format = formatDate($streak['end_date']);
										
			$DayDiff = strtotime($date_end_log_format) - strtotime($date_start_log_format);
			$days =  date('z', $DayDiff);
			$array_date_log = array();
						
			for($i = 0;$i < $days;$i++)
			{
				$array_date_log[] = date( "Y-m-d", strtotime( "$date_start_log_format + $i day" ));	
			}

						
			for($j = 0;$j < $days_filter;$j++)
			{
				$days_filter_day_by_day = date( "Y-m-d", strtotime( "$date_start_format + $j day" ));
				
				if(in_array($days_filter_day_by_day, $array_date_log))
				{
					$result_final[] = $streak;								
					break;
				}
			}

		}
	return $result_final;			
}