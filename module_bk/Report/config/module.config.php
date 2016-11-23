<?php
namespace Report;

return array(
    'router' => array(
        'routes' => array( 
	        	'home' => array(
			      'type' => 'Zend\Mvc\Router\Http\Literal',
			      'options' => array(
			        'route'    => '/',
			        'defaults' => array(
			          'controller' => 'Report\Controller\Index',
			          'action'     => 'index',
			        ),
			      ),
			    ),
	         'report' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/report',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Report\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),                
                'defaults' => array(
                    'controller' => 'Report\Controller\Index',
                    'action' => 'index',
                ),
                'may_terminate' => true,
                'child_routes' => array(
	                'login' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/login[/][:action[/:id]]',
                            'constraints' => array(
                                'action' => '[a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Report\Controller\Login',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'index' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/index[/][:action[/:id]]',
                            'constraints' => array(
                                'action' => '[a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Report\Controller\Index',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'ketoan' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/ketoan[/][:action[/:id]]',
                            'constraints' => array(
                                'action' => '[a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Report\Controller\Ketoan',
                                'action' => 'index',                                
                            ),
                        ),
                    ),  
                    'media' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/media[/][:action[/:id]]',
                            'constraints' => array(
                                'action' => '[a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Report\Controller\Media',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'sale' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/sale[/][:action[/:id]]',
                            'constraints' => array(
                                'action' => '[a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Report\Controller\Sale',
                                'action' => 'index',
                            ),
                        ),
                    ), 
                    'user' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/user[/][:action[/:id]]',
                            'constraints' => array(
                                'action' => '[a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Report\Controller\User',
                                'action' => 'index',
                            ),
                        ),
                    ),                
                ),
            ),                      
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Report\Controller\Index' => 'Report\Controller\IndexController',
            'Report\Controller\Ketoan' => 'Report\Controller\KetoanController',     
            'Report\Controller\User' => 'Report\Controller\UserController',        
            'Report\Controller\Media' => 'Report\Controller\MediaController',
            'Report\Controller\Sale' => 'Report\Controller\SaleController',
            'Report\Controller\Login' => 'Report\Controller\LoginController',
        ),
    ),   
    'view_manager' => array(
	    'template_path_stack' => array(
	        __DIR__ . '/../view',
	    ),
	),
    
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
