<?php
namespace Report\Model;

 use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
	use Zend\Db\Sql\Select;
	use Zend\Db\Sql\Sql;
	use Zend\Db\Sql\Expression;
	use Zend\Db\ResultSet\ResultSet;
	use Zend\Db\Sql\Predicate;
	//use Zend\Db\Sql\Predicate\Like
 class SuppliersTable extends GeneralTable
 {
     protected $tableGateway;
     protected $table   = 'suppliers';
	 protected $primary = 'id';
	 protected $adapter = null;
     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
         $this->adapter = $this->tableGateway->getAdapter();
     }
     
    public function getSuppliers() {     
     
         
     $results = null;     
      
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);
          
      $select = $sql->select()->from($this->table)->columns(array('value' => 'name','data' => 'id'));   
     
 
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
    
     public function getAll() {  
	    $results = null;     
      
	      $adapter = $this->tableGateway->getAdapter();
	      $sql = new Sql($adapter);	          	      
	      $select = $sql->select()->from($this->table);           
	      try{
	
	        $selectString = $sql->getSqlStringForSqlObject($select);
	        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE );
	        $results = $results->toArray();
	      }catch(\Exception $ex){
	       throw new \Exception($ex->getMessage());
	      }
	            
	     return $results;
	}
    
    public function checksupplier($name)
    {
	    $results = null;     
      
	      $adapter = $this->tableGateway->getAdapter();
	      $sql = new Sql($adapter);
	              
	         
		  $select = $sql->select()->from($this->table);	      	     	           	    
	      $select->where("name = '$name'");
		  
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
    
    public function updateSupplier($array_update,$array_where){
		
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
    
    public function insertSupplier($data){
        try{
            $connection = $this->adapter->getDriver()->getConnection();
            $connection->beginTransaction();
            $this->tableGateway->insert($data);
            $connection->commit();
            return $this->tableGateway->getLastInsertValue();
        }catch (\Exception $ex){         
            $connection->rollback();   
            if(IS_DEMO || 1==1){
                throw new \Exception($ex->getMessage());
            }else{
                return false;
            }
        }
    }
    
    public function getSupplier($id) {     
     
     $results = null;     
      
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);
              
	  $select = $sql->select()->from($this->table);
           
	  $select->where("id = '$id'");	 
	    
      try{

        $selectString = $sql->getSqlStringForSqlObject($select);
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE );      
        return $results->current();

      }catch(\Exception $ex){
       throw new \Exception($ex->getMessage());
      }
     //}            
     return $results;
    }
    
    
    
    
    /* 
    public function getSupplier($key_match) {     
     
     
     $results = null;     
      
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);
          
      $select = $sql->select()->from($this->table);    
      //$select->where->like("key_match", "%{$key_match}%");
	  $select->where("key_match = '$key_match'");
	 
	  
      try{

        $selectString = $sql->getSqlStringForSqlObject($select);
        //echo $selectString;
        //die();
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE );
        $results = $results->toArray();
      }catch(\Exception $ex){
       throw new \Exception($ex->getMessage());
      }
     //}            
     return $results;
    }
    
    public function updateKeymatch($array_update,$array_where){
				
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
    */
  
 }