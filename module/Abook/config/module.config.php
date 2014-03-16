<?php

$Module = "Abook";
$nsController = "$Module\Controller";
$folderView = "abook";
$constraints = array(
    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
);

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
            'contact-add' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/contact/add',
                    'constraints' => $constraints,
                    'defaults' => array(
                        'controller' => "$nsController\Contacts",
                        'action' => "add"
                    ),
                ),
            ),
            'contact-edit' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/contact/edit[/][:id]',
                    'constraints' => array(
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => "$nsController\Contacts",
                        'action' => "edit"
                    ),
                ),
            ),
            'contact-show' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/contact/show',
                    'constraints' => $constraints,
                    'defaults' => array(
                        'controller' => "$nsController\Contacts",
                        'action' => "show"
                    ),
                ),
            ),
            'contact-delete' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/contact/del',
                    'constraints' => $constraints,
                    'defaults' => array(
                        'controller' => "$nsController\Contacts",
                        'action' => "delete"
                    ),
                ),
            ),
            'contact-list' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/contact/list',
                    'constraints' => $constraints,
                    'defaults' => array(
                        'controller' => "$nsController\Contacts",
                        'action' => "index"
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
