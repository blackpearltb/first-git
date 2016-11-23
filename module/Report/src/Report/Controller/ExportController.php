<?php
namespace Streak\Controller;


use Zend\Mvc\Controller\AbstractActionController;


class ExportController extends AbstractActionController
{
	
			
	public function onDispatch(\Zend\Mvc\MvcEvent $e) {  
	  $route = $e->getRouteMatch();
        if (!defined('MODULE_NAME'))
            define('MODULE_NAME', str_replace('\controller', '', strtolower(__NAMESPACE__)));
        if (!defined('CONTROLLER_NAME'))
            define('CONTROLLER_NAME', str_replace(strtolower(__NAMESPACE__ . '\\'), '', strtolower($this->getEvent()->getRouteMatch()->getParam('controller'))));

        if (!defined('ACTION_NAME'))
            define('ACTION_NAME', $route->getParam('action', 'index'));
        return parent::onDispatch ( $e );
	 }
	
   public function indexAction(){
	    $result = array();
	    $result['data'] = "test";
	    /*
	    set_time_limit( 0 );
        //$model = new Default_Model_SomeModel();
        //$data = $model->getData();
        $data = $this->getStreakTable()->getMonths();
        $filename = APPLICATION_PATH . "/tmp/excel-" . date( "m-d-Y" ) . ".xls";
        $realPath = realpath( $filename );
        if ( false === $realPath )
        {
            touch( $filename );
            chmod( $filename, 0777 );
        }
        $filename = realpath( $filename );
        $handle = fopen( $filename, "w" );
        $finalData = array();
        
        foreach ( $data AS $row )
        {
            $finalData[] = array(
                utf8_decode( $row ), // For chars with accents.
                
            );
        }
        foreach ( $finalData AS $finalRow )
        {
            fputcsv( $handle, $finalRow, "\t" );
        }
        fclose( $handle );
        
        $this->_helper->layout->disableLayout();
        //$this->_helper->viewRenderer->setNoRender();
        /*
        $this->getResponse()->setRawHeader( "Content-Type: application/vnd.ms-excel; charset=UTF-8" )
            ->setRawHeader( "Content-Disposition: attachment; filename=excel.xls" )
            ->setRawHeader( "Content-Transfer-Encoding: binary" )
            ->setRawHeader( "Expires: 0" )
            ->setRawHeader( "Cache-Control: must-revalidate, post-check=0, pre-check=0" )
            ->setRawHeader( "Pragma: public" )
            ->setRawHeader( "Content-Length: " . filesize( $filename ) )
            ->sendResponse();
        readfile( $filename ); exit();
	    */
	    die(json_encode($result));
	}
   
}



