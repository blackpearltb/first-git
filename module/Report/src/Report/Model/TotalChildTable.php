<?php
namespace Report\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Adapter\Adapter;
	use Zend\Db\Sql\Select;
	use Zend\Db\Sql\Sql;
	use Zend\Db\Sql\Expression;
	use Zend\Db\ResultSet\ResultSet;
	use Zend\Db\Sql\Predicate;
	
 class TotalChildTable extends GeneralTable
 {
     protected $tableGateway;
     protected $table   = 'streak_log_data_child';    
	 protected $primary = 'id';
	 protected $adapter = null;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
         $this->adapter = $this->tableGateway->getAdapter();
     }
     
    
   public function getStreakChannel($streakID,$date_filter) {     
          
     $results = null;     
      
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);
              
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
 
 }