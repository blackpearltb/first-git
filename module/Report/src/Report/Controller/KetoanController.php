<?php
namespace Report\Controller;


use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use PHPExcel;
use Report\Model\Collected;
use Report\Model\CollectedTable;
use Report\Form\CollectionForm;
use Report\Model\Client;
use Report\Model\ClientsTable;
use Report\Form\CustomerForm;
use Report\Model\Supplier;
use Report\Model\SuppliersTable;
use Report\Form\SupplierForm;

class KetoanController extends BackEndController
{
	protected $ketoanTable;
			
	
	public function indexAction()
    {
	  
	    if($_SESSION['REPORTMEMBER']['type'] != 'ketoan'){            
            header("Location: /streak_new/public/report/index");
            exit();
        }	    	    	   
		 return new ViewModel(array(                       
         ));  

        
    }
	
    public function monthAction()
    {
	    if($_SESSION['REPORTMEMBER']['type'] != 'ketoan'){            
            header("Location: /streak_new/public/report/index");
            exit();
        }
                           	    	   
		$months = $this->getModelTable('KetoanTable')->getMonths();
		
		
	    return new ViewModel(array(
           
             'months' => $months,
         ));  
        
    }
    
    public function totalCommissionSalesAction()
    {
	 
	    if($_SESSION['REPORTMEMBER']['type'] != 'ketoan'){            
            header("Location: /streak_new/public/report/index");
            exit();
        }   
	    
	    
	   	$request = $this->getRequest();		   
	    if($request->isPost()){
		    
		    $data = $request->getPost();
		    $result = array();		    
		    $array_result = array();
		    $filter_time = $data['filter_time'];
		    			     
			$streaks = $this->getModelTable('KetoanTable')->getMonthComissionMedia('',$filter_time,'sale');
			$streaks = filter_campaign_belong_month($filter_time,$streaks);
			$sales = $this->getModelTable('UsersTable')->getUsers("sale");
			
				foreach($sales as $sale){
					$array_email = array();
					$array_email[] = trim($sale['email']);
					$email = trim($sale['email']);					
					if($sale['position'] == "senior")
					{
												   
						$users_child = $this->getModelTable('UsersTable')->getUsersTeam($sale['id']);
						   	 
						foreach($users_child as $user_child)
						{
						    $array_email[] = $user_child['email'];
						}
						
						
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
									//$total_actual_sold = $total_actual_sold +  str_replace(",","",$streak['actual_sold']);
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
	  
	     if($_SESSION['REPORTMEMBER']['type'] != 'ketoan'){            
            header("Location: /streak_new/public/report/index");
            exit();
        }   
	    $this->layout('layout/layout_total_commission_sale');
	    $months = $this->getModelTable('KetoanTable')->getMonths();
	    $sales = $this->getModelTable('UsersTable')->getUsers("sale");
	    
	   	$request = $this->getRequest();
		   
	    if($request->isPost()){
		    
		    $data = $request->getPost();
		    $result = array();
		    $array_streaks = array();
		    $array_test = array();
		    
			$email = $data['email'];		   
		    $filter_time = $data['filter_time'];	
		    $today =  date( "Y-m-d");
			$lastday = date( "Y-m-d", strtotime( "$today - 1 day" ));	  
			$streakfiles = $this->getModelTable('FileStreakTable')->getFiles();  
			
				$date_filter_last_month = date( "Y-m", strtotime( "$filter_time - 1 month" ));
			    $streaks = $this->getModelTable('KetoanTable')->getMonthComissionMedia($email,$filter_time,'sale');
				$streaks = filter_campaign_belong_month($filter_time,$streaks);
			    $result['email'] = $email;
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
								
									
								$streak['actual_sold_value_vnd_after_lobby'] = 	$streak['actual_sold_value_vnd'] - $streak['lobby'];															
								if(str_replace("%","",$streak['gp_plan']) >= 20)
								{
									
									$streak['total_commission'] = $streak['actual_sold_value_vnd_after_lobby'] / 100 * 2;
								}
								else
								{
									$streak['total_commission'] = 0;
								}
								
								$streak['actual_sold_value_vnd_after_lobby'] = number_format($streak['actual_sold_value_vnd_after_lobby']);
								$streak['actual_sold_value_vnd'] = number_format($streak['actual_sold_value_vnd']);
								$streak['lobby'] = number_format($streak['lobby']);
								$streak['total_commission'] = number_format($streak['total_commission']);
																												    		     			
							    $array_streaks[] = $streak;							
			    }
			
		    $result['data'] = $array_streaks;   
		    
		     
			die(json_encode($result));
		}
		else
		{
			
			return new ViewModel(array( 
				'months' => $months,          
	             'sales' => $sales	             
	         ));
			
		} 

        
    }
    
    
    
    public function totalCommissionMediaAction()
    {
	  
	    if($_SESSION['REPORTMEMBER']['type'] != 'ketoan'){            
            header("Location: /streak_new/public/report/index");
            exit();
        }   
	    $this->layout('layout/layout_total_commission');
	    $months = $this->getModelTable('KetoanTable')->getMonths();
	    $medias = $this->getModelTable('UsersTable')->getUsers("media");
	    	    
	   	$request = $this->getRequest();
		   
	    if($request->isPost()){
		    
		    $data = $request->getPost();
		    $result = array();
		    $array_streaks = array();
		    $array_test = array();
		    
			$email = $data['email'];		   
		    $filter_time = $data['filter_time'];	
		    $today =  date( "Y-m-d");
			$lastday = date( "Y-m-d", strtotime( "$today - 1 day" ));	  
			$streakfiles = $this->getModelTable('FileStreakTable')->getFiles();  

				$date_filter_last_month = date( "Y-m", strtotime( "$filter_time - 1 month" ));
			    $streaks = $this->getModelTable('KetoanTable')->getMonthComissionMedia($email,$filter_time,'media');
			    $streaks = filter_campaign_belong_month($filter_time,$streaks);
			   
			    $result['email'] = $email;
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
				'months' => $months,          
	             'medias' => $medias,
	             'streak_total' => $streaks_total	             
	         ));
			
		}  

        
    }
    
    
    public function totalCommissionMediasAction()
    {
	 
	    if($_SESSION['REPORTMEMBER']['type'] != 'ketoan'){            
            header("Location: /streak_new/public/report/index");
            exit();
        }   
	    
	    
	   	$request = $this->getRequest();		   
	    if($request->isPost()){
		    
		    $data = $request->getPost();
		    $result = array();
		    $array_emails = array();
		    $array_result = array();
		    $filter_time = $data['filter_time'];
		    if($filter_time == "")
		     {
			     $thismonth =  date( "Y-m");
			     $filter_time = date( "Y-m", strtotime( "$thismonth - 1 month" ));
		     }				
			$streaks = $this->getModelTable('KetoanTable')->getMonthComissionMedia('',$filter_time,'media');
			$streaks = filter_campaign_belong_month($filter_time,$streaks);
			$medias = $this->getModelTable('UsersTable')->getUsers("media");  

				foreach($medias as $media){
					$array_email = array();
					$array_email[] = trim($media['email']);
					$email = trim($media['email']);					
					if($media['position'] == "senior")
					{
												   
						$users_child = $this->getModelTable('UsersTable')->getUsersTeam($media['id']);
						   	 
						foreach($users_child as $user_child)
						{
						    $array_email[] = $user_child['email'];
						}
						
						
					}
					
					$gp_actual_total = 0;
					$commission_total = 0;
					$total_rev = 0;
					$total_gp = 0;
					$number_count = 0;
					foreach($streaks as $streak)
					{
						//if($email == trim($streak['assigned_to']))
						if(in_array(trim($streak['assigned_to']), $array_email))
						{
							
							if($streak['actual_gp'] != "")
							{
								$gp_actual_total = $gp_actual_total +  str_replace("%","",$streak['actual_gp']);
								$total_rev = $total_rev +  str_replace(",","",$streak['total_rev']);
								$total_gp = $total_gp +  str_replace(",","",$streak['actual_profit']);
								
								$gp_plan = str_replace("%","",$streak['gp_plan']);
								$gp_actual = str_replace("%","",$streak['actual_gp']);
								$actual_profit = str_replace(",","",$streak['actual_profit']);
								if($gp_actual - $gp_plan >= 10)
								{
									$commission_total = $commission_total + ($actual_profit * 0.035);
								}
								else if($gp_actual - $gp_plan >= 5)
								{
									$commission_total = $commission_total + ($actual_profit * 0.03);
								}
								else if($gp_actual >= $gp_plan && $gp_plan >= 20)
								{
									$commission_total = $commission_total + ($actual_profit * 0.025);
								}
																
								$number_count++;
								
							}														
						}
	
					}
					$array_emails[$email]['total_rev'] = number_format($total_rev);
					$array_emails[$email]['gp_actual_total'] = number_format($total_gp);
					$array_emails[$email]['average_actual_total'] = round($gp_actual_total/$number_count, 2)."%";	
					$array_emails[$email]['commission_total'] = number_format($commission_total);				
				}	
				
					
		    $result['data'] = $array_emails;   
		   
		     
			die(json_encode($result));
		}
    
    }
    

