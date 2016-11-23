<?php
namespace Report\Controller;


use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Report\Model\Users;
use Report\Model\UsersTable;

class UserController extends BackEndController{

   
	
    public function changePasswordAction(){
		
		$result = "";
		$error = 2;
     
		$request = $this->getRequest();
		if($request->isPost()){
			$oldPassword = trim($request->getPost('oldpassword'));
			$newPassword = trim($request->getPost('password'));
			$renewPassword =  trim($request->getPost('repassword'));
						
			try{
				
				$result = $this->getModelTable('UsersTable')->changePassword($oldPassword, $newPassword, $renewPassword);
				
				if($result != "")
				{
					$error = 1;
				}
				else
				{
					$error = 0;
				}
			}catch (\Exception $ex){
				$this->viewModel->__set('error_message',$ex->getMessage());
			}
		}
		
		return new ViewModel(array(           
             'result' => $result,    
             'error' => $error,         
         ));
    }


}