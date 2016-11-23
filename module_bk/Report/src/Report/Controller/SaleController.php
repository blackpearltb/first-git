<?php
namespace Report\Controller;

use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use PHPExcel;
use Report\Model\Users;
use Report\Model\UsersTable;
use Report\Form\UsersForm;


class SaleController extends BackEndController
{

		
	
	public function indexAction()
    {
	    if($_SESSION['REPORTMEMBER']['type'] != 'sale'){            
            header("Location: /streak_new/public/report/index");
            exit();
        }	   	    	   
		 return new ViewModel(array(                       
         ));          
    }
	
    public function monthAction()
    {
	    if($_SESSION['REPORTMEMBER']['type'] != 'sale'){            
            header("Location: streak_new/public/report/index");
            exit();
        }	    	    	   
		$months = $this->getModelTable('KetoanTable')->getMonths();
		
	    return new ViewModel(array(
           
             'months' => $months,
         ));  
        
    }
    
	public function checkcampaignAction()
	{
		if($_SESSION['REPORTMEMBER']['type'] != 'sale'){            
            header("Location: streak_new/public/report/index");
            exit();
        }
        
        $this->layout('layout/layout_checkcampaign');
        
        $campaigns = $this->getModelTable('KetoanTable')->getCampaigns(0);
        
        $request = $this->getRequest();
	    if($request->isPost()){
		    $data = $request->getPost();
		    $result = array();
		 	$input_filter = $data['input_filter'];
		    $input_filter = json_decode($input_filter);   
		    $campaigns = $this->getModelTable('KetoanTable')->getCampaigns($input_filter);
		    
		    $result['data'] = $campaigns;
			die(json_encode($result));		    
		}    
        else
        {
	        return new ViewModel(array(   
		        'campaigns' => $campaigns,                    
	         ));
        }
	}
	
    
    public function getDataSeniorsAction()
    {
	    if($_SESSION['REPORTMEMBER']['type'] != 'sale' || $_SESSION['REPORTMEMBER']['position'] != 'admin'){            
            header("Location: /streak_new/public/report/index");
            exit();
        }   
	    
	    
	   	$request = $this->getRequest();
	    if($request->isPost()){
		    $result = array();
		    $array_total = array();
		    $data = $request->getPost();		        			
		    $filter_time = $data['filter_time'];
		    if($filter_time == "")
		    {
			     $thismonth =  date( "Y-m");
			     $filter_time = date( "Y-m", strtotime( "$thismonth - 1 month" ));
		    }
		    
		 	$seniors = $this->getModelTable('UsersTable')->getSeniors("sale");   
		 	
		 	foreach($seniors as $senior)
		 	{
			 	$senior_email = $senior['email'];
			 	$array_email = array();
			 	$array_email[] = $senior_email;
			 	$users_child = $this->getModelTable('UsersTable')->getUsersTeam($senior['id']);
			 	foreach($users_child as $user_child)
				{
					$array_email[] = $user_child['email'];
				}
				
				$streaks = $this->getModelTable('KetoanTable')->getAll($filter_time,"","",$array_email);
				$streaks = filter_campaign_belong_month($filter_time,$streaks);
				$total_actual_sold = 0;
				
				foreach($streaks as $streak)
				{
					$total_actual_sold = $total_actual_sold + str_replace(",","",$streak['actual_sold_value_vnd']);
				}
				
				$array_total[$senior_email]['id_senior'] = $senior['id'];
				$array_total[$senior_email]['total_campaigns'] = count($streaks);
				$array_total[$senior_email]['total_actual_sold'] = number_format($total_actual_sold);
		 	}
		 	
		 	$result['data'] = $array_total;
		 	
		 	die(json_encode($result));
		 	
		}
    }
    