    public function addAction()
    {					    
             		
    }
    
    
    public function sendMailCollected2($streakID,$campaign_name,$collected1,$collected2,$collected3,$collected4,$collected_date1,$collected_date2,$collected_date3,$collected_date4)
	{
		
			$streak = $this->getModelTable('TotalTable')->getEmails($streakID);
			$sale = $streak[0]['sale'];
			$media = $streak[0]['assigned_to'];
			
			$parent_id = $this->getModelTable('UsersTable')->getParentID($sale);
			$parent_id_sale = $parent_id[0]['parent_id']; 
			$sale_leader = $this->getModelTable('UsersTable')->getEmailLeader($parent_id_sale);
			$sale_leader = $sale_leader[0]['email'];
			
			$parent_id = $this->getModelTable('UsersTable')->getParentID($media);
			$parent_id_media = $parent_id[0]['parent_id']; 
			$media_leader = $this->getModelTable('UsersTable')->getEmailLeader($parent_id_media);
			$media_leader = $media_leader[0]['email'];
	
			$mail = new \PHPMailer;
			$mail->CharSet = 'UTF-8';
			//$mail->SMTPDebug = 3;                               // Enable verbose debug output					
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'smtp.zoho.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'tin8-noreply@mediaeyes.vn';                 // SMTP username
			$mail->Password = 'ZohoMail@123465';                           // SMTP password
			$mail->SMTPSecure = 'TLS';                           // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;						
			$mail->setFrom('tin8-noreply@mediaeyes.vn', 'Streak Log');
			$mail->addAddress($media);
			$mail->addAddress($media_leader);
			$mail->addAddress($sale);
			$mail->addAddress($sale_leader);			
			$mail->addAddress('hoanglan@urekamedia.com');			
			//$mail->addAddress('blackpearltb@gmail.com');	
			$mail->WordWrap = 50; 
			$mail->isHTML(true); 
			$mail->Subject = 'Streak Log Collected';
			$mail->Body    = "	
				<div>
					<div style='text-align=center;font-size:23px'>Streak Collection</div>			
					<div style='font-size:16px'>
					StreakID : $streakID <br/>
					Campaign : $campaign_name <br/>
					Collected 1 : $collected1 <br/>
					Collected Date 1 : $collected_date1 <br/>
					Collected 2 : $collected2 <br/>
					Collected Date 2 : $collected_date2 <br/>
					Collected 3 : $collected3 <br/>
					Collected Date 3 : $collected_date3 <br/>
					Collected 4 : $collected4 <br/>
					Collected Date 4 : $collected_date4 <br/>
					Sale : $sale <br/>
					Media : $media <br/>	
					</div>				
				</div>
				";			
				$mail->AltBody = 'Streak Log Collected';	
							
				if($mail->send())
				{
					return true;
				}			
				
			
				
	}
	
	
	public function addCustomerAction()
    {			
	    
	    $form = new CustomerForm();
	    
	    $customers = $this->getModelTable('ClientsTable')->getAll();
	      	   
        $request = $this->getRequest();
        if($request->isPost()){

            $data = $request->getPost();

            $customer = new Client();
			
            $form->setInputFilter($customer->getInputFilter());
            $form->setData($data);
            if($form->isValid()){
	            
                $customer->exchangeArray($form->getData());  
                            
				$name = $customer->name;
				$address = $customer->address;
				$phone = $customer->phone;
				$mst = $customer->mst;
										
				$check = $this->getModelTable('ClientsTable')->checkcustomer($name);
				

				
				if(count($check) > 0)
				{										
					echo "<script>alert('Customer exist!')</script>";										
				}
				else
				{	
					
					$data_insert = array("id" => "","name" => $name,"address" => $address,"phone" => $phone,"email" =>$mst);								
					$this->getModelTable('ClientsTable')->insertCustomer($data_insert);
	
                }
                
                $this->myRedirect();	
                		
            }
        }      	    		    
        return new ViewModel(array(           
             'form' => $form,
             'customers' => $customers,             
         ));      		
    }
    
    public function editCustomerAction()
    {
	    
		$form = new CustomerForm();
		
		$request = $this->getRequest();
        if($request->isPost()){
			
	        $data = $request->getPost();
	        
            $customer = new Client();
            
            $form->setInputFilter($customer->getInputFilter());
            $form->setData($data);
            if($form->isValid()){
	           
	           	$customer->exchangeArray($form->getData());
	           	$id_customer = $customer->id;                	           	
				$name = $customer->name;
				$address = $customer->address;
				$phone = $customer->phone;
				$mst = $customer->mst;
								
				$array_update = array(
						"name" => $name,
						"address" => $address,
						"phone" => $phone,
						"mst" => $mst,																
					);
					$array_where = array(
						"id" => $id_customer,									
					);						
					$this->getModelTable('ClientsTable')->updateCustomer($array_update,$array_where);
					
				echo "<script>alert('Updated Customer Successful!')</script>";	
				$this->myRedirect();
					
	        }
	    }
	    else
	    {
		   	$id = (int)$this->params()->fromRoute('id', 0);
	        if (!$id) {
		      
	            return $this->redirect()->toRoute('report/ketoan', array(
	                'action' => 'add-customer'
	            ));
	        }
			$customer = $this->getModelTable('ClientsTable')->getCustomer($id);
			$form->bind($customer);
	    }
	    
	    return new ViewModel(array(           
             'form' => $form,            
         ));
	}
	
	
	
	public function addSupplierAction()
    {			
	    
	    $form = new SupplierForm();
	    
	    $suppliers = $this->getModelTable('SuppliersTable')->getAll();
	      	   
        $request = $this->getRequest();
        if($request->isPost()){

            $data = $request->getPost();

            $supplier = new Supplier();
			
            $form->setInputFilter($supplier->getInputFilter());
            $form->setData($data);
            if($form->isValid()){
	            
                $supplier->exchangeArray($form->getData());  
                            
				$name = $supplier->name;
				$contact_person = $supplier->contact_person;
				$phone = $supplier->phone;
				$address = $supplier->address;
				$mst = $supplier->mst;
										
				$check = $this->getModelTable('SuppliersTable')->checksupplier($name);
				

				
				if(count($check) > 0)
				{										
					echo "<script>alert('Supplier exist!')</script>";										
				}
				else
				{	
					
					$data_insert = array("id" => "","name" => $name,"contact_person" => $contact_person,"phone" => $phone,"address" => $address,"mst" =>$mst);								
					$this->getModelTable('SuppliersTable')->insertSupplier($data_insert);
	
                }
                
                $this->myRedirect();	
                		
            }
        }      	    		    
        return new ViewModel(array(           
             'form' => $form,
             'suppliers' => $suppliers,             
         ));      		
    }
    
