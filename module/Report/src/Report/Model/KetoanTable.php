<?php
namespace Report\Model;

 use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
	use Zend\Db\Sql\Select;
	use Zend\Db\Sql\Sql;
	use Zend\Db\Sql\Expression;
	use Zend\Db\ResultSet\ResultSet;
	use Zend\Db\Sql\Predicate;
 class KetoanTable extends GeneralTable
 {
     protected $tableGateway;
     protected $table   = 'streak_log_ketoan_parent';
	 protected $primary = 'id';
	 protected $adapter = null;
     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
         $this->adapter = $this->tableGateway->getAdapter();
     }
     
     public function getAll($date_filter,$input_filter,$sortaction,$array_email = null) {     
     
     $results = null;     
      
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);    
      $select = $sql->select()->from($this->table);    
            
	  $select->where("date = '$date_filter'");
	  
	  
	  if(count($array_email) > 0)
	  {		 
		  if($_SESSION['REPORTMEMBER']['type'] == 'media')
		  {
		  	$select->where(array('assigned_to' => $array_email));		  
		  }
		  else if($_SESSION['REPORTMEMBER']['type'] == 'sale')
		  {
			  $select->where(array('sale' => $array_email));
		  }
	  }
	  	  	  	  
	  if(count($input_filter) > 0)
	  {
		  foreach($input_filter as $key => $value)
		  {
			  $select->where("$key LIKE '%$value%'");
		  }
	  }	
	  	if($sortaction != "")
	  	{
			$sortaction = explode("-", $sortaction);
			$select->order("$sortaction[0] $sortaction[1]");	
	  	}
	  	else
	  	{
        	$select->order('streakID DESC');   
        }
      try{

        $selectString = $sql->getSqlStringForSqlObject($select);
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE );
        $results = $results->toArray();
      }catch(\Exception $ex){
       throw new \Exception($ex->getMessage());
      }
     //}            
     return $results;
    }
    
    
    public function getInvoiceParent($streakID) {
	    $results = null;     
      
	      $adapter = $this->tableGateway->getAdapter();
	      $sql = new Sql($adapter);
	              
		  $select = $sql->select()->from($this->table)->columns(array('invoice_value'));
	           		  
		  $select->where("streakID = '$streakID'");
		    
	      try{
	
	        $selectString = $sql->getSqlStringForSqlObject($select);
	        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE );
	        $results = $results->toArray();
	      }catch(\Exception $ex){
	       throw new \Exception($ex->getMessage());
	      }
	     //}            
	     return $results;
	}    
    
    
    public function getChannel($streakID,$date_filter) {     
     
     $results = null;     
      
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);
              
	  $select = $sql->select()->from($this->table);
           
	  $select->where("date = '$date_filter'");
	  $select->where("streakID = '$streakID'");
	    
      try{

        $selectString = $sql->getSqlStringForSqlObject($select);
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE );
        $results = $results->toArray();
      }catch(\Exception $ex){
       throw new \Exception($ex->getMessage());
      }
     //}            
     return $results;
    }
    
    
    public function getStreakID() {     
     
     $results = null;     
      
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);
              
	  $select = $sql->select()->from($this->table)->columns(array('value' => 'streakID','data' => 'streakID'));
       $select->group('streakID');    	 
	    
      try{

        $selectString = $sql->getSqlStringForSqlObject($select);
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE );
        $results = $results->toArray();
      }catch(\Exception $ex){
       throw new \Exception($ex->getMessage());
      }
     //}            
     return $results;
    }
    
    
    public function getCampaigns($input_filter) {     
     
     $results = null;     
      
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);
              
	  $select = $sql->select()->from($this->table)->columns(array('streakID' => 'streakID','name' => 'name','sale' => 'sale','media' => 'assigned_to'));
              
      if(count($input_filter) > 0)
	  {
		  foreach($input_filter as $key => $value)
		  {
			  $select->where("$key LIKE '%$value%'");
		  }
	  }	
	  $select->where("streakID != 0");
	  $select->group('streakID');
      
	    
      try{
        $selectString = $sql->getSqlStringForSqlObject($select);
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE );
        $results = $results->toArray();
      }catch(\Exception $ex){
       throw new \Exception($ex->getMessage());
      }
     //}            
     return $results;
    }
    
        
    
    public function getMonths() {     
         
	     $results = null;     
	      
	      $adapter = $this->tableGateway->getAdapter();
	      $sql = new Sql($adapter);
	
		   $select = $sql->select()->from($this->table)->columns(array('date'));
	       $select->group('date');                 
	      try{
	        $selectString = $sql->getSqlStringForSqlObject($select);
	        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE );
	        $results = $results->toArray();
	      }catch(\Exception $ex){
	       throw new \Exception($ex->getMessage());
	      }
	     //}            
	     return $results;
    }
    
    
    
    public function getActualLastMonth($streakID,$date_filter)
    {
	    $results = null;     
      
	      $adapter = $this->tableGateway->getAdapter();
	      $sql = new Sql($adapter);
	              
	         
		  $select = $sql->select()->from($this->table);	      	     	           	    
	      //$select->where(array('streakID'=>$streakID,'date'=>$date_filter_last_month));
	      $select->where(array('streakID'=>$streakID));
	      $select->where("date < '$date_filter'");
		  $select->order('date DESC');
		  //$select->limit(0, 2);
	      try{	       
	        $selectString = $sql->getSqlStringForSqlObject($select);
	        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE );
	        $results = $results->toArray();
	      }catch(\Exception $ex){
	       throw new \Exception($ex->getMessage());
	      }
	     //}            
	     return $results;
    }
        
    
    public function updateParent($array_update,$array_where){
				
        try{
            $connection = $this->adapter->getDriver()->getConnection();
            $connection->beginTransaction();
                       
            $this->tableGateway->update($array_update,$array_where);
            $connection->commit();
           
            return true;
        }catch(\Exception $ex){
	        
            $connection->rollback();
            die($ex->getMessage());
            if(IS_DEMO){
                die($ex->getMessage());
                //throw new \Exception($ex->getMessage());
            }else{
                return false;
            }
        }
        
    }
    
    public function getMonthComissionMedia($emails,$filter_time,$type) {     
     
     if($filter_time == "")
		    {
			     $thismonth =  date( "Y-m");
			     $filter_time = date( "Y-m", strtotime( "$thismonth - 1 month" ));
		    }
          
     $results = null;          
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);
    
       $select = $sql->select()->from($this->table);
	   
	   $select->join('tb_collected', 'streak_log_ketoan_parent.streakID = tb_collected.streakID', array("vat","remain","collected_1","collected_date1","collected_2","collected_date2","collected_3","collected_date3","collected_4","collected_date4"),'left');       
       
       
       if($type == "media")
       {
	       //$select->join('commission_status', 'streak_log_ketoan_parent.streakID = commission_status.streakID', array("status" => "status_media"),'left');
	       
	       if($emails != ""){	       
	       		$select->where(array("{$this->table}.assigned_to"=>$emails));
			}
			
					
			//$select->join('users', 'streak_log_ketoan_parent.assigned_to = users.email', array("email"),'left');			
			$select->where(array("{$this->table}.date"=>$filter_time));
			$select->where("{$this->table}.stage LIKE '%Done%'");
		}
		else
		{
			//$select->join('commission_status', 'streak_log_ketoan_parent.streakID = commission_status.streakID', array("status" => "status_sale"),'left');	
			if($emails != ""){	       
	       		$select->where(array("{$this->table}.sale"=>$emails));
			}									
			$select->where(array("{$this->table}.date"=>$filter_time));

		}
						
		
      try{

        $selectString = $sql->getSqlStringForSqlObject($select);

        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE );
        $results = $results->toArray();
      }catch(\Exception $ex){
       throw new \Exception($ex->getMessage());
      }
     //}            
     return $results;
    }
    
    public function getLastMonthComissionMedia($streakID,$filter_time) {     
          
     $results = null;          
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);
    
       $select = $sql->select()->from($this->table);
	   $select->where(array('streakID'=>$streakID));
	      $select->where("date < '$filter_time'");
		  $select->order('date DESC');

			$select->where("stage LIKE '%Done%'");

      try{

        $selectString = $sql->getSqlStringForSqlObject($select);		
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE );
        $results = $results->toArray();
      }catch(\Exception $ex){
       throw new \Exception($ex->getMessage());
      }
     //}            
     return $results;
    }
    
    
    public function getMonthsComissionMedia($email,$filter_time,$type) {     
     
     
     $results = null;     
                  
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);
    
       $select = $sql->select()->from($this->table);
	   
	   $select->join('tb_collected', 'streak_log_ketoan_parent.streakID = tb_collected.streakID', array("vat","remain","collected_1","collected_date1","collected_2","collected_date2","collected_3","collected_date3","collected_4","collected_date4"),'left');
       $select->join('users', 'streak_log_ketoan_parent.assigned_to = users.email', array("email"),'left');
       $select->join('commission_status', 'streak_log_ketoan_parent.streakID = commission_status.streakID', array("status"),'left');
       
       if($type == "media")
       {
		  	$select->where(
			  array(
			  "{$this->table}.assigned_to"=>$email,			 
			  "{$this->table}.date = '{$filter_time}'",			  		  
			  )
			);	
			
			$select->where("{$this->table}.stage LIKE '%Done%'");		
		}
		else
		{
			$select->where(
			  array(
			  "{$this->table}.sale"=>$email,	
			  "{$this->table}.date = '{$filter_time}'",		  			  		  
			  )
			);

		}	
		
		//$select->group("{$this->table}.streakID");	
					

      try{

        $selectString = $sql->getSqlStringForSqlObject($select);

        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE );
        $results = $results->toArray();
      }catch(\Exception $ex){
       throw new \Exception($ex->getMessage());
      }
     //}            
     return $results;
    }
      	
	 
 }