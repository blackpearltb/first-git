<?php
namespace Report\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Adapter\Adapter;
	use Zend\Db\Sql\Select;
	use Zend\Db\Sql\Sql;
	use Zend\Db\Sql\Expression;
	use Zend\Db\ResultSet\ResultSet;
	use Zend\Db\Sql\Predicate;
	
 class KetoanChildTable extends GeneralTable
 {
     protected $tableGateway;
     protected $table   = 'streak_log_ketoan';    
	 protected $primary = 'id';
	 protected $adapter = null;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
         $this->adapter = $this->tableGateway->getAdapter();
     }
     
    
    public function getAll($array_columns_choice,$date_filter) {     
          
     $results = null;     
      
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);
      
      $select = $sql->select()->from($this->table)->columns($array_columns_choice);
              
       
	  $select->where("date = '$date_filter'");
	  //$select->where("stage = 'Running'");
      try{
       	//$select->offset($this->_offset);
        //$select->limit($this->_limit);
        $selectString = $sql->getSqlStringForSqlObject($select);
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE );
        $results = $results->toArray();
      }catch(\Exception $ex){
       throw new \Exception($ex->getMessage());
      }
     //}            
     return $results;
    }
    
    public function getInvoiceChild($id_join) {
	    $results = null;     
      
	      $adapter = $this->tableGateway->getAdapter();
	      $sql = new Sql($adapter);
	              
		  $select = $sql->select()->from($this->table)->columns(array('invoice_value'));
	           		  
		  $select->where("id_join = '$id_join'");
		    
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
    
    public function getStreakChannel($streakID,$date_filter) {     
          
     $results = null;     
      
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);
       /*       
      if($array_columns_choice != 0)
      {
	      $array_columns_choice[] = "id_join";	     
	      $select = $sql->select()->from($this->table)->columns($array_columns_choice);
	      
	      
      }
      else
      {
	      $array_columns_choice[] = "id_join";
	      $select = $sql->select()->from($this->table);
      }
      */
      $select = $sql->select()->from($this->table);     
      $select->where("streakID = '$streakID'");
     // $select->where(array('streakID'=>$streakID,'date'=>$last_month));
  
	  $select->where("date = '$date_filter'");
	  //$select->where("stage = 'Running'");
      
      
      try{
       	//$select->offset($this->_offset);
        //$select->limit($this->_limit);
        $selectString = $sql->getSqlStringForSqlObject($select);
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE );
        $results = $results->toArray();
      }catch(\Exception $ex){
       throw new \Exception($ex->getMessage());
      }
     //}            
     return $results;
    }
    

    public function getActualLastMonth_Child($id_join,$date_filter)
    {
	    $results = null;     
      
	      $adapter = $this->tableGateway->getAdapter();
	      $sql = new Sql($adapter);
	              
	         
		  $select = $sql->select()->from($this->table);	      	     	           	    
	      
	      $select->where(array('id_join'=>$id_join));
	      $select->where("date < '$date_filter'");
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
    
	
	public function updateChild($array_update,$array_where){
		
		
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
	
	/*
	 private function _formatDataToSave($data) {
        return $data;
    }	
    public function add($data) {
        
        $data = $this->_formatDataToSave($data);
        $data = $this->filterColumns($data);

        $data['date_created'] = date('Y-m-d H:i:s');
        $id = $this->insertGeneral($data);            
        
        return $id;
    }

    public function edit($data) {
        if (!isset($data[$this->primary])){
            return false;
        }

        $id = $data[$this->primary];    
        unset($data[$this->primary]);


        $data = $this->_formatDataToSave($data);
        $data = $this->filterColumns($data);
        
                  
        return $this->updateGeneral($id, $data);
             
    }
    */
	 
 }