    public function editSupplierAction()
    {
	    
		$form = new SupplierForm();
		
		$request = $this->getRequest();
        if($request->isPost()){
			
	        $data = $request->getPost();
	        
            $supplier = new Supplier();
            
            $form->setInputFilter($supplier->getInputFilter());
            $form->setData($data);
            if($form->isValid()){
	           
	           	$supplier->exchangeArray($form->getData());
	           	$id_supplier = $supplier->id;                	           	
				$name = $supplier->name;
				$contact_person = $supplier->contact_person;
				$phone = $supplier->phone;
				$email = $supplier->email;
								
				$array_update = array(
						"name" => $name,
						"contact_person" => $contact_person,
						"phone" => $phone,
						"address" => $address,
						"mst" => $mst,																
					);
					$array_where = array(
						"id" => $id_supplier,									
					);						
					$this->getModelTable('SuppliersTable')->updateSupplier($array_update,$array_where);
					
				echo "<script>alert('Updated Supplier Successful!')</script>";	
				$this->myRedirect();
					
	        }
	    }
	    else
	    {
		   	$id = (int)$this->params()->fromRoute('id', 0);
	        if (!$id) {
		      
	            return $this->redirect()->toRoute('report/ketoan', array(
	                'action' => 'add-supplier'
	            ));
	        }
			$supplier = $this->getModelTable('SuppliersTable')->getSupplier($id);
			$form->bind($supplier);
	    }
	    
	    return new ViewModel(array(           
             'form' => $form,            
         ));
	}
    
    
    public function addCollectionAction()
    {			
	    
	    $form = new CollectionForm();
	    $collections = $this->getModelTable('CollectedTable')->getAll();
	    $clients = $this->getModelTable('ClientsTable')->getClients();
	    $streaksID = $this->getModelTable('KetoanTable')->getStreakID();
	    
	    /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if($request->isPost()){
            $data = $request->getPost();
            $collected = new Collected();
            $form->setInputFilter($collected->getInputFilter());
            $form->setData($data);
            if($form->isValid()){
                $collected->exchangeArray($form->getData());                
				$streakID = $collected->streakID;
				$client = $collected->client;
				$campaign_name = $collected->campaign_name;
				$contract = $collected->contract;
				$contract_value = $collected->contract_value;
				$collected_1 = $collected->collected_1;
				$collected_2 = $collected->collected_2;
				$collected_3 = $collected->collected_3;
				$collected_4 = $collected->collected_4;
				$collected_date1 = $collected->collected_date1;
				$collected_date2 = $collected->collected_date2;
				$collected_date3 = $collected->collected_date3;
				$collected_date4 = $collected->collected_date4;
			
				
				$check = $this->getModelTable('CollectedTable')->checkcollected($streakID);
				if(count($check) > 0)
				{
					
					$array_update = array(
						"client" => $collected->client,
						"campaign_name" => $collected->campaign_name,
						"contract" => $collected->contract,
						"contract_value" => $collected->contract_value,
						"collected_1" => $collected->collected_1,
						"collected_date1" => $collected->collected_date1,
						"collected_2" => $collected->collected_2,
						"collected_date2" => $collected->collected_date2,
						"collected_3" => $collected->collected_3,
						"collected_date3" => $collected->collected_date3,
						"collected_4" => $collected->collected_4,
						"collected_date4" => $collected->collected_date4
										
					);
					$array_where = array(
						"streakID" => $streakID									
					);						
					$this->getModelTable('CollectedTable')->updateCollected($array_update,$array_where);
										
				}
				else
				{	
					
					$data_insert = array("id" => "","client" => $client,"streakID" => $streakID,"campaign_name" => $campaign_name,"contract" =>$contract,"contract_value" => $contract_value,"collected_1" => $collected_1,"collected_date1" => $collected_date1,"collected_2" => $collected_2,"collected_date2" => $collected_date2,"collected_3" => $collected_3,"collected_date3" => $collected_date3,"collected_4" => $collected_4,"collected_date4" => $collected_date4,"vat" => "","remain" => "");
				
				
					$this->getModelTable('CollectedTable')->insertCollected($data_insert);
					
					
					
                }
				if($this->sendMailCollected2($streakID,$campaign_name,$collected_1,$collected_2,$collected_3,$collected_4,$collected_date1,$collected_date2,$collected_date3,$collected_date4)){
                	$this->myRedirect();
                }
            }
        }      	    		    
        return new ViewModel(array(
           
             'form' => $form,
             'collections' => $collections,
             "clients" => $clients,
             'streaksID' => $streaksID,
         ));      		
    }
    
    
    public function editCollectionAction()
    {
	    
	
		$form = new CollectionForm();
		$clients = $this->getModelTable('ClientsTable')->getClients();
		$streaksID = $this->getModelTable('KetoanTable')->getStreakID();
		$request = $this->getRequest();
        if($request->isPost()){
			
	        $data = $request->getPost();
	        
            $collected = new Collected();
            $form->setInputFilter($collected->getInputFilter());
            $form->setData($data);
            if($form->isValid()){
	            
	           	$collected->exchangeArray($form->getData());
	           	$id_collection = $collected->id;                	           	
				$streakID = $collected->streakID;			
				$client = $collected->client;
				$campaign_name = $collected->campaign_name;
				$contract = $collected->contract;
				$contract_value = $collected->contract_value;
				$collected_1 = $collected->collected_1;
				$collected_2 = $collected->collected_2;
				$collected_3 = $collected->collected_3;
				$collected_4 = $collected->collected_4;
				$collected_date1 = $collected->collected_date1;
				$collected_date2 = $collected->collected_date2;
				$collected_date3 = $collected->collected_date3;
				$collected_date4 = $collected->collected_date4;
				
				$array_update = array(
						"client" => $collected->client,
						"streakID" => $collected->streakID,
						"campaign_name" => $collected->campaign_name,
						"contract" => $collected->contract,
						"contract_value" => $collected->contract_value,
						"collected_1" => $collected->collected_1,
						"collected_date1" => $collected->collected_date1,
						"collected_2" => $collected->collected_2,
						"collected_date2" => $collected->collected_date2,
						"collected_3" => $collected->collected_3,
						"collected_date3" => $collected->collected_date3,
						"collected_4" => $collected->collected_4,
						"collected_date4" => $collected->collected_date4
										
					);
					$array_where = array(
						"id" => $id_collection,									
					);						
					$this->getModelTable('CollectedTable')->updateCollected($array_update,$array_where);
					
					
					if($this->sendMailCollected2($streakID,$campaign_name,$collected_1,$collected_2,$collected_3,$collected_4,$collected_date1,$collected_date2,$collected_date3,$collected_date4)){
						echo "<script>alert('Updated Collection Successful!')</script>";	
						$this->myRedirect();
                	}
					
	        }
	    }
	    else
	    {
		   	$id = (int)$this->params()->fromRoute('id', 0);
	        if (!$id) {
		      
	            return $this->redirect()->toRoute('report/ketoan', array(
	                'action' => 'add-collection'
	            ));
	        }
			$collection = $this->getModelTable('CollectedTable')->getCollection($id);
			$form->bind($collection);
	    }
	    
	    return new ViewModel(array(           
             'form' => $form,
             "clients" => $clients,
             'streaksID' => $streaksID,
         ));
	}
    
    
    public function totalAction()
    {	
	  	 if($_SESSION['REPORTMEMBER']['type'] != 'ketoan'){            
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
		    $array_test = array();
		    $today =  date( "Y-m-d");
			$lastday = date( "Y-m-d", strtotime( "$today - 1 day" ));
		    
		    $streaks = $this->getModelTable('TotalTable')->getAll($lastday,$input_filter,$sortaction);
		    $streakfiles = $this->getModelTable('FileStreakTable')->getFiles();
		    $clients = $this->getModelTable('ClientsTable')->getClients();
		    
		    
		    foreach($streaks as $streak){
			   	
			   	
			   	$end_date = $streak['end_date'];
			   	$end_date = explode("/", $end_date);
			   	$end_date = $end_date[2];
			   	if($end_date < '2016')
			   	{	
				   	continue;
				}
				else
				{   
					
					$indexOf = array_search($streak['streakID'], array_column($streakfiles, 'streakID'),true);
			        if($indexOf)
			        {
			        	$streak['alternateLink'] = $streakfiles[$indexOf]['alternateLink'];			        
			        }
			        else
			        {
				     	$streak['alternateLink'] = "";   
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
						
					$key_match_client = md5($streak['streakID'].$streak['name']);							
					$name_client = $this->getModelTable('ClientMatchCampaignTable')->getClient($key_match_client);								
					$streak['client'] = $name_client['name'];														
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
						$streakChild['client'] = "";
						$streakChild['invoice'] = $count_invoice_child;
						$array_streaks_child[] = $streakChild;
					}
				    $streak['child'] = $array_streaks_child;			     
				    
				    $array_streaks[] = $streak;
			    }
			    
		    }		    		    		    
		    $result['data'] = $array_streaks;   
		    
		     
			die(json_encode($result));
		}
		else
		{
			return new ViewModel();
		}
	    	
	    
    }

    
    public function exportExcelAction(){
	    	    	    
	    $result = array();
	    $result['data'] = "Đã Export";	    	    	                           
	    
	    $request = $this->getRequest();
	    
	    
	    if($request->isPost()){
		    $data = $request->getPost();
		    $filename =  $data['filename'];
		    $data_export =  $data['data_export'];
		    $data_export = json_decode($data_export);
		}
		
				
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet()->setTitle('List of Months');
            $rowNumber = 1;
            $col = 'A';		
            
            foreach ($data_export as $data) {
                $col = 'A';
                foreach ($data as $value) {
                    $objPHPExcel->getActiveSheet()->setCellValue($col . $rowNumber, $value);
                    $col++;
                }
                $rowNumber++;
            }
                                    
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=$filename.xls");
            header('Cache-Control: max-age=0');

            $objWriter->save("$filename.xls");                    

	    die(json_encode($result));
	}
	
