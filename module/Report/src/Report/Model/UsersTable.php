<?php
namespace Report\Model;

 use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Predicate;
	
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
 class UsersTable extends GeneralTable{
 
     protected $tableGateway;
     protected $table   = 'users';
	 protected $primary = 'id';
	 protected $adapter = null;
     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
         $this->adapter = $this->tableGateway->getAdapter();
     }
     
     
    public function getEmailLeader($parent_id_sale) {     
     
     $results = null;     
      
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);
        
      $select = $sql->select()->from($this->table);
   
       
	  $select->where("id = '$parent_id_sale'");	  	  	      
       
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
    
    
    public function login(Login $login)
    {
	    
        try {
            $user = $this->getUser($login->email, $login->password);
            
            $_SESSION['REPORTMEMBER'] = array(
                'id' => $user['id'],
                'email' => $user['email'],
                'fullname' => $user['fullname'],
                'password' => $user['password'],
                'type' => $user['type'],
                'position' => $user['position'],
                'parent_id' => $user['parent_id']
                              
            );	
			
            if ($login->rememberme) {
                setcookie("REPORTMEMBER", json_encode($_SESSION['REPORTMEMBER']), time() + 3600 * 24 * 30);
            }
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }
    
    public function getUsersTeam($id_user)
    {
	    $results = null;     
      
	      $adapter = $this->tableGateway->getAdapter();
	      $sql = new Sql($adapter);
	        
	      $select = $sql->select()->from($this->table)->columns(array('email'));
	   
	       
		  $select->where("parent_id = '$id_user'");	  	  	      
	       
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
    
    public function getUser($email, $password)
    {	    
        try {

			$results = null;     
      
		     $adapter = $this->tableGateway->getAdapter();
            $sql = new Sql($adapter);			        
		    $select = $sql->select()->from($this->table);
                        
            $select->where(array(
                'email' => $email,
                'password' => $password
                
            ));		
            $select->limit(1);
            
                       
            $selectString = $sql->getSqlStringForSqlObject($select);
            $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
            $results = $results->current();
            if (!$results) {
	           
                throw new \Exception('Username or password does not correct');
            }
            return (array)$results;
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }
    
    public function getSeniors($type) {     
     
     $results = null;     
      
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);        
      $select = $sql->select()->from($this->table);          
	  $select->where(array("type" => "$type","position" => "senior"));	  	  	      

       
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
    
    public function getLeader($id) {     
     
     $results = null;     
      
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);        
      $select = $sql->select()->from($this->table)->columns(array('position','fullname','email','password','confirm-password' => 'password','senior' => 'parent_id','id'));          
	  $select->where(array("id" => "$id"));	  	  	      

       
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
    
    public function getUsers($type) {     
     
     $results = null;     
      
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);        
      $select = $sql->select()->from($this->table);          
	  $select->where(array("type" => "$type"));	  	  	      
      $select->where("position != 'admin'");
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
      
        
    public function getParentID($email) {     
     
     $results = null;     
      
      $adapter = $this->tableGateway->getAdapter();
      $sql = new Sql($adapter);
        
      $select = $sql->select()->from($this->table);
   
       
	  $select->where("email = '$email'");	  	  	      
       
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
    
    
    public function changePassword($oldPassword, $newPassword, $renewPassword){
	        
        try{
            if($oldPassword == ""){
	            	             
                return 'Please fill your old password!';                
            }
            if($oldPassword != $_SESSION['REPORTMEMBER']['password']){
	            
                return 'Your old password is wrong, please try again!';
            }
            if($newPassword == "" || $renewPassword == ""){
                return 'Please fill new password or re-new password';
            }
            if($newPassword != $renewPassword){
                return 'Re-new password and new password do not match';
            }
            
                       
            $adapter = $this->tableGateway->getAdapter();
            $sql = new Sql($adapter);
            $update = $sql->update();
            $update->table('users');
            $update->set(array(
                'password' => $newPassword,
            ));
            $update->where(array(
                'id' => $_SESSION['REPORTMEMBER']['id'],
            ));
            $updateString = $sql->getSqlStringForSqlObject($update);
            $adapter->query($updateString, $adapter::QUERY_MODE_EXECUTE);
            $_SESSION['REPORTMEMBER']['password'] = $newPassword;
            return "";
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }
    
	public function updateUser($array_update,$array_where){
		
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

    
    public function insertUser($data){
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
    
    public function checkUser($email)
    {	    
        try {

			$results = null;     
      
		     $adapter = $this->tableGateway->getAdapter();
            $sql = new Sql($adapter);			        
		    $select = $sql->select()->from($this->table);
                        
            $select->where(array(
                'email' => $email
                
                
            ));		
            $select->limit(1);
            
                       
            $selectString = $sql->getSqlStringForSqlObject($select);
            $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
            return $results->current();            
           
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }
    
    
  
 }