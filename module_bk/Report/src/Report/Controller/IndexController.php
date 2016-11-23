<?php
namespace Report\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\ViewModel;


class IndexController extends AbstractActionController{

    private $data_view = array();

    public function indexAction(){
						
        return new ViewModel(array(                       
         )); 
    }

}