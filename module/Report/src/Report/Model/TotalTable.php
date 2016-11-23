<?php
namespace Report\Model;

 use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
	use Zend\Db\Sql\Select;
	use Zend\Db\Sql\Sql;
	use Zend\Db\Sql\Expression;
	use Zend\Db\ResultSet\ResultSet;
	use Zend\Db\Sql\Predicate;
 class TotalTable extends GeneralTable
 {
     protected $tableGateway;
     protected $table   = 'streak_log_data_parent';
	 protected $primary = 'id';
	 protected $adapter = null;
     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
         $this->adapter = $this->tableGateway->getAdapter();
     }
     
     public function getAll($date_filter,$input_filter,$sortaction,$array_email = null) {     
     
     $results = null;     
      $columns[] = 'id_join';
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);
    
       $select = $sql->select()->from($this->table);
              
		$select->join('tb_collected', 'streak_log_data_parent.streakID = tb_collected.streakID', array("vat","remain","collected_1","collected_date1","collected_2","collected_date2","collected_3","collected_date3","collected_4","collected_date4"),'left');
       
	  	$select->where(
		  array(
		  "{$this->table}.date"=>$date_filter,		 		  		  
		  )
		);
		
			if(count($input_filter) > 0)
		    {
			  foreach($input_filter as $key => $value)
			  {
				  $select->where("{$this->table}.$key LIKE '%$value%'");
			  }
		    }
		    
		    if(count($array_email) > 0)
			  {	
				  if($_SESSION['REPORTMEMBER']['type'] == 'media')
				  {
				  	$select->where(array("{$this->table}.assigned_to" => $array_email));		  
				  }	 
				  else
				  {
				  	$select->where(array("{$this->table}.sale" => $array_email));
				  }
				  
			  }
		    	
		  	if($sortaction != "")
		  	{
				$sortaction = explode("-", $sortaction);
				$select->order("{$this->table}.$sortaction[0] $sortaction[1]");	
				
		  	}
		  	else
		  	{
			  	
	        	$select->order("{$this->table}.streakID DESC");   
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
    
    
    public function getAllDone($date_filter,$input_filter,$sortaction) {     
     
     $results = null;     
      $columns[] = 'id_join';
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);
    
       $select = $sql->select()->from($this->table);
              
		$select->join('tb_collected', 'streak_log_data_parent.streakID = tb_collected.streakID', array("vat","remain","collected_1","collected_date1","collected_2","collected_date2","collected_3","collected_date3","collected_4","collected_date4"),'left');
       
       $select->join('commission_status', 'streak_log_data_parent.streakID = commission_status.streakID', array("status"),'left');
       
	  	$select->where(
		  array(
		  "{$this->table}.date"=>$date_filter,		  
		  )
		);
		
		$select->where("{$this->table}.stage LIKE '%Done%'");
		
		
			if(count($input_filter) > 0)
		    {
			  foreach($input_filter as $key => $value)
			  {
				  $select->where("{$this->table}.$key LIKE '%$value%'");
			  }
		    }
		    		   
		    	
		  	if($sortaction != "")
		  	{
				$sortaction = explode("-", $sortaction);
				$select->order("{$this->table}.$sortaction[0] $sortaction[1]");	
				
		  	}
		  	else
		  	{
			  	
	        	$select->order("{$this->table}.streakID DESC");   
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
    
    
    public function getTotalComissionMedia($email,$lastday,$type) {     
     
     
     $results = null;     
     
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);
    
       $select = $sql->select()->from($this->table);
	   
	   $select->join('tb_collected', 'streak_log_data_parent.streakID = tb_collected.streakID', array("vat","remain","collected_1","collected_date1","collected_2","collected_date2","collected_3","collected_date3","collected_4","collected_date4"),'left');
       $select->join('users', 'streak_log_data_parent.assigned_to = users.email', array("email"),'left');
       $select->join('commission_status', 'streak_log_data_parent.streakID = commission_status.streakID', array("status"),'left');
       
       if($type == "media")
       {
		  	$select->where(
			  array(
			  "{$this->table}.assigned_to"=>$email,
			  "{$this->table}.date"=>$lastday,		  
			  )
			);
		}
		else
		{
			$select->where(
			  array(
			  "{$this->table}.sale"=>$email,
			  "{$this->table}.date"=>$lastday,		  
			  )
			);

		}
		$select->where("{$this->table}.stage LIKE '%Done%'");				
		
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
    
    
    public function getEmails($streakID) {     
     
     $results = null;     
      
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);
        
      $select = $sql->select()->from($this->table);
   
       
	  $select->where("streakID = '$streakID'");
	  	  	
       $select->order('date DESC');   
       
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