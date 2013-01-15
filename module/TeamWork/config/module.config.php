<?php
return array(
	'router' => array(
		'routes' => array(
			'home' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route'    => '/',
					'defaults' => array(
				       	'__NAMESPACE__' => 'TeamWork\Controller',
                  		'controller' => 'index',
                   		'action' => 'index',
					),
				),
			),
			'Team' => array(
	        	'type' => 'Literal',
	            'options' => array(
	                'route' => '/TeamWork',
	                'defaults' => array(
	                    '__NAMESPACE__' => 'TeamWork\Controller',
	                    'controller' => 'index',
	                    'action' => 'index',
	                ),
	            ),
	            'may_terminate' => true,
	            'child_routes' => array(
	                'default' => array(
	                    'type' => 'Segment',
	                    'options' => array(
	                        'route' => '/[:controller[/:action]]',
	                        'constraints' => array(
	                            'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
	                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
	                        ),
	                    ),
	                ),
	            ),
			),
		),
	),
    'service_manager' => array(
    	'factories' => array(
    		'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
    	),
    ),
    /*'translator' => array(
    	'locale' => 'en_US',
    	'translation_file_patterns' => array(
    		array(
    			'type' => 'gettext',
    			'base_dir' => __DIR__ . '/../language',
    			'pattern'  => '%s.mo',
    		),
    	),
    ),*/
	/*'view_manager' => array(
		'template_path_stack' => array(
			'TeamWork' => __DIR__ . '/../view',
		),
	),*/
	'controllers' => array(
		'invokables' => array(
			'TeamWork\Controller\index' => 'TeamWork\Controller\IndexController',
			'TeamWork\Controller\Login' => 'TeamWork\Controller\LoginController',
			'TeamWork\Controller\Rights' => 'TeamWork\Controller\RightsController',
			'TeamWork\Controller\Center' => 'TeamWork\Controller\CenterController',
			'TeamWork\Controller\Website' => 'TeamWork\Controller\WebsiteController',
			'TeamWork\Controller\Firm' => 'TeamWork\Controller\FirmController',
			'TeamWork\Controller\Sysoption' => 'TeamWork\Controller\SysoptionController',
		),
	),
	'view_manager' => array(
		'display_not_found_reason' => true,
		'display_exceptions' => true,
		'doctype' => 'HTML5',
		'not_found_template' => 'error/404',
		'exception_template' => 'error/index',
		'template_map' => array(
			'layout/backstage' => __DIR__ . '/../view/layout/backstage_layout.phtml',
			'layout/login' => __DIR__ . '/../view/layout/login_layout.phtml',
			'error/404' => __DIR__ . '/../view/error/404.phtml',
			'error/index' => __DIR__ . '/../view/error/index.phtml',
		),
		'template_path_stack' => array(
			__DIR__ . '/../view',
		),
	),
);