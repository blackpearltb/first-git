<?php

namespace Report;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Report\Model\Report;
use Report\Model\KetoanTable;
use Report\Model\KetoanChildTable;
use Report\Model\TotalTable;
use Report\Model\TotalChildTable;
use Report\Model\CollectedTable;
use Report\Model\ClientsTable;
use Report\Model\ClientMatchCampaignTable;
use Report\Model\SuppliersTable;
use Report\Model\SupplierMatchCampaignTable;
use Report\Model\CommissionStatusMediaTable;
use Report\Model\CommissionStatusSaleTable;
use Report\Model\UsersTable;
use Report\Model\FileStreakTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    
    // Add this method:
     public function getServiceConfig()
     {
         return array(
             'factories' => array(
                 'Report\Model\KetoanTable' =>  function($sm) {
                     $tableGateway = $sm->get('KetoanTableGateway');
                     $table = new KetoanTable($tableGateway);
                     return $table;
                 },
                 'KetoanTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                    // $resultSetPrototype->setArrayObjectPrototype(new Report());
                     return new TableGateway('streak_log_ketoan_parent', $dbAdapter, null, $resultSetPrototype);
                 },
                 'Report\Model\KetoanChildTable' =>  function($sm) {
                     $tableGateway = $sm->get('KetoanChildTableGateway');
                     $table = new KetoanChildTable($tableGateway);
                     return $table;
                 },
                 'KetoanChildTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                    // $resultSetPrototype->setArrayObjectPrototype(new Report());
                     return new TableGateway('streak_log_ketoan', $dbAdapter, null, $resultSetPrototype);
                 },
                 
                 'Report\Model\TotalTable' =>  function($sm) {
                     $tableGateway = $sm->get('TotalTableGateway');
                     $table = new TotalTable($tableGateway);
                     return $table;
                 },
                 'TotalTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                    // $resultSetPrototype->setArrayObjectPrototype(new Report());
                     return new TableGateway('streak_log_data_parent', $dbAdapter, null, $resultSetPrototype);
                 },
                 
                 'Report\Model\TotalChildTable' =>  function($sm) {
                     $tableGateway = $sm->get('TotalChildTableGateway');
                     $table = new TotalChildTable($tableGateway);
                     return $table;
                 },
                 'TotalChildTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                    // $resultSetPrototype->setArrayObjectPrototype(new Report());
                     return new TableGateway('streak_log_data_child', $dbAdapter, null, $resultSetPrototype);
                 },
                 
                 'Report\Model\CollectedTable' =>  function($sm) {
                     $tableGateway = $sm->get('CollectedTableGateway');
                     $table = new CollectedTable($tableGateway);
                     return $table;
                 },
                 'CollectedTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                    // $resultSetPrototype->setArrayObjectPrototype(new Report());
                     return new TableGateway('tb_collected', $dbAdapter, null, $resultSetPrototype);
                 },
                 'Report\Model\ClientsTable' =>  function($sm) {
                     $tableGateway = $sm->get('ClientsTableGateway');
                     $table = new ClientsTable($tableGateway);
                     return $table;
                 },
                 'ClientsTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                    // $resultSetPrototype->setArrayObjectPrototype(new Report());
                     return new TableGateway('clients', $dbAdapter, null, $resultSetPrototype);
                 },
                 'Report\Model\SuppliersTable' =>  function($sm) {
                     $tableGateway = $sm->get('SuppliersTableGateway');
                     $table = new SuppliersTable($tableGateway);
                     return $table;
                 },
                 'SuppliersTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                    // $resultSetPrototype->setArrayObjectPrototype(new Report());
                     return new TableGateway('suppliers', $dbAdapter, null, $resultSetPrototype);
                 },
                 'Report\Model\UsersTable' =>  function($sm) {
                     $tableGateway = $sm->get('UsersTableGateway');
                     $table = new UsersTable($tableGateway);
                     return $table;
                 },
                 'UsersTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                    // $resultSetPrototype->setArrayObjectPrototype(new Report());
                     return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                 },
                 'Report\Model\FileStreakTable' =>  function($sm) {
                     $tableGateway = $sm->get('FileStreakTableGateway');
                     $table = new FileStreakTable($tableGateway);
                     return $table;
                 },
                 'FileStreakTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();                   
                     return new TableGateway('streak_all_file_drive', $dbAdapter, null, $resultSetPrototype);
                 },
                 'Report\Model\ClientMatchCampaignTable' =>  function($sm) {
                     $tableGateway = $sm->get('ClientMatchCampaignTableGateway');
                     $table = new ClientMatchCampaignTable($tableGateway);
                     return $table;
                 },
                 'ClientMatchCampaignTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();                   
                     return new TableGateway('client_match_campaign', $dbAdapter, null, $resultSetPrototype);
                 },                 
                 'Report\Model\SupplierMatchCampaignTable' =>  function($sm) {
                     $tableGateway = $sm->get('SupplierMatchCampaignTableGateway');
                     $table = new SupplierMatchCampaignTable($tableGateway);
                     return $table;
                 },
                 'SupplierMatchCampaignTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();                   
                     return new TableGateway('supplier_match_campaign', $dbAdapter, null, $resultSetPrototype);
                 },
                 'Report\Model\CommissionStatusMediaTable' =>  function($sm) {
                     $tableGateway = $sm->get('CommissionStatusMediaTableGateway');
                     $table = new CommissionStatusMediaTable($tableGateway);
                     return $table;
                 },
                 'CommissionStatusMediaTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();                   
                     return new TableGateway('commission_status_media', $dbAdapter, null, $resultSetPrototype);
                 },
                 'Report\Model\CommissionStatusSaleTable' =>  function($sm) {
                     $tableGateway = $sm->get('CommissionStatusSaleTableGateway');
                     $table = new CommissionStatusSaleTable($tableGateway);
                     return $table;
                 },
                 'CommissionStatusSaleTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();                   
                     return new TableGateway('commission_status_sale', $dbAdapter, null, $resultSetPrototype);
                 },
                 
             ),
         );                         
         
     }
}