    public function getInfoCommissionSeniorsSaleAction()
    {
	    if($_SESSION['REPORTMEMBER']['type'] != 'sale' || $_SESSION['REPORTMEMBER']['position'] != 'admin'){            
            header("Location: /streak_new/public/report/index");
            exit();
        }   
	    
	    
	   	$request = $this->getRequest();
	    if($request->isPost()){
		    $result = array();
		    $array_total = array();
		    $data = $request->getPost();		        			
		    $filter_time = $data['filter_time'];	
		    
		    
		    $streaks = $this->getModelTable('KetoanTable')->getMonthComissionMedia('',$filter_time,'sale');
			$streaks = filter_campaign_belong_month($filter_time,$streaks);	    
		 	$seniors = $this->getModelTable('UsersTable')->getSeniors("sale");   
		 	
		 	foreach($seniors as $senior)
		 	{
			 	$senior_email = trim($senior['email']);
			 	$array_email = array();
			 	$array_email[] = $senior_email;
			 	$email = trim($senior['email']);
			 	$users_child = $this->getModelTable('UsersTable')->getUsersTeam($senior['id']);
			 	foreach($users_child as $user_child)
				{
					$array_email[] = $user_child['email'];
				}
				
					$gp_actual_total = 0;
					$commission_total = 0;
					$total_actual_sold = 0;
					$total_gp = 0;
					$number_count = 0;
					foreach($streaks as $streak)
					{
						
						if(in_array(trim($streak['sale']), $array_email))
							{
								
								if($streak['actual_gp'] != "")
								{
									$gp_actual_total = $gp_actual_total +  str_replace("%","",$streak['actual_gp']);								
									//$total_rev = $total_rev +  str_replace(",","",$streak['total_rev']);
									$total_gp = $total_gp +  str_replace(",","",$streak['actual_profit']);																								
									$streaks_last_month = $this->getModelTable('KetoanTable')->getActualLastMonth($streak['streakID'],$filter_time);
									$streak['actual_sold_value_vnd'] = str_replace(",","",$streak['actual_sold_value_vnd']);						   
							        $streak['lobby'] = str_replace(",","",$streak['lobby']);
									if(count($streaks_last_month) > 0){						       
	 					        	
										$actual_sold_value_vnd_last_month = $streaks_last_month[0]['actual_sold_value_vnd'];				        	
										$actual_sold_value_vnd_last_month = str_replace(",","",$actual_sold_value_vnd_last_month);							  				        
								        $streak['actual_sold_value_vnd'] = $streak['actual_sold_value_vnd'] - $actual_sold_value_vnd_last_month;
								        
								        $lobby_last_month = $streaks_last_month[0]['lobby'];				        	
										$lobby_last_month = str_replace(",","",$lobby_last_month);							  				        
								        $streak['lobby'] = $streak['lobby'] - $lobby_last_month;
								       
										$streak['actual_sold_value_vnd_after_lobby'] = 	$streak['actual_sold_value_vnd'] - $streak['lobby'];																	$total_actual_sold = $total_actual_sold + $streak['actual_sold_value_vnd_after_lobby'];
										if(str_replace("%","",$streak['gp_plan']) >= 20)
										{
											
											$commission_total = $commission_total +  ($streak['actual_sold_value_vnd_after_lobby'] / 100 * 2);
										}
										else
										{
											$commission_total = $commission_total + 0;
										}
								       
									}								
									$number_count++;
									
								}														
							}
						
					}
					$array_result[$email]['id_senior'] = $senior['id'];
					$array_result[$email]['total_actual_sold_after_lobby'] = number_format($total_actual_sold);
					$array_result[$email]['gp_actual_total'] = number_format($total_gp);
					$array_result[$email]['average_actual_total'] = round($gp_actual_total/$number_count, 2)."%";	
					$array_result[$email]['commission_total'] = number_format($commission_total);
				
		 	}
		 	
		 	$result['data'] = $array_result;
		 	
		 	die(json_encode($result));
		 	
		}
    }
    
    public function getInfoCommissionTeamGroupAction()
    {
	    if($_SESSION['REPORTMEMBER']['type'] != 'sale' || $_SESSION['REPORTMEMBER']['position'] != 'admin'){            
            header("Location: /streak_new/public/report/index");
            exit();
        }   
	    
	    
	   	$request = $this->getRequest();
	    if($request->isPost()){
		    $result = array();
		    $array_total = array();
		    $data = $request->getPost();		        			
		    $filter_time = $data['filter_time'];	
		    $id_senior = $data['id_senior'];
		    
		    
		    $streaks = $this->getModelTable('KetoanTable')->getMonthComissionMedia('',$filter_time,'sale');
			$streaks = filter_campaign_belong_month($filter_time,$streaks);	    
			
		 	$array_email = array();			 	
			 
			 	$users_child = $this->getModelTable('UsersTable')->getUsersTeam($id_senior);
			 	foreach($users_child as $user_child)
				{
					$array_email[] = $user_child['email'];
				}   
		 	
		 	foreach($array_email as $email)
		 	{
			 				 						
					$gp_actual_total = 0;
					$commission_total = 0;
					$total_actual_sold = 0;
					$total_gp = 0;
					$number_count = 0;
					foreach($streaks as $streak)
					{
						
						if(trim($streak['sale']) ==  trim($email))
							{
								
								if($streak['actual_gp'] != "")
								{
									$gp_actual_total = $gp_actual_total +  str_replace("%","",$streak['actual_gp']);								
									//$total_rev = $total_rev +  str_replace(",","",$streak['total_rev']);
									$total_gp = $total_gp +  str_replace(",","",$streak['actual_profit']);																								
									$streaks_last_month = $this->getModelTable('KetoanTable')->getActualLastMonth($streak['streakID'],$filter_time);
									$streak['actual_sold_value_vnd'] = str_replace(",","",$streak['actual_sold_value_vnd']);						   
							        $streak['lobby'] = str_replace(",","",$streak['lobby']);
									if(count($streaks_last_month) > 0){						       
	 					        	
										$actual_sold_value_vnd_last_month = $streaks_last_month[0]['actual_sold_value_vnd'];				        	
										$actual_sold_value_vnd_last_month = str_replace(",","",$actual_sold_value_vnd_last_month);							  				        
								        $streak['actual_sold_value_vnd'] = $streak['actual_sold_value_vnd'] - $actual_sold_value_vnd_last_month;
								        
								        $lobby_last_month = $streaks_last_month[0]['lobby'];				        	
										$lobby_last_month = str_replace(",","",$lobby_last_month);							  				        
								        $streak['lobby'] = $streak['lobby'] - $lobby_last_month;
								       
										$streak['actual_sold_value_vnd_after_lobby'] = 	$streak['actual_sold_value_vnd'] - $streak['lobby'];																	$total_actual_sold = $total_actual_sold + $streak['actual_sold_value_vnd_after_lobby'];
										if(str_replace("%","",$streak['gp_plan']) >= 20)
										{
											
											$commission_total = $commission_total +  ($streak['actual_sold_value_vnd_after_lobby'] / 100 * 2);
										}
										else
										{
											$commission_total = $commission_total + 0;
										}
								       
									}								
									$number_count++;
									
								}														
							}
						
					}
					$array_result[$email]['total_actual_sold_after_lobby'] = number_format($total_actual_sold);
					$array_result[$email]['gp_actual_total'] = number_format($total_gp);
					$array_result[$email]['average_actual_total'] = round($gp_actual_total/$number_count, 2)."%";	
					$array_result[$email]['commission_total'] = number_format($commission_total);
				
		 	}
		 	
		 	$result['data'] = $array_result;
		 	
		 	die(json_encode($result));
		 	
		}
    }
    
    
    
    public function totalCommissionSaleAction()
    {
	  
	     if($_SESSION['REPORTMEMBER']['type'] != 'sale'){            
            header("Location: /streak_new/public/report/index");
            exit();
        }   
	    $this->layout('layout/layout_total_commission_sale_type_sale');
	    $months = $this->getModelTable('KetoanTable')->getMonths();
	    
	   	$request = $this->getRequest();
		   
	    if($request->isPost()){
		    
		    $data = $request->getPost();
		    $result = array();
		    $array_streaks = array();
		    $array_test = array();
		    
			
		    $filter_time = $data['filter_time'];
		    	
		    $today =  date( "Y-m-d");
			$lastday = date( "Y-m-d", strtotime( "$today - 1 day" ));	  
			$streakfiles = $this->getModelTable('FileStreakTable')->getFiles();  
			
				//$date_filter_last_month = date( "Y-m", strtotime( "$filter_time - 1 month" ));
				$id_user = $_SESSION['REPORTMEMBER']['id'];	
			    $parent_id = $_SESSION['REPORTMEMBER']['parent_id'];
				   if($parent_id != 0)
				   {
					   $array_email = array();
					   $array_email[] = $_SESSION['REPORTMEMBER']['email'];
					   
					   $users_child = $this->getModelTable('UsersTable')->getUsersTeam($id_user);
					   	 
					   foreach($users_child as $user_child)
					   {
						   $array_email[] = $user_child['email'];
					   }
					   
					   $streaks = $this->getModelTable('KetoanTable')->getMonthComissionMedia($array_email,$filter_time,'sale');
				   }
				   else
				   {
					   $array_email = array();
					   $array_email[] = $data['email'];					  
					   $streaks = $this->getModelTable('KetoanTable')->getMonthComissionMedia($array_email,$filter_time,'sale');
					   					   
				   }
			    
			   $streaks = filter_campaign_belong_month($filter_time,$streaks);
			    
			    foreach($streaks as $streak){
				    
								
								
								$commmision_status = $this->getModelTable('CommissionStatusSaleTable')->checkCommission($streak['streakID'],$filter_time);
								if(count($commmision_status) > 0)
								{
									$streak['status'] = $commmision_status[0]['status'];
								}
								else
								{
									$streak['status'] = "None";
								}
								
								$streaks_last_month = $this->getModelTable('KetoanTable')->getActualLastMonth($streak['streakID'],$filter_time);					   
						        $streak['actual_sold_value_vnd'] = str_replace(",","",$streak['actual_sold_value_vnd']);						   
						        $streak['lobby'] = str_replace(",","",$streak['lobby']);						        
						        if(count($streaks_last_month) > 0){						       
 					        	
									$actual_sold_value_vnd_last_month = $streaks_last_month[0]['actual_sold_value_vnd'];				        	
									$actual_sold_value_vnd_last_month = str_replace(",","",$actual_sold_value_vnd_last_month);							  				        
							        $streak['actual_sold_value_vnd'] = $streak['actual_sold_value_vnd'] - $actual_sold_value_vnd_last_month;
							        
							        $lobby_last_month = $streaks_last_month[0]['lobby'];				        	
									$lobby_last_month = str_replace(",","",$lobby_last_month);							  				        
							        $streak['lobby'] = $streak['lobby'] - $lobby_last_month;
							       
								}
								
									
								$streak['actual_sold_value_vnd_after_lobby'] = 	$streak['actual_sold_value_vnd'] - $streak['lobby'];														$streak['actual_sold_value_vnd'] = number_format($streak['actual_sold_value_vnd']);
								$streak['lobby'] = number_format($streak['lobby']);
								
								if(str_replace("%","",$streak['gp_plan']) >= 20)
								{
									
									$streak['total_commission'] = number_format($streak['actual_sold_value_vnd_after_lobby'] / 100 * 2);
								}
								else
								{
									$streak['total_commission'] = 0;
								}
								
								$streak['actual_sold_value_vnd_after_lobby'] = number_format($streak['actual_sold_value_vnd_after_lobby']);
																												    		     			
							    $array_streaks[] = $streak;
							
			    }
			
		    $result['data'] = $array_streaks;   
		    
		     
			die(json_encode($result));
		}
		else
		{
			
			return new ViewModel(array( 
				'months' => $months
	            
	         ));
			
		} 

        
    }
    
    
    
    	   
    public function processAjaxRequestAction(){
	   

	    $result = array('status' => 'error', 'message' => 'There was some error. Try again.');
	    
	    $request = $this->getRequest();
	    
	    if($request->isPost()){
	    	
	        	$data = $request->getPost();	        				
				$id_senior = $data['id_senior'];
		        $date_filter = $data['date_filter'];
		        $input_filter = $data['input_filter'];
		        $input_filter = json_decode($input_filter);
		        $sortaction = $data['sortaction'];
		        
		        if($date_filter == "")
		        {
			    	$current_month = date('Y-m');
					$date_filter = date( "Y-m", strtotime( "$current_month - 1 month" ));    
		        }		        
		        //$date_filter_last_month = date( "Y-m", strtotime( "$date_filter - 1 month" ));		        
		        
		        $id_user = $_SESSION['REPORTMEMBER']['id'];	
			    $parent_id = $_SESSION['REPORTMEMBER']['parent_id'];
			    
			   if($parent_id != 0)
			   {
				   $array_email = array();
				   $array_email[] = $_SESSION['REPORTMEMBER']['email'];
				   
				   $users_child = $this->getModelTable('UsersTable')->getUsersTeam($id_user);
				   	 
				   foreach($users_child as $user_child)
				   {
					   $array_email[] = $user_child['email'];
				   }
				   $streaks = $this->getModelTable('KetoanTable')->getAll($date_filter,$input_filter,$sortaction,$array_email);
			   }
			   else
			   {
				   if($id_senior == "")
				   {
				   		$streaks = $this->getModelTable('KetoanTable')->getAll($date_filter,$input_filter,$sortaction);
				   }
				   else
				   {
					   $array_email = array();
					   $email_senior = $this->getModelTable('UsersTable')->getEmailLeader($id_senior);
					   $array_email[] = trim($email_senior[0]['email']);
					   
					   $users_child = $this->getModelTable('UsersTable')->getUsersTeam($id_senior);
					   	 
					   foreach($users_child as $user_child)
					   {
						   $array_email[] = $user_child['email'];
					   }
					   
					   $streaks = $this->getModelTable('KetoanTable')->getAll($date_filter,$input_filter,$sortaction,$array_email);
				   }
			   }
		        
		        	
				$streakfiles = $this->getModelTable('FileStreakTable')->getFiles();
		        $array_streaks = array();	
		        $array_test = array();	    
		        $array_test[] = $array_email;
		       
		            
		        foreach($streaks as $streak)
		        {
			        

			        $date_start_log_format = formatDate($streak['start_date']);
					$date_end_log_format = formatDate($streak['end_date']);
					
									
					$DayDiff = strtotime($date_end_log_format) - strtotime($date_start_log_format);
					$days =  date('z', $DayDiff);
					$array_date_log = array();
					
					for($i = 0;$i < $days;$i++)
					{
						$array_date_log[] = date( "Y-m-d", strtotime( "$date_start_log_format + $i day" ));	
					}
					
					$date_start_format = $date_filter."-"."01";
					$date_end_format = date("Y-m-t", strtotime($date_start_format));
					
			
					$DayDiff2 = strtotime($date_end_format) - strtotime($date_start_format);
					$days_filter =  date('z', $DayDiff2);
					
					for($j = 0;$j < $days_filter;$j++)
					{
						$days_filter_day_by_day = date( "Y-m-d", strtotime( "$date_start_format + $j day" ));
						
						if(in_array($days_filter_day_by_day, $array_date_log))
						{	
													
							$array_streaks_child = array();
					        $streaks_last_month = $this->getModelTable('KetoanTable')->getActualLastMonth($streak['streakID'],$date_filter);
					        
							$streak['actual_sold_value_vnd'] = str_replace(",","",$streak['actual_sold_value_vnd']);
					        $actual_sold_value_vnd_last_month = $streaks_last_month[0]['actual_sold_value_vnd'];				        	
							$actual_sold_value_vnd_last_month = str_replace(",","",$actual_sold_value_vnd_last_month);							        			        
						    $streak['actual_sold_value_vnd'] = number_format($streak['actual_sold_value_vnd'] - $actual_sold_value_vnd_last_month);
					        
					        						   
					        $streak['actual_cost_usd'] = str_replace(",","",$streak['actual_cost_usd']);	
					        $actual_cost_usd_last_month = $streaks_last_month[0]['actual_cost_usd'];				        							        
					        $actual_cost_usd_last_month = str_replace(",","",$actual_cost_usd_last_month);			        						        	
					        $streak['actual_cost_usd'] = number_format($streak['actual_cost_usd'] - $actual_cost_usd_last_month);
					        
					        					   
					        $streak['actual_cost_vnd'] = str_replace(",","",$streak['actual_cost_vnd']);
					         $actual_cost_vnd_last_month = $streaks_last_month[1]['actual_cost_vnd'];				        	
					        	$actual_cost_vnd_last_month = str_replace(",","",$actual_cost_vnd_last_month);			        						        	
					        	$streak['actual_cost_vnd'] = number_format($streak['actual_cost_vnd'] - $actual_cost_vnd_last_month);
					        	
					        	
					        $streak['actual_profit'] = str_replace(",","",$streak['actual_profit']);
					        $actual_profit_last_month = $streaks_last_month[0]['actual_profit'];				        	
					        	$actual_profit_last_month = str_replace(",","",$actual_profit_last_month);			        						        	
					        	$streak['actual_profit'] = number_format($streak['actual_profit'] - $actual_profit_last_month);
					        	
					        						        
					        $streak['achieved_kpi'] = str_replace(",","",$streak['achieved_kpi']);
					        $achieved_kpi_last_month = $streaks_last_month[0]['achieved_kpi'];				        	
					        	$achieved_kpi_last_month = str_replace(",","",$achieved_kpi_last_month);			        						        	
					        	$streak['achieved_kpi'] = number_format($streak['achieved_kpi'] - $achieved_kpi_last_month);
					        
					        $streak['lobby'] = str_replace(",","",$streak['lobby']);	
					        $lobby_last_month = $streaks_last_month[0]['lobby'];				        	
					        	$lobby_last_month = str_replace(",","",$lobby_last_month);			        						        	
					        	$streak['lobby'] = number_format($streak['lobby'] - $lobby_last_month);
					        	
					        				        
					        $streak['entertainment'] = str_replace(",","",$streak['entertainment']);
					        $entertainment_last_month = $streaks_last_month[0]['entertainment'];				        	
					        	$entertainment_last_month = str_replace(",","",$entertainment_last_month);			        						        	
					        	$streak['entertainment'] = $streak['entertainment'] - $entertainment_last_month;
					        
					        $channels = $this->getModelTable('KetoanTable')->getChannel($streak['streakID'],$date_filter);
					        $channel = $channels[0]['channel'];

							$invoice_value = $channels[0]['invoice_value'];
							$percent = ($actual_sold_usd - $invoice_value)/$invoice_value;
							$streak['percent'] = round($percent);
							
							
							$key_match_client = md5($streak['streakID'].$streak['name']);
							
							$name_client = $this->getModelTable('ClientMatchCampaignTable')->getClient($key_match_client);								
							$streak['client'] = $name_client['name'];							
							
							$key_match_supplier = md5($streak['streakID'].$streak['channel']);
							$name_supplier = $this->getModelTable('SupplierMatchCampaignTable')->getSupplier($key_match_supplier);
							$streak['supplier'] = $name_supplier['name'];
														
					        
					        $streak['child'] = $this->getModelTable('KetoanChildTable')->getStreakChannel($streak['streakID'],$date_filter);
							$streak['total_rev'] = number_format($streak['total_rev']);
					       
					        foreach($streak['child'] as $streakChild)
						    {
							    $streaks_child_last_month = $this->getModelTable('KetoanChildTable')->getActualLastMonth_Child($streakChild['id_join'],$date_filter);
							    
							    
									$streaks_child_last_month = $this->getModelTable('KetoanChildTable')->getActualLastMonth_Child($streakChild['id_join'],$date_filter);
							    $streakChild['actual_sold_value_vnd'] = str_replace(",","",$streakChild['actual_sold_value_vnd']);
							    $child_actual_sold_value_vnd_last_month = $streaks_child_last_month[0]['actual_sold_value_vnd'];
								$child_actual_sold_value_vnd_last_month = str_replace(",","",$child_actual_sold_value_vnd_last_month);								
								$streakChild['actual_sold_value_vnd'] = $streakChild['actual_sold_value_vnd'] - $child_actual_sold_value_vnd_last_month;
							    
							    $streakChild['actual_cost_usd'] = str_replace(",","",$streakChild['actual_cost_usd']);
							    $child_actual_cost_usd_last_month = $streaks_child_last_month[0]['actual_cost_usd'];
								$child_actual_cost_usd_last_month = str_replace(",","",$child_actual_cost_usd_last_month);										
								$streakChild['actual_cost_usd'] = $streakChild['actual_cost_usd'] - $child_actual_cost_usd_last_month;
							    
							    $streakChild['actual_cost_vnd'] = str_replace(",","",$streakChild['actual_cost_vnd']);
							    $child_actual_cost_vnd_last_month = $streaks_child_last_month[0]['actual_cost_vnd'];
								$child_actual_cost_vnd_last_month = str_replace(",","",$child_actual_cost_vnd_last_month);	
								$streakChild['actual_cost_vnd'] = $streakChild['actual_cost_vnd'] - $child_actual_cost_vnd_last_month;
							    
							    
							    $streakChild['actual_profit'] = str_replace(",","",$streakChild['actual_profit']);
							    $child_actual_profit_last_month = $streaks_child_last_month[0]['actual_profit'];
								$child_actual_profit_last_month = str_replace(",","",$child_actual_profit_last_month);				
								$streakChild['actual_profit'] = $streakChild['actual_profit'] - $child_actual_profit_last_month;
							    
							    
							    $streakChild['achieved_kpi'] = str_replace(",","",$streakChild['achieved_kpi']);
							    $child_achieved_kpi_last_month = $streaks_child_last_month[0]['achieved_kpi'];
								$child_achieved_kpi_last_month = str_replace(",","",$child_achieved_kpi_last_month);
								$streakChild['achieved_kpi'] = $streakChild['achieved_kpi'] - $child_achieved_kpi_last_month;
							    
							    $streakChild['lobby'] = str_replace(",","",$streakChild['lobby']);
							    $child_lobby_last_month = $streaks_child_last_month[0]['lobby'];
								$child_lobby_last_month = str_replace(",","",$child_lobby_last_month);
								$streakChild['lobby'] = $streakChild['lobby'] - $child_lobby_last_month;
							    
							    $streakChild['entertainment'] = str_replace(",","",$streakChild['entertainment']);
							    $child_entertainment_last_month = $streaks_child_last_month[0]['entertainment'];
								$child_entertainment_last_month = str_replace(",","",$child_entertainment_last_month);		
								$streakChild['entertainment'] = $streakChild['entertainment'] - $child_entertainment_last_month;
							   
							    $streakChild['client'] = "";
							   							    							 							   
							    
							    $key_match_supplier_child = md5($streakChild['streakID'].$streakChild['channel']);
							    
								$name_supplier_child = $this->getModelTable('SupplierMatchCampaignTable')->getSupplier($key_match_supplier_child);						
								$streakChild['supplier']  = $name_supplier_child['name'];
								
							    
							    
							    $array_streaks_child[] = $streakChild;
							    
							    
						    }
						    
					        $streak['child'] = $array_streaks_child;			        
					        $array_streaks[] = $streak;
					        
					        break;
						}
												
					}	
					
     
		        }
		        				
		        $result['data'] = $array_streaks;	        	
	       
			
	    }	    
	    die(json_encode($result));
	}
    
