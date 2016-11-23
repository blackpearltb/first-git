<?php
/**
 * Created by PhpStorm.
 * User: viet
 * Date: 5/25/14
 * Time: 2:51 PM
 */
namespace Report\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;

class GeneralTable{
    protected $tableGateway;
    protected $cache = NULL;
    protected $table    = null;
    protected $primary  = null;
    protected $entity   = null;
    protected $metadata = null;
    protected $sql = null;
    protected $selectSql = null;
    protected $deleteSql = null;
    protected $adapter = null;

    public function __construct(TableGateway $tableGateway){
        // $this->tableGateway = $tableGateway;
        // $this->sql = new Sql($tableGateway->getAdapter());
        //$this->adapter = $tableGateway;
        //$this->adapter = $adapter;
        //$this->initialize();

        $this->tableGateway = $tableGateway;
        $this->sql = new Sql($tableGateway->getAdapter());
        $this->selectSql = $this->sql->select();
        $this->deleteSql = $this->sql->delete();
        $this->adapter = $this->tableGateway->getAdapter();
    }

    protected function getLastestId(){
        return $this->tableGateway->getLastInsertValue();
    }

    
    

    public function softUpdateData($ids,$data){
        return $this->tableGateway->update($data, array($this->getIdCol() => $ids));
    }


    

    protected function getModelTable($name){
    	if (!isset($this->{$name})) {
    		$this->{$name} = NULL;
    	}
    	if (!$this->{$name}) {
    		$sm = $this->getServiceLocator();
    		$this->{$name} = $sm->get('Report\Model\\' . $name);
    	}
    	return $this->{$name};
    }



    public function sqlQuery($select){
        try{
            $adapter = $this->tableGateway->getAdapter();
            if(is_string() ){
                $select = $this->sql->getSqlStringForSqlObject($select);
            }
            $results = $adapter->query($select, $adapter::QUERY_MODE_EXECUTE);
        }catch (\Exception $ex){
            if(IS_DEMO){
                throw new \Exception($ex->getMessage());
            }else{
                return null;
            }
        }
    }
    public function deleteGeneral($where){
        if(!$where){
            return null;
        }
        try{
            $this->tableGateway->delete($where);
        }catch (\Exception $ex){
            if(IS_DEMO){
                throw new \Exception($ex->getMessage());
            }else{
                return null;
            }
        }
    }

    
    protected function insertGeneral($data){
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

    public function updateGeneral($w, $data){
        try{
            $connection = $this->adapter->getDriver()->getConnection();
            $connection->beginTransaction();
            if(!is_array($w)){
	            $where = array($this->primary => $w);
            }else{
	            $where = $w;
            }
            $this->tableGateway->update($data, $where);
            $connection->commit();
            return true;
        }catch(\Exception $ex){
            $connection->rollback();
            if(IS_DEMO){
                die($ex->getMessage());
                //throw new \Exception($ex->getMessage());
            }else{
                return false;
            }
        }
    }
    
    public function fetchAllMy($select, $showSql = false, $isObject = false){
        try{
            $adapter = $this->tableGateway->getAdapter();
            $selectString = $this->sql->getSqlStringForSqlObject($select);
            if($showSql){
                echo $selectString;die;
            }
            $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
            if(!$isObject){
                return $results->toArray();    
            }
            return $results;
        }catch (\Exception $ex){
            if(IS_DEMO){
                throw new \Exception($ex->getMessage());
            }else{
                return null;
            }
        }
    }

    public function fetchRowMy($select){
        try{
            $adapter = $this->tableGateway->getAdapter();
            $selectString = $this->sql->getSqlStringForSqlObject($select);
            $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
            return $results->current();
        }catch (\Exception $ex){
            if(IS_DEMO){
                throw new \Exception($ex->getMessage());
            }else{
                return null;
            }
        }
    }    

    public function fetchAllGeneral($select, $showSql = false, $isObject = false){
        return $this->fetchAllMy($select, $showSql, $isObject);
    }

    public function fetchRowGeneral($select){
        return $this->fetchRowMy($select);
    }

    public function getColumnsInTable() {
        $columns = array();
        $adapter = $this->adapter;
        $query = "DESCRIBE {$this->table}";
        $columnObjects = $adapter->query($query, $adapter::QUERY_MODE_EXECUTE);
        foreach ($columnObjects as $columnObject) {
            $columns[] = $columnObject->Field;
        }
        return $columns;
    }

    public function filterColumns($data) {
        $dataFormat = array();

        $columns = $this->getColumnsInTable();

        foreach ($data as $column => $value) {
            if (in_array($column, $columns)) {
                $dataFormat[$column] = $value;
            }
        }
        return $dataFormat;
    }
}