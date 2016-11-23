<?php
namespace Report;

return array(
    'router' => array(
        'routes' => array(
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'report' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/report',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Report\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'defaults' => array(
                    'controller' => 'Report\Controller\Index',
                    'action' => 'index',
                ),
                'may_terminate' => true,
                'child_routes' => array(
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
                            'route' => '/ketoan[/][:action][/:id][/page/:page]',
                            'constraints' => array(
                                'action' => '[a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Report\Controller\KeToan',
                                'action' => 'index',                                
                            ),
                        ),
                    ),                    
                ),
            ),           
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Report\Controller\Index' => 'Report\Controller\IndexController',
            'Report\Controller\KeToan' => 'Report\Controller\KeToanController',
                        
        ),
    ),
	'view_helpers' => array(
		'factories' => array(
			'formelementerrors' => function($vhm) {
				$fee = new \Zend\Form\View\Helper\FormElementErrors();
				$fee->setAttributes([
					'style' => 'color:red'
				]);
				return $fee;
			}
		)
	),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'template_map' => array(
            'report/layout' => __DIR__ . '/../view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
