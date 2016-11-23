<?php

namespace Report\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
class BackEndController extends AbstractActionController
{
    protected $data_view = array();
    protected $intPage = 0;
    protected $intPageSize = 10;
    public function __construct()
    {
    }

    public function onDispatch(\Zend\Mvc\MvcEvent $e) { 
	  
	 
	  if(!isset($_SESSION['REPORTMEMBER'])){
            unset($_SESSION['REPORTMEMBER']);
            $request = $this->getRequest();
            if($request->isXmlHttpRequest()){                
                echo json_encode(array(
                    'success' => FALSE,
                    'msg' => 'Please login first!',
                    'login'=>false,
                ));
                die();
            }
            header("Location: http://streak.urekamedia.com/streak_new/public/report/login");
            exit();
        }
	    
	  $route = $e->getRouteMatch();
        if (!defined('MODULE_NAME'))
            define('MODULE_NAME', str_replace('\controller', '', strtolower(__NAMESPACE__)));
        if (!defined('CONTROLLER_NAME'))
            define('CONTROLLER_NAME', str_replace(strtolower(__NAMESPACE__ . '\\'), '', strtolower($this->getEvent()->getRouteMatch()->getParam('controller'))));

        if (!defined('ACTION_NAME'))
            define('ACTION_NAME', $route->getParam('action', 'index'));
        return parent::onDispatch ( $e );
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


    public function myRedirect(){
        $redirectUrl = $this->getRequest()->getHeader('referer');
        $redirectUrl = str_replace('Referer:', '', $redirectUrl);    
        $this->redirect()->toUrl(trim($redirectUrl));
    }

}