    public function totalAction()
    {	
	    if($_SESSION['REPORTMEMBER']['type'] != 'sale'){            
            header("Location: /streak_new/public/report/index");
            exit();
        }	    
	    $this->layout('layout/layout_total');
	    
	   	 $request = $this->getRequest();
	   	 
	
	    if($request->isPost()){
		    $data = $request->getPost();
		    $input_filter = $data['input_filter'];
		    $input_filter = json_decode($input_filter);
		    $sortaction = $data['sortaction'];
		    		    
		    $result = array();
		    $array_streaks = array();
		    
		    $today =  date( "Y-m-d");
			$lastday = date( "Y-m-d", strtotime( "$today - 1 day" ));
		    
		    $id_user = $_SESSION['REPORTMEMBER']['id'];	
			    $parent_id = $_SESSION['REPORTMEMBER']['parent_id'];
			   if($parent_id != 0)
			   {
				   $array_email = array();
				   $array_email[] = $_SESSION['REPORTMEMBER']['email'];
				   
				   $users_child = $this->getModelTable('UsersTable')->getUsersTeam($id_user);
				   	 
				   foreach($users_child as $user_child)
				   {
					   $array_email[] = $user_child['email'];
				   }
				   $streaks = $this->getModelTable('TotalTable')->getAll($lastday,$input_filter,$sortaction,$array_email);				   
			   }
			   else
			   {
				   $streaks = $this->getModelTable('TotalTable')->getAll($lastday,$input_filter,$sortaction);
				   
			   }

		    $streakfiles = $this->getModelTable('FileStreakTable')->getFiles();
		    
		    foreach($streaks as $streak){
			   			    
			   
				$invoice_parent = 	$this->getModelTable('KetoanTable')->getInvoiceParent($streak['streakID']);
				$count_invoice_parent = 0;
				foreach($invoice_parent as $invoice_value)
				{
					
					if($invoice_value['invoice_value'] != "")
					{
						$invoice_temp = str_replace(",","",$invoice_value['invoice_value']);
						$count_invoice_parent = $count_invoice_parent + $invoice_temp; 
					}
										
				}				
				$streak['invoice']  = $count_invoice_parent;
				$streak['collected1'] = number_format($streak['collected1']);
					$streak['collected2'] = number_format($streak['collected2']);
					$streak['collected3'] = number_format($streak['collected3']);
					$streak['collected4'] = number_format($streak['collected4']);								
			    $streak['child'] = $this->getModelTable('TotalChildTable')->getStreakChannel($streak['streakID'],$lastday);
			    $array_streaks_child = array();
			    foreach($streak['child'] as $streakChild)
				{
					$count_invoice_child = 0;
					$invoice_child = $this->getModelTable('KetoanChildTable')->getInvoiceChild($streakChild['id_join']);
					foreach($invoice_child as $invoice_child_value)
					{	
										
						if($invoice_child_value['invoice_value'] != "")
						{
							$invoice_child_temp = str_replace(",","",$invoice_child_value['invoice_value']);
							$count_invoice_child = $count_invoice_child + $invoice_child_temp; 
						}
												
					}
					
					$streakChild['invoice'] = $count_invoice_child;
					$array_streaks_child[] = $streakChild;
				}
			    $streak['child'] = $array_streaks_child;			     
			    $array_streaks[] = $streak;
			    
		    }
		    $result['data'] = $array_streaks;   
		    
		     
			die(json_encode($result));
		}
		else
		{
			
			return new ViewModel();
		}
  
    }

    
    public function addSaleAction()
    {
	    $result = "";
		$error = 2;
	  	$form = new UsersForm();
	  	$users = array();
	  	
	  	$users_temp = $this->getModelTable('UsersTable')->getUsers("sale");
	  	
	  	foreach($users_temp as $user)
	  	{
		  	$leader = $this->getModelTable('UsersTable')->getLeader($user['parent_id']);
		  	if($leader['fullname'])
		  	{
		  		$user['leader'] = $leader['fullname'];
		  	}
		  	else
		  	{
			  	$user['leader'] = "";
		  	}
		  	
		  	$users[] = $user;
		  	
	  	}
	  	$seniors = $this->getModelTable('UsersTable')->getSeniors("sale");
			  $options = array();
		        if (count($seniors)) {
		            foreach ($seniors as $senior) {
		                $options[] = array(
		                    'value' => $senior['id'],
		                    'label' => $senior['fullname'],
		                );
		            }
		        } else {
		            return $this->redirect()->toRoute('report/sale', array('action' => 'add-sale'));
		        }
		        $form->get('senior')->setOptions(array(
		            'options' => $options
		        ));
	  	
	  	$request = $this->getRequest();
       if($request->isPost()){
			$position = trim($request->getPost('position'));
			$senior = trim($request->getPost('senior'));
			$email =  trim($request->getPost('email'));
			$fullname =  trim($request->getPost('fullname'));
			$password=  trim($request->getPost('password'));
			$confirmpassword=  trim($request->getPost('confirm-password'));
			
			$check = $this->getModelTable('UsersTable')->checkUser($email);
			if(!$check)
			{
				if($position == "0"){
	            	             
                	$result = 'Please choose sale position!';  
                	$error = 1;              
	            }            
	            else if($email == ""){
		            
	                $result = 'Please fill email!';
	                $error = 1;
	            }            
	            else  if($fullname == ""){
		            
	                $result = 'Please fill fullname!';
	                $error = 1;
	            }
	            
	            else  if($password == "" || $confirmpassword == ""){
	                $result = 'Please fill new password or re-password';
	                $error = 1;
	            }
	            else  if($password != $confirmpassword){
	                $result = 'Re-password and password do not match';
	                $error = 1;
	            }
	            else
	            {
		            if($senior == "")
		            {
			            $senior = 0;
		            }
		            
		            $data_insert = array("id" => "","parent_id" => $senior,"password" => $password,"fullname" => $fullname,"email" => $email,"type"=>"sale","position"=>$position);
					$this->getModelTable('UsersTable')->insertUser($data_insert);
					$error = 0;
	            }
			}
			else
			{
				$result = 'Email has already been taken!';
				$error = 1;
			}
			
		} 
	  return new ViewModel(array(           
             'form' => $form, 
             'users' => $users, 
             'result' => $result,    
             'error' => $error,          
         ));
	  
	}
	
	
	
	
	public function editSaleAction()
    {
	    $result = "";
		$error = 2;
	  	$form = new UsersForm();
	  	
	  	$seniors = $this->getModelTable('UsersTable')->getSeniors("sale");
			  $options = array();
		        if (count($seniors)) {
		            foreach ($seniors as $senior) {
		                $options[] = array(
		                    'value' => $senior['id'],
		                    'label' => $senior['fullname'],
		                );
		            }
		        } else {
		            return $this->redirect()->toRoute('report/sale', array('action' => 'add-sale'));
		        }
		        $form->get('senior')->setOptions(array(
		            'options' => $options
		        ));
	  	
	  	$request = $this->getRequest();
	  	if($request->isPost()){
			$position = trim($request->getPost('position'));
			$senior = trim($request->getPost('senior'));
			$email =  trim($request->getPost('email'));
			$fullname =  trim($request->getPost('fullname'));	
			$id_user = trim($request->getPost('id'));
			
									
				if($position == "0"){
	            	             
                	$result = 'Please choose sale position!';  
                	$error = 1;              
	            }            
	            else if($email == ""){
		            
	                $result = 'Please fill email!';
	                $error = 1;
	            }            
	            else  if($fullname == ""){
		            
	                $result = 'Please fill fullname!';
	                $error = 1;
	            }	   
	            else
	            {
		            
		            
		            if($senior == "")
		            {
			            $senior = 6;
		            }
		            
		            $array_update = array(
						"parent_id" => $senior,						
						"fullname" => $fullname,
						"email" => $email,
						"position" => $position,				
					);
					$array_where = array(
						"id" => $id_user,									
					);	
														
					$this->getModelTable('UsersTable')->updateUser($array_update,$array_where);
					$error = 0;					
					
	            }			
			
			$sale = $this->getModelTable('UsersTable')->getLeader($id_user);
			
			$form->bind($sale);
			
		} 
		else
	    {
		   	$id = (int)$this->params()->fromRoute('id', 0);
	        if (!$id) {
		      
	            return $this->redirect()->toRoute('report/sale', array(
	                'action' => 'add-sale'
	            ));
	        }
			$sale = $this->getModelTable('UsersTable')->getLeader($id);
			
			$form->bind($sale);
	    }
		
	  return new ViewModel(array(           
             'form' => $form,              
             'result' => $result,    
             'error' => $error,  

         ));
	  
	}
	 
	
}



