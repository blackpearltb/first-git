<?php
namespace Report\Controller;

use Report\Model\Login;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\ViewModel;
use Report\Model\User;
use Report\Form\LoginForm;

class LoginController extends AbstractActionController{

    private $data_view = array();

    public function indexAction(){
		
		
		if(isset($_SESSION['REPORTMEMBER'])){            
            header("Location: index");
            exit();
        }
		
        $form = new LoginForm();
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if($request->isPost()){
            $data = $request->getPost();
            $login = new Login();
            $form->setInputFilter($login->getInputFilter());
            $form->setData($data);
            if($form->isValid()){
                $login->exchangeArray($form->getData());
				
                try{
	               
                    $this->getModelTable('UsersTable')->login($login);                    
                    $this->redirect()->toRoute('report/index');
                }catch (\Exception $ex){
                    $this->data_view['error_message'] = $ex->getMessage();
                }
            }
        }
        $this->data_view['form'] = $form;
        $view = new ViewModel();
        $view->setTerminal(TRUE);
        $view->setVariables($this->data_view);
        return $view;
    }

	
    public function logoutAction(){
        if(isset($_SESSION['REPORTMEMBER'])){
            unset($_SESSION['REPORTMEMBER']);
            if (isset($_COOKIE['REPORTMEMBER'])) {
                unset($_COOKIE['REPORTMEMBER']);
                setcookie('REPORTMEMBER', null, -1);
            }
        }
		return $this->redirect()->toRoute('report/login');
    }

    public function getModelTable($name)
    {
        if (!isset($this->{$name})) {
            $this->{$name} = NULL;
        }
        if (!$this->{$name}) {
            $sm = $this->getServiceLocator();
            $this->{$name} = $sm->get('Report\Model\\' . $name);
        }
        return $this->{$name};
    }
}