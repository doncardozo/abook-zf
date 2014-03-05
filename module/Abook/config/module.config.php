<?php

$Module = "Abook";
$nsController = "$Module\Controller";
$folderView = "abook";

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => "$nsController\Contacts",
                        'action' => 'index',
                    ),
                ),
            ),
            'abook' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/Contacts',
                    'defaults' => array(
                        '__NAMESPACE__' => "$nsController\Contacts",
                        'controller' => 'Contacts',
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
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            "$nsController\Contacts" => "$nsController\ContactsController",
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            "$folderView/layout/layout" => __DIR__ . "/../view/$folderView/layout/layout.phtml",
            "$folderView/index/index" => __DIR__ . "/../view/$folderView/index/index.phtml",
            "$folderView/error/404" => __DIR__ . "/../view/$folderView/error/404.phtml",
            "$folderView/error/index" => __DIR__ . "/../view/$folderView/error/index.phtml",
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    'di' => array(
        'Abook\Model\ManagerAbstract' => array(
            'parameters' => array(
                'adapter' => 'Zend\Db\Adapter\Adapter',
            ),
        ),
    )
);
