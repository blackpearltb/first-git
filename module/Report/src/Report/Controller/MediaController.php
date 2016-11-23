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


class MediaController extends BackEndController
{

		
	
	public function indexAction()
    {
	    if($_SESSION['REPORTMEMBER']['type'] != 'media'){            
            header("Location: /streak_new/public/report/index");
            exit();
        }	   	    	   
		 return new ViewModel(array(                       
         ));          
    }
	
    public function monthAction()
    {
	    if($_SESSION['REPORTMEMBER']['type'] != 'media'){            
            header("Location: streak_new/public/report/index");
            exit();
        }	    	    	   
		$months = $this->getModelTable('KetoanTable')->getMonths();
		
	    return new ViewModel(array(
           
             'months' => $months,
         ));  
        
    }
    
    public function totalCommissionMediaAction()
    {
	  
	    if($_SESSION['REPORTMEMBER']['type'] != 'media'){            
            header("Location: /streak_new/public/report/index");
            exit();
        }   
	    $this->layout('layout/layout_total_commission_type_media');
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

				$date_filter_last_month = date( "Y-m", strtotime( "$filter_time - 1 month" ));
				
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
					   
					   $streaks = $this->getModelTable('KetoanTable')->getMonthComissionMedia($array_email,$filter_time,'media');
				   }
				   else
				   {
					   $streaks = $this->getModelTable('KetoanTable')->getMonthComissionMedia("",$filter_time,'media');
					   					   
				   }
				$streaks = filter_campaign_belong_month($filter_time,$streaks);		    			    			    
			    foreach($streaks as $streak){
				    	
				    	
				    	
					    $indexOf = array_search($streak['streakID'], array_column($streakfiles, 'streakID'),true);
				        if($indexOf)
				        {
				        	$streak['alternateLink'] = $streakfiles[$indexOf]['alternateLink'];			        
				        }
				        else
				        {
					     	$streak['alternateLink'] = "";   
				        }

						
								
								$commmision_status = $this->getModelTable('CommissionStatusMediaTable')->checkCommission($streak['streakID'],$filter_time);
								if(count($commmision_status) > 0)
								{
									$streak['status'] = $commmision_status[0]['status'];
								}
								else
								{
									$streak['status'] = "None";
								}
								
								$streaks_last_month = $this->getModelTable('KetoanTable')->getActualLastMonth($streak['streakID'],$filter_time);					   					       			$actual_profit = str_replace(",","",$streak['actual_profit']);
																						
								$collected_1 = str_replace(",","",$streak['collected_1']);
								$collected_2 = str_replace(",","",$streak['collected_2']);
								$collected_3 = str_replace(",","",$streak['collected_3']);
								$collected_4 = str_replace(",","",$streak['collected_4']);
								
								$collected = number_format($collected_1+$collected_2+$collected_3+$collected_4);
								$streak['collected'] = $collected;
								$streak['25'] = 0;
								$streak['30'] = 0;
								$streak['35'] = 0;
								
								$gp_plan = str_replace("%","",$streak['gp_plan']);
								$gp_actual = str_replace("%","",$streak['actual_gp']);
								
								if($gp_actual - $gp_plan >= 10)
								{
									$streak['35'] = number_format($actual_profit * 0.035);
								}
								else if($gp_actual - $gp_plan >= 5)
								{
									$streak['30'] = number_format($actual_profit * 0.03);
								}
								else if($gp_actual >= $gp_plan && $gp_plan >= 20)
								{
									$streak['25'] = number_format($actual_profit * 0.025);
								}
																
								$streak['total_commission'] = number_format(str_replace(",","",$streak['25']) + str_replace(",","",$streak['30']) + str_replace(",","",$streak['35']));
													    		     			
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
				   $streaks = $this->getModelTable('KetoanTable')->getAll($date_filter,$input_filter,$sortaction);
			   }
		        
		        	
				$streakfiles = $this->getModelTable('FileStreakTable')->getFiles();
		        $array_streaks = array();	
		        $array_test = array();	    
		        $array_test[] = $array_email;
		       
		            
		        foreach($streaks as $streak)
		        {
			        
			        foreach($streakfiles as $streakfile)	
					{
						$name_file = $streakfile['title'];
						$name_file = explode("_", $name_file);
						$name_file = end($name_file);
						if($name_file == $streak['streakID'])
						{	
							$streak['alternateLink'] = 	$streakfile['alternateLink'];				
						 
						}
					}

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
					        	$streak['entertainment'] = number_format($streak['entertainment'] - $entertainment_last_month);
					        
					        					       					        
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
					       
					        foreach($streak['child'] as $streakChild)
						    {
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
		        
				$result['test'] = $array_test;
		        $result['data'] = $array_streaks;	        	
	       
			
	    }	    
	    die(json_encode($result));
	}
    
    public function totalAction()
    {	
	    if($_SESSION['REPORTMEMBER']['type'] != 'media'){            
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
			   			    
			    foreach($streakfiles as $streakfile)	
					{
						$name_file = $streakfile['title'];
						$name_file = explode("_", $name_file);
						$name_file = end($name_file);
						if($name_file == $streak['streakID'])
						{	
							$streak['alternateLink'] = 	$streakfile['alternateLink'];				
						 
						}
					}
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

    
    public function addMediaAction()
    {
	    $result = "";
		$error = 2;
	  	$form = new UsersForm();
	  	$users = array();
	  	
	  	$users_temp = $this->getModelTable('UsersTable')->getUsers("media");
	  	
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
	  	$seniors = $this->getModelTable('UsersTable')->getSeniors("media");
			  $options = array();
		        if (count($seniors)) {
		            foreach ($seniors as $senior) {
		                $options[] = array(
		                    'value' => $senior['id'],
		                    'label' => $senior['fullname'],
		                );
		            }
		        } else {
		            return $this->redirect()->toRoute('report/media', array('action' => 'add-media'));
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
	            	             
                	$result = 'Please choose media position!';  
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
		            
		            $data_insert = array("id" => "","parent_id" => $senior,"password" => $password,"fullname" => $fullname,"email" => $email,"type"=>"media","position"=>$position);
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
	
	
	
	
	public function editMediaAction()
    {
	    $result = "";
		$error = 2;
	  	$form = new UsersForm();
	  	
	  	$seniors = $this->getModelTable('UsersTable')->getSeniors("media");
			  $options = array();
		        if (count($seniors)) {
		            foreach ($seniors as $senior) {
		                $options[] = array(
		                    'value' => $senior['id'],
		                    'label' => $senior['fullname'],
		                );
		            }
		        } else {
		            return $this->redirect()->toRoute('report/media', array('action' => 'add-media'));
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
	            	             
                	$result = 'Please choose media position!';  
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
			            $senior = 1;
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
			
			$media = $this->getModelTable('UsersTable')->getLeader($id_user);
			$form->bind($media);
			
		} 
		else
	    {
		   	$id = (int)$this->params()->fromRoute('id', 0);
	        if (!$id) {
		      
	            return $this->redirect()->toRoute('report/media', array(
	                'action' => 'add-media'
	            ));
	        }
			$media = $this->getModelTable('UsersTable')->getLeader($id);
			$form->bind($media);
	    }
		
	  return new ViewModel(array(           
             'form' => $form,              
             'result' => $result,    
             'error' => $error,          
         ));
	  
	}
	 
	
}