	public function exportTotalRevAction(){
	    	    	    
	    $result = array();
	    $result['data'] = "Đã Export";	    	    	                           
	    
	    $request = $this->getRequest();
	    
	    
	    if($request->isPost()){
		    $data = $request->getPost();
		    $data_export =  $data['data_export'];
		    $data_export = json_decode($data_export);
		}
		
				
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet()->setTitle('List of Months');
            $rowNumber = 1;
            $col = 'A';		
            
            foreach ($data_export as $data) {
                $col = 'A';
                foreach ($data as $value) {
                    $objPHPExcel->getActiveSheet()->setCellValue($col . $rowNumber, $value);
                    $col++;
                }
                $rowNumber++;
            }
                                    
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="ketoan-total-rev.xls"');
            header('Cache-Control: max-age=0');

            $objWriter->save('ketoan-total-rev.xls');                    

	    die(json_encode($result));
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
		        
		        $result['date_filter'] = $date_filter;
		        $streaks = $this->getModelTable('KetoanTable')->getAll($date_filter,$input_filter,$sortaction);	
				$streaks = filter_campaign_belong_month($date_filter,$streaks);
				$clients = $this->getModelTable('ClientsTable')->getClients();
		       	$suppliers = $this->getModelTable('SuppliersTable')->getSuppliers(); 
		        $streakfiles = $this->getModelTable('FileStreakTable')->getFiles();
		        
		        $array_streaks = array();	
		        $array_test = array();	
				$total_actual_sold = 0;
				$total_actual_cost = 0;
				$total_entertainment = 0;
				$total_lobby = 0;
				$total_invoice_usd = 0;
				
		        foreach($streaks as $streak)
		        {
			       			        
			        $indexOf = array_search($streak['streakID'], array_column($streakfiles, 'streakID'),true);
			        if($indexOf)
			        {
			        	$streak['alternateLink'] = $streakfiles[$indexOf]['alternateLink'];			        
			        }
			        else
			        {
				     	$streak['alternateLink'] = "";   
			        }
			        
			        /*
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
					*/								
							$array_streaks_child = array();
					        $streaks_last_month = $this->getModelTable('KetoanTable')->getActualLastMonth($streak['streakID'],$date_filter);					   					       
					        
					        $streak['actual_sold_value_vnd'] = str_replace(",","",$streak['actual_sold_value_vnd']);						   
					        $streak['actual_cost_usd'] = str_replace(",","",$streak['actual_cost_usd']);						   
					        $streak['actual_cost_vnd'] = str_replace(",","",$streak['actual_cost_vnd']);
					        $streak['actual_profit'] = str_replace(",","",$streak['actual_profit']);					        
					        $streak['achieved_kpi'] = str_replace(",","",$streak['achieved_kpi']);
					        $streak['lobby'] = str_replace(",","",$streak['lobby']);					        
					        $streak['entertainment'] = str_replace(",","",$streak['entertainment']);
					        
					        if(count($streaks_last_month) > 0){						       
						        //$date_filter_last_month = $streaks_last_month[0]['date'];
						        					        	
						        					        	
								$actual_sold_value_vnd_last_month = $streaks_last_month[0]['actual_sold_value_vnd'];				        	
								$actual_sold_value_vnd_last_month = str_replace(",","",$actual_sold_value_vnd_last_month);							        						        
						        $streak['actual_sold_value_vnd'] = $streak['actual_sold_value_vnd'] - $actual_sold_value_vnd_last_month;	
					       		        

						        $actual_cost_usd_last_month = $streaks_last_month[0]['actual_cost_usd'];				        							        
					        	$actual_cost_usd_last_month = str_replace(",","",$actual_cost_usd_last_month);			        						        	
					        	$streak['actual_cost_usd'] = $streak['actual_cost_usd'] - $actual_cost_usd_last_month;
					          
						        $actual_cost_vnd_last_month = $streaks_last_month[1]['actual_cost_vnd'];				        	
					        	$actual_cost_vnd_last_month = str_replace(",","",$actual_cost_vnd_last_month);			        						        	
					        	$streak['actual_cost_vnd'] = $streak['actual_cost_vnd'] - $actual_cost_vnd_last_month;
					       
						        
						        $actual_profit_last_month = $streaks_last_month[0]['actual_profit'];				        	
					        	$actual_profit_last_month = str_replace(",","",$actual_profit_last_month);			        						        	
					        	$streak['actual_profit'] = $streak['actual_profit'] - $actual_profit_last_month;
					        	
					        	$achieved_kpi_last_month = $streaks_last_month[0]['achieved_kpi'];				        	
					        	$achieved_kpi_last_month = str_replace(",","",$achieved_kpi_last_month);			        						        	
					        	$streak['achieved_kpi'] = $streak['achieved_kpi'] - $achieved_kpi_last_month;
					        	
					        	$lobby_last_month = $streaks_last_month[0]['lobby'];				        	
					        	$lobby_last_month = str_replace(",","",$lobby_last_month);			        						        	
					        	$streak['lobby'] = $streak['lobby'] - $lobby_last_month;
					        	
					        	$entertainment_last_month = $streaks_last_month[0]['entertainment'];				        	
					        	$entertainment_last_month = str_replace(",","",$entertainment_last_month);			        						        	
					        	$streak['entertainment'] = $streak['entertainment'] - $entertainment_last_month;
					        }	
					        $actual_sold_usd = 	$streak['actual_sold_value_vnd']/22500;	
					        
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
							    $streakChild['actual_cost_usd'] = str_replace(",","",$streakChild['actual_cost_usd']);
							    $streakChild['actual_cost_vnd'] = str_replace(",","",$streakChild['actual_cost_vnd']);
							    $streakChild['actual_profit'] = str_replace(",","",$streakChild['actual_profit']);
							    $streakChild['achieved_kpi'] = str_replace(",","",$streakChild['achieved_kpi']);
							    $streakChild['lobby'] = str_replace(",","",$streakChild['lobby']);
							    $streakChild['entertainment'] = str_replace(",","",$streakChild['entertainment']);
							    
							    if(count($streaks_child_last_month) > 0)
							    {							    								    							    
									$child_actual_sold_value_vnd_last_month = $streaks_child_last_month[0]['actual_sold_value_vnd'];
									$child_actual_sold_value_vnd_last_month = str_replace(",","",$child_actual_sold_value_vnd_last_month);									
									$streakChild['actual_sold_value_vnd'] = $streakChild['actual_sold_value_vnd'] - $child_actual_sold_value_vnd_last_month;
								
									$child_actual_cost_usd_last_month = $streaks_child_last_month[0]['actual_cost_usd'];
									$child_actual_cost_usd_last_month = str_replace(",","",$child_actual_cost_usd_last_month);																		
									$streakChild['actual_cost_usd'] = $streakChild['actual_cost_usd'] - $child_actual_cost_usd_last_month;
									
							   
									$child_actual_cost_vnd_last_month = $streaks_child_last_month[0]['actual_cost_vnd'];
									$child_actual_cost_vnd_last_month = str_replace(",","",$child_actual_cost_vnd_last_month);																		
									$streakChild['actual_cost_vnd'] = $streakChild['actual_cost_vnd'] - $child_actual_cost_vnd_last_month;
									
							   
									$child_actual_profit_last_month = $streaks_child_last_month[0]['actual_profit'];
									$child_actual_profit_last_month = str_replace(",","",$child_actual_profit_last_month);																		
									$streakChild['actual_profit'] = $streakChild['actual_profit'] - $child_actual_profit_last_month;
									
									
									$child_achieved_kpi_last_month = $streaks_child_last_month[0]['achieved_kpi'];
									$child_achieved_kpi_last_month = str_replace(",","",$child_achieved_kpi_last_month);
									
									$streakChild['achieved_kpi'] = $streakChild['achieved_kpi'] - $child_achieved_kpi_last_month;
									
									$child_lobby_last_month = $streaks_child_last_month[0]['lobby'];
									$child_lobby_last_month = str_replace(",","",$child_lobby_last_month);
									
									$streakChild['lobby'] = $streakChild['lobby'] - $child_lobby_last_month;
									
									$child_entertainment_last_month = $streaks_child_last_month[0]['entertainment'];
									$child_entertainment_last_month = str_replace(",","",$child_entertainment_last_month);									
									
									$streakChild['entertainment'] = $streakChild['entertainment'] - $child_entertainment_last_month;
								}	
							   
							   
							    $streakChild['client'] = "";
							   
							    
							    $key_match_supplier_child = md5($streakChild['streakID'].$streakChild['channel']);
							    
								$name_supplier_child = $this->getModelTable('SupplierMatchCampaignTable')->getSupplier($key_match_supplier_child);						
								$streakChild['supplier']  = $name_supplier_child['name'];
							    
							    $array_streaks_child[] = $streakChild;							    							    
						    }
						    
						    $total_actual_sold =  $total_actual_sold + $streak['actual_sold_value_vnd'];
						    $total_actual_cost =  $total_actual_cost + $streak['actual_cost_usd'];
						    $total_lobby =  $total_lobby + $streak['lobby'];
						    $total_entertainment =  $total_entertainment + $streak['entertainment'];
						  	$total_invoice_usd = $total_invoice_usd + str_replace(",", "", $streak['invoice_value']);
						  						    
					        $streak['child'] = $array_streaks_child;			        
					        $array_streaks[] = $streak;
					/*        
					        break;
						}
												
					}	
					*/
     
		        }
		        $result['clients'] = $clients;
		        $result['suppliers'] = $suppliers;
				$result['total_actual_sold'] = number_format($total_actual_sold);
				$result['total_actual_cost'] = number_format($total_actual_cost);
				$result['total_lobby'] = number_format($total_lobby);
				$result['total_entertainment'] = number_format($total_entertainment);
				$result['total_invoice_usd'] = number_format($total_invoice_usd);
		        $result['data'] = $array_streaks;	        	

			
	    }	    
	    die(json_encode($result));
	}
	
	
	public function getActualStreakChildAction()
	{	
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
	    
	    $request = $this->getRequest();
	    
	    if($request->isPost()){
	    	
	        $data = $request->getPost();
	        $date_filter = $data['date_filter'];
	        $date_filter_last_month = date( "Y-m", strtotime( "$date_filter - 1 month" ));
	        $array_columns_choice = ["streakID","channel","actual_sold_value_vnd","actual_cost_usd","invoice_value","start_date","end_date","id_join"];
	        $streaks = $this->getModelTable('KetoanChildTable')->getAll($array_columns_choice,$date_filter);
	        $array_streaks = [];
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
							
							$id_join = $streak['id_join'];
							$actual_sold_value_vnd = str_replace(",","",$streak['actual_sold_value_vnd']);
							$actual_cost_usd = str_replace(",","",$streak['actual_cost_usd']);	
							$invoice = str_replace(",","",$streak['invoice_value']);				
							
							
							
							$streak_child_last_month = $this->getModelTable('KetoanChildTable')->getActualLastMonth_Child($id_join,$date_filter);
							
							$actual_sold_value_vnd_last = str_replace(",","",$streak_child_last_month[0]['actual_sold_value_vnd']);
							$streak['actual_sold_value_vnd'] = $actual_sold_value_vnd - $actual_sold_value_vnd_last;
							
							$actual_cost_usd_last = str_replace(",","",$streak_child_last_month[0]['actual_cost_usd']);
							$streak['actual_cost_usd'] = $actual_cost_usd - $actual_cost_usd_last;
							
							$invoice_last = str_replace(",","",$streak_child_last_month[0]['invoice_value']);
							$streak['invoice_value'] = $invoice - $invoice_last;
							
		
							$array_streaks[] = $streak;
							
							break;
						}
					}
			}
	        
	        $result['data'] = $array_streaks;
	    }
	    
	    die(json_encode($result));
	}
	
	
	public function updateRemainAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();
		    $id_join =  $data['id_join'];
		    $streakID = explode("-",$id_join);
			$streakID = $streakID[0];
		    $value =  $data['value'];
		    
		    $date_filter =  $data['date_filter'];
		    if($date_filter == "")
		    {
				$current_month = date('Y-m');
				$date_filter = date( "Y-m", strtotime( "$current_month - 1 month" ));    
		    }
		    				
				$value = str_replace(",","",$value);
							
				$array_update = array(
					"remain" => $value					
				);
				$array_where = array(
					"streakID" => $streakID,
					"date" => $date_filter
				);
			
				$result['data'] = $total;				
				$this->getModelTable('KetoanTable')->updateParent($array_update,$array_where);
				
		}

		die(json_encode($result));
		
	}
	
	
	public function updateTotalRevAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();
		    $id_join =  $data['id_join'];
		    $streakID = explode("-",$id_join);
			$streakID = $streakID[0];
		    $value =  $data['value'];
		    $date_filter =  $data['date_filter'];
		    if($date_filter == "")
		    {
				$current_month = date('Y-m');
				$date_filter = date( "Y-m", strtotime( "$current_month - 1 month" ));    
		    }
		    				
				$value = str_replace(",","",$value);
							
				$array_update = array(
					"total_rev" => $value					
				);
				$array_where = array(
					"streakID" => $streakID,
					"date" => $date_filter
				);
			
				$result['data'] = $total;				
				$this->getModelTable('KetoanTable')->updateParent($array_update,$array_where);
				
		}

		die(json_encode($result));
		
	}
	
	public function updateDealsizeAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();
		    $id_join =  $data['id_join'];
		    $streakID = explode("-",$id_join);
			$streakID = $streakID[0];
		    $value =  $data['value'];
		    $date_filter =  $data['date_filter'];
		    if($date_filter == "")
		    {
				$current_month = date('Y-m');
				$date_filter = date( "Y-m", strtotime( "$current_month - 1 month" ));    
		    }
		    				
				$value = str_replace(",","",$value);
							
				$array_update = array(
					"dealsize" => $value					
				);
				$array_where = array(
					"streakID" => $streakID,
					"date" => $date_filter
				);
			
				$result['data'] = $total;				
				$this->getModelTable('KetoanTable')->updateParent($array_update,$array_where);
				
		}

		die(json_encode($result));
		
	}
	
	
	public function updateVatAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();
		    $id_join =  $data['id_join'];
		    $streakID = explode("-",$id_join);
			$streakID = $streakID[0];
		    $value =  $data['value'];
		    $date_filter =  $data['date_filter'];
		    if($date_filter == "")
		    {
				$current_month = date('Y-m');
				$date_filter = date( "Y-m", strtotime( "$current_month - 1 month" ));    
		    }
		    				
				$value = str_replace(",","",$value);
							
				$array_update = array(
					"vat" => $value					
				);
				$array_where = array(
					"streakID" => $streakID,
					"date" => $date_filter
				);
			
				$result['data'] = $total;				
				$this->getModelTable('KetoanTable')->updateParent($array_update,$array_where);
				
		}

		die(json_encode($result));
		
	}
	
	public function updateVatTotalAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();
		    $id_join =  $data['id_join'];
		    $streakID = explode("-",$id_join);
			$streakID = $streakID[0];
		    $value =  $data['value'];
		    
		   			
			$value = str_replace(",","",$value);
			
			
			$check = $this->getModelTable('CollectedTable')->checkcollected($streakID);
			
			
			if(count($check) > 0)
			{		
	
				$array_update = array(
					"vat" => $value					
				);
				$array_where = array(
					"streakID" => $streakID									
				);
					
				$this->getModelTable('CollectedTable')->updateCollected($array_update,$array_where);
			}
			else
			{
				
					$data_insert = array("id" => "","streakID" => $streakID,"collected_1" => "","collected_date1" => "","collected_2" => "","collected_date2" => "","collected_3" => "","collected_date3" => "","collected_4" => "","collected_date4" => "","vat" => $value,"remain" => "");
				
				
				$this->getModelTable('CollectedTable')->insertCollected($data_insert);
				
			}										
		}

		die(json_encode($result));
		
	}
	
	
	public function updateRemainTotalAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();		   
			$id_join =  $data['id_join'];

		    $streakID = explode("-",$id_join);
			$streakID = $streakID[0];
		    $value =  $data['value'];
		   		   		   
			$value = str_replace(",","",$value);
			
			$array_update = array(
				"remain" => $value					
			);
			$array_where = array(
				"streakID" => $streakID									
			);
							
			$this->getModelTable('CollectedTable')->updateCollected($array_update,$array_where);
										
		}

		die(json_encode($result));
		
	}
	

	public function updateCollectedAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();		   
			$id_join =  $data['id_join'];
			
			
		    $streakID = explode("-",$id_join);
			$streakID = $streakID[0];
			
			
			
		    $value =  $data['value'];
		    $stt =  $data['stt'];
		   
		    $column = "collected_".$stt;				
			$value = str_replace(",","",$value);
			
			
			$check = $this->getModelTable('CollectedTable')->checkcollected($streakID);
			
									
			if(count($check) > 0)
			{		
	
				$array_update = array(
					$column => $value					
				);
				$array_where = array(
					"streakID" => $streakID									
				);
										
				$this->getModelTable('CollectedTable')->updateCollected($array_update,$array_where);
			}
			else
			{
				if($stt == 1)
				{
					$data_insert = array("id" => "","streakID" => $streakID,"collected_1" => $value,"collected_date1" => "","collected_2" => "","collected_date2" => "","collected_3" => "","collected_date3" => "","collected_4" => "","collected_date4" => "");
	
				}
				else if($stt == 2)
				{
					$data_insert = array("id" => "","streakID" => $streakID,"collected_1" => "","collected_date1" => "","collected_2" => $value,"collected_date2" => "","collected_3" => "","collected_date3" => "","collected_4" => "","collected_date4" => "");
				}
				else if($stt == 3)
				{
					$data_insert = array("id" => "","streakID" => $streakID,"collected_1" => "","collected_date1" => "","collected_2" => "","collected_date2" => "","collected_3" => $value,"collected_date3" => "","collected_4" => "","collected_date4" => "");
				}
				else if($stt == 4)
				{
					$data_insert = array("id" => "","streakID" => $streakID,"collected_1" => "","collected_date1" => "","collected_2" => "","collected_date2" => "","collected_3" => "","collected_date3" => "","collected_4" => $value,"collected_date4" => "");
				}
				
				$this->getModelTable('CollectedTable')->insertCollected($data_insert);
				
			}
	
		}
		
		die(json_encode($result));
		
	}
	
	public function sendMailCollectedAction()
	{
		
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();
			$collected1 =  number_format($data['collected1']);
			$collected_date1 =  $data['collected_date1'];
			$collected2 =  number_format($data['collected2']);
			$collected_date2 =  $data['collected_date2'];
			$collected3 =  number_format($data['collected3']);
			$collected_date3 =  $data['collected_date3'];
			$collected4 =  number_format($data['collected4']);
			$collected_date4 =  $data['collected_date4'];
			$campaign =  $data['campaign'];
			$id_join =  $data['id_join'];
			$dealsize =  number_format($data['dealsize']);
			$remain =  number_format($data['remain']);
			$streakID = explode("-", $id_join);
			$streakID = $streakID[0];
			$streak = $this->getModelTable('TotalTable')->getEmails($streakID);
			$sale = $streak[0]['sale'];
			$media = $streak[0]['assigned_to'];
			
			$parent_id = $this->getModelTable('UsersTable')->getParentID($sale);
			$parent_id_sale = $parent_id[0]['parent_id']; 
			$sale_leader = $this->getModelTable('UsersTable')->getEmailLeader($parent_id_sale);
			$sale_leader = $sale_leader[0]['email'];
			
			$parent_id = $this->getModelTable('UsersTable')->getParentID($media);
			$parent_id_media = $parent_id[0]['parent_id']; 
			$media_leader = $this->getModelTable('UsersTable')->getEmailLeader($parent_id_media);
			$media_leader = $media_leader[0]['email'];
	
			$mail = new \PHPMailer;
			$mail->CharSet = 'UTF-8';
			//$mail->SMTPDebug = 3;                               // Enable verbose debug output					
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'smtp.zoho.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'tin8-noreply@mediaeyes.vn';                 // SMTP username
			$mail->Password = 'ZohoMail@123465';                           // SMTP password
			$mail->SMTPSecure = 'TLS';                           // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;						
			$mail->setFrom('tin8-noreply@mediaeyes.vn', 'Streak Log');
			$mail->addAddress($media);
			$mail->addAddress($media_leader);
			$mail->addAddress($sale);
			$mail->addAddress($sale_leader);			
			$mail->addAddress('hoanglan@urekamedia.com');			
			//$mail->addAddress('blackpearltb@gmail.com');	
			$mail->WordWrap = 50; 
			$mail->isHTML(true); 
			$mail->Subject = 'Streak Log Collected';
			$mail->Body    = "	
				<div>
					<div style='text-align=center;font-size:23px'>Streak Collection</div>			
					<div style='font-size:16px'>					
					StreakID : $streakID <br/>
					Campaign : $campaign <br/>
					Dealsize : $dealsize <br/>
					Remain : $remain <br/>
					Collected 1 : $collected1 <br/>
					Collected Date 1 : $collected_date1 <br/>
					Collected 2 : $collected2 <br/>
					Collected Date 2 : $collected_date2 <br/>
					Collected 3 : $collected3 <br/>
					Collected Date 3 : $collected_date3 <br/>
					Collected 4 : $collected4 <br/>
					Collected Date 4 : $collected_date4 <br/>
					Sale : $sale <br/>
					Media : $media <br/>	
					</div>				
				</div>
				";			
				$mail->AltBody = 'Streak Log Collected';	
							
				$mail->send();
			
		}		
			
		die(json_encode($result));		
	}
	
	
	
	
	
	public function updateCollectedDateAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();		   
			$id_join =  $data['id_join'];
	
		    $streakID = explode("-",$id_join);
			$streakID = $streakID[0];

		    $value =  $data['value'];
		    $stt =  $data['stt'];
		   
		    $column = "collected_date".$stt;				
			$value = str_replace(",","",$value);
			//$result['test'] = $streakID;		
			
			$check = $this->getModelTable('CollectedTable')->checkcollected($streakID);
					
			if(count($check) > 0)
			{		
	
				$array_update = array(
					$column => $value					
				);
				$array_where = array(
					"streakID" => $streakID									
				);
				
						
				$this->getModelTable('CollectedTable')->updateCollected($array_update,$array_where);
			}
			else
			{
				if($stt == 1)
				{
					$data_insert = array("id" => "","streakID" => $streakID,"collected_1" => "","collected_date1" => $value,"collected_2" => "","collected_date2" => "","collected_3" => "","collected_date3" => "","collected_4" => "","collected_date4" => "");
	
				}
				else if($stt == 2)
				{
					$data_insert = array("id" => "","streakID" => $streakID,"collected_1" => "","collected_date1" => "","collected_2" => "","collected_date2" => $value,"collected_3" => "","collected_date3" => "","collected_4" => "","collected_date4" => "");
				}
				else if($stt == 3)
				{
					$data_insert = array("id" => "","streakID" => $streakID,"collected_1" => "","collected_date1" => "","collected_2" => "","collected_date2" => "","collected_3" => "","collected_date3" => $value,"collected_4" => "","collected_date4" => "");
				}
				else if($stt == 4)
				{
					$data_insert = array("id" => "","streakID" => $streakID,"collected_1" => "","collected_date1" => "","collected_2" => "","collected_date2" => "","collected_3" => "","collected_date3" => "","collected_4" => "","collected_date4" => $value);
				}
				
				$this->getModelTable('CollectedTable')->insertCollected($data_insert);
				
			}	
		}
		die(json_encode($result));
		
	}
	
	
	
	public function updateInvoiceChildAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();
		    $id_join =  $data['id_join'];
		    $value =  $data['value'];
		    $date_filter =  $data['date_filter'];
		    if($date_filter == "")
		    {
				$current_month = date('Y-m');
				$date_filter = date( "Y-m", strtotime( "$current_month - 1 month" ));    
		    }
		   				
				$value_vnd = $value*22500;
				
				$array_update = array(
					"invoice_value" => $value,
					"invoice_vnd" => $value_vnd
				);
				$array_where = array(
					"id_join" => $id_join,
					"date" => $date_filter
				);

				$result['data'] = $date_filter;				
				$this->getModelTable('KetoanChildTable')->updateChild($array_update,$array_where);
 
		}

		die(json_encode($result));
		
	}
	
	
	public function updateInvoiceParentAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();
		    $id_join_parent =  $data['id_join_parent'];
		    $streakID = explode("-",$id_join_parent);
			$streakID = $streakID[0];
		    $value =  $data['value'];
		    $date_filter =  $data['date_filter'];
		    if($date_filter == "")
		    {
				$current_month = date('Y-m');
				$date_filter = date( "Y-m", strtotime( "$current_month - 1 month" ));    
		    }
		    				
				$value = str_replace(",","",$value);
								
				$value_vnd = $value*22500;
				
				$array_update = array(
					"invoice_value" => $value,
					"invoice_vnd" => $value_vnd
				);
				$array_where = array(
					"streakID" => $streakID,
					"date" => $date_filter
				);
			
				$result['data'] = $total;				
				$this->getModelTable('KetoanTable')->updateParent($array_update,$array_where);
				
		}

		die(json_encode($result));
		
	}
	
	
	
	public function updateActualSoldValueVndChildAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();
		    $id_join =  $data['id_join'];
		    $value =  $data['value'];
		    $date_filter =  $data['date_filter'];
		    if($date_filter == "")
		    {
				$current_month = date('Y-m');
				$date_filter = date( "Y-m", strtotime( "$current_month - 1 month" ));    
		    }
		    $date_filter_last = date( "Y-m", strtotime( "$date_filter - 1 month" ));
		    
		    $streaks_child_last_month = $this->getModelTable('KetoanChildTable')->getActualLastMonth_Child($id_join,$date_filter);
		    
		   
		    		    
		    	if(count($streaks_child_last_month) > 0)
		    	{
		    
				    $child_actual_sold_value_vnd_last_month = $streaks_child_last_month[0]['actual_sold_value_vnd'];
					$child_actual_sold_value_vnd_last_month = str_replace(",","",$child_actual_sold_value_vnd_last_month);	
								
					$value = str_replace(",","",$value);
					$total = $child_actual_sold_value_vnd_last_month + $value;	
				}
				else
				{
					$total = $value;
				}
							
					
				$array_update = array(
					"actual_sold_value_vnd" => $total
				);
				$array_where = array(
					"id_join" => $id_join,
					"date" => $date_filter
				);

				$result['data'] = $date_filter;				
				$this->getModelTable('KetoanChildTable')->updateChild($array_update,$array_where);
 
		}

		die(json_encode($result));
		
	}
	
	public function updateActualSoldValueVndAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();
		    $id_join_parent =  $data['id_join_parent'];
		    $streakID = explode("-",$id_join_parent);
			$streakID = $streakID[0];
		    $value =  $data['value'];
		    $date_filter =  $data['date_filter'];
		    if($date_filter == "")
		    {
				$current_month = date('Y-m');
				$date_filter = date( "Y-m", strtotime( "$current_month - 1 month" ));    
		    }
		    //$date_filter_last = date( "Y-m", strtotime( "$date_filter - 1 month" ));
		    
		    $streaks_last_month = $this->getModelTable('KetoanTable')->getActualLastMonth($streakID,$date_filter);
		    if(count($streaks_last_month) > 0)
		    {
			    $actual_sold_value_vnd_last_month = $streaks_last_month[0]['actual_sold_value_vnd'];
				$actual_sold_value_vnd_last_month = str_replace(",","",$actual_sold_value_vnd_last_month);				
				$value = str_replace(",","",$value);
				$result['last_actual_sold'] = $actual_sold_value_vnd_last_month;
				$total = $actual_sold_value_vnd_last_month + $value;
				$result['data'] = $total;				
			}
			else
			{
				$total = $value;
			}
				
				$array_update = array(
					"actual_sold_value_vnd" => $total

				);
				$array_where = array(
					"streakID" => $streakID,
					"date" => $date_filter
				);
			
								
				$this->getModelTable('KetoanTable')->updateParent($array_update,$array_where);
				
		}

		die(json_encode($result));
		
	}
	 
	public function updateActualCostChildAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();
		    $id_join =  $data['id_join'];
		    $value =  $data['value'];
		    $date_filter =  $data['date_filter'];
		    if($date_filter == "")
		    {
				$current_month = date('Y-m');
				$date_filter = date( "Y-m", strtotime( "$current_month - 1 month" ));    
		    }
		    //$date_filter_last = date( "Y-m", strtotime( "$date_filter - 1 month" ));
		    
		    $streaks_child_last_month = $this->getModelTable('KetoanChildTable')->getActualLastMonth_Child($id_join,$date_filter);
		    
		    
		    	if(count($streaks_child_last_month) > 0)
		    	{
			    $child_actual_cost_usd_last_month = $streaks_child_last_month[0]['actual_cost_usd'];
				$child_actual_cost_usd_last_month = str_replace(",","",$child_actual_cost_usd_last_month);				
				$value = str_replace(",","",$value);
				$total = $child_actual_cost_usd_last_month + $value;				
				}
				else
				{
					$total = $value;
				}
				$total_vnd = $total*22500;
				
				
				$array_update = array(
					"actual_cost_usd" => $total,
					"actual_cost_vnd" => $total_vnd
				);
				$array_where = array(
					"id_join" => $id_join,
					"date" => $date_filter
				);

				$result['data'] = $date_filter;				
				$this->getModelTable('KetoanChildTable')->updateChild($array_update,$array_where);
 
		}

		die(json_encode($result));
		
	}
	
	public function updateActualCostAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();
		    $id_join_parent =  $data['id_join_parent'];
		    $streakID = explode("-",$id_join_parent);
			$streakID = $streakID[0];
		    $value =  $data['value'];
		    $date_filter =  $data['date_filter'];
		    if($date_filter == "")
		    {
				$current_month = date('Y-m');
				$date_filter = date( "Y-m", strtotime( "$current_month - 1 month" ));    
		    }
		    //$date_filter_last = date( "Y-m", strtotime( "$date_filter - 1 month" ));
		    $streaks_last_month = $this->getModelTable('KetoanTable')->getActualLastMonth($streakID,$date_filter);
		    if(count($streaks_last_month) > 0)
		    {
		    			    
			    $actual_cost_usd_last_month = $streaks_last_month[0]['actual_cost_usd'];
				$actual_cost_usd_last_month = str_replace(",","",$actual_cost_usd_last_month);				
				$value = str_replace(",","",$value);
				$total = $actual_cost_usd_last_month + $value;				
			}
			else
			{
				$total = $value;
			}	
			$total_vnd = $total*22500;
				
				$array_update = array(
					"actual_cost_usd" => $total,
					"actual_cost_vnd" => $total_vnd
				);
				$array_where = array(
					"streakID" => $streakID,
					"date" => $date_filter
				);
			
				$result['data'] = $total;				
				$this->getModelTable('KetoanTable')->updateParent($array_update,$array_where);
				
		}

		die(json_encode($result));
		
	}

	public function updateEntertainmentAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();
		    $id_join_parent =  $data['id_join_parent'];
		    $streakID = explode("-",$id_join_parent);
			$streakID = $streakID[0];
		    $value =  $data['value'];
		    $date_filter =  $data['date_filter'];
		    if($date_filter == "")
		    {
				$current_month = date('Y-m');
				$date_filter = date( "Y-m", strtotime( "$current_month - 1 month" ));    
		    }
		    
		    //$date_filter_last = date( "Y-m", strtotime( "$date_filter - 1 month" ));
		    
		    $streaks_last_month = $this->getModelTable('KetoanTable')->getActualLastMonth($streakID,$date_filter);
		    
		    	if(count($streaks_last_month) > 0){
			    	
				    $entertainment_last_month1 = $streaks_last_month[0]['entertainment'];
					$entertainment_last_month = str_replace(",","",$entertainment_last_month1);				
					$value = str_replace(",","",$value);
					$total = $entertainment_last_month + $value;
					//$result['data'] = $total;
		    	}
		    	else
		    	{
			    	$total = $value;
		    	}			
								
				$array_update = array(
					"entertainment" => $total					
				);
				$array_where = array(
					"streakID" => $streakID,
					"date" => $date_filter
				);
			
							
				$this->getModelTable('KetoanTable')->updateParent($array_update,$array_where);
				
		}

		die(json_encode($result));
		
	}
	
	public function updateLobbyAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();
		    $id_join_parent =  $data['id_join_parent'];
		    $streakID = explode("-",$id_join_parent);
			$streakID = $streakID[0];
		    $value =  $data['value'];
		    $date_filter =  $data['date_filter'];
		    if($date_filter == "")
		    {
				$current_month = date('Y-m');
				$date_filter = date( "Y-m", strtotime( "$current_month - 1 month" ));    
		    }
		    		
		    	//$date_filter_last = date( "Y-m", strtotime( "$date_filter - 1 month" ));
		    
				$streaks_last_month = $this->getModelTable('KetoanTable')->getActualLastMonth($streakID,$date_filter);
				if(count($streaks_last_month) > 0){
					
				    $lobby_last_month1 = $streaks_last_month[0]['lobby'];
					$lobby_last_month = str_replace(",","",$lobby_last_month1);				
					$value = str_replace(",","",$value);
					$total = $lobby_last_month + $value;
				}
				else
				{
					$total = $value;
				}				
								
				$array_update = array(
					"lobby" => $total 					
				);
				$array_where = array(
					"streakID" => $streakID,
					"date" => $date_filter
				);
			
							
				$this->getModelTable('KetoanTable')->updateParent($array_update,$array_where);
				
		}

		die(json_encode($result));
		
	}
	
	
	
	
	public function updateClientAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();
		    $key_match =  md5($data['key_match']);		    
		    $id_client =  $data['id_client'];
		    
		    $check = $this->getModelTable('ClientMatchCampaignTable')->checkKeymatch($key_match);

		   	
			if($check == true)
			{		
				
				$array_update = array(
					'clientID' => "$id_client"					
				);
				$array_where = array(
					"key_match" => "$key_match"
				);
				
						
				$this->getModelTable('ClientMatchCampaignTable')->updateKeymatch($array_update,$array_where);
			}
			else
			{
				
					$data_insert = array("id" => "","key_match" => $key_match,"clientID" => $id_client);
					$this->getModelTable('ClientMatchCampaignTable')->insertKeymatch($data_insert);
			}			    		    		    		    

		}

		die(json_encode($result));
		
	}
	
	public function clearClientAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();
		    $key_match =  md5($data['key_match']);		    
					
				$array_update = array(
					"clientID" => ""					
				);
				$array_where = array(
					"key_match" => $key_match					
				);
			
							
				$this->getModelTable('ClientMatchCampaignTable')->updateKeymatch($array_update,$array_where);
				
		}
		
		die(json_encode($result));
		
	}
	
	public function updateSupplierAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();
		    $key_match =  md5($data['key_match']);		    
		    $id_supplier =  $data['id_supplier'];
		    
		    $check = $this->getModelTable('SupplierMatchCampaignTable')->checkKeymatch($key_match);

		   	
			if($check == true)
			{		
				
				$array_update = array(
					'supplierID' => "$id_supplier"					
				);
				$array_where = array(
					"key_match" => "$key_match"
				);
				
						
				$this->getModelTable('SupplierMatchCampaignTable')->updateKeymatch($array_update,$array_where);
			}
			else
			{
				
					$data_insert = array("id" => "","key_match" => $key_match,"supplierID" => $id_supplier);
					$this->getModelTable('SupplierMatchCampaignTable')->insertKeymatch($data_insert);
			}			    		    		    		    

		}

		die(json_encode($result));
		
	}
	 public function clearSupplierAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();
		    $key_match =  md5($data['key_match']);		    
					
				$array_update = array(
					"supplierID" => ""					
				);
				$array_where = array(
					"key_match" => $key_match					
				);
			
							
				$this->getModelTable('SupplierMatchCampaignTable')->updateKeymatch($array_update,$array_where);
				
		}
		
		die(json_encode($result));
		
	}
	
	
	public function updateCommissionStatusMediaAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();		   
			$streakID =  $data['streakID'];			   
		    $value =  $data['value'];	
		    $date_paid =  $data['date_paid'];	   		   		   						
			
			$check = $this->getModelTable('CommissionStatusMediaTable')->checkCommission($streakID,$date_paid);
				
			if(count($check) > 0)
			{		
	
				$array_update = array(
					"status" => $value					
				);
				$array_where = array(
					"streakID" => $streakID,
					"date_paid" => $date_paid									
				);
				
						
				$this->getModelTable('CommissionStatusMediaTable')->updateCommission($array_update,$array_where);
			}
			else
			{
				
				$data_insert = array("id" => "","streakID" => $streakID,"status" => $value,"date_paid" => $date_paid);
	
				
				$this->getModelTable('CommissionStatusMediaTable')->insertCommission($data_insert);
				
			}	
		}
		die(json_encode($result));
		
	}
	
	public function updateCommissionStatusSaleAction()
	{
		$result = array('status' => 'error', 'message' => 'There was some error. Try again.');
		$request = $this->getRequest();

	    if($request->isPost()){
		    $data = $request->getPost();		   
			$streakID =  $data['streakID'];			   
		    $value =  $data['value'];	
		    $date_paid =  $data['date_paid'];	   		   		   						
			
			$check = $this->getModelTable('CommissionStatusSaleTable')->checkCommission($streakID,$date_paid);
			
				
			if(count($check) > 0)
			{		
	
				$array_update = array(
					"status" => $value					
				);
				$array_where = array(
					"streakID" => $streakID,
					"date_paid" => $date_paid									
				);
				
						
				$this->getModelTable('CommissionStatusSaleTable')->updateCommission($array_update,$array_where);
			}
			else
			{
								
				$data_insert = array("id" => "","streakID" => $streakID,"status" => $value,"date_paid" => $date_paid);
	
				
				$this->getModelTable('CommissionStatusSaleTable')->insertCommission($data_insert);
				
			}	
			
		}
		die(json_encode($result));
		
	}
	
}



