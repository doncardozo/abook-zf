<?php

$Module = "Book";
$nsController = "$Module\Controller";

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/]',
                    'defaults' => array(
                        'controller' => "$nsController\Index",
                        'action' => 'index',
                    ),
                ),
            ),
            'contact-add' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/contact/add',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => "$nsController\Contact",
                        'action' => 'add'
                    )
                )
            ),
            'contact-list' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/contact/list',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => "$nsController\Contact",
                        'action' => 'list'
                    )
                )
            ),
            'contact-edit' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/contact/edit[/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => "$nsController\Contact",
                        'action' => 'edit'
                    )
                )
            ),            
            'contact-delete' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/contact/delete[/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => "$nsController\Contact",
                        'action' => 'delete'
                    )
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            "$nsController\Index" => "$nsController\IndexController",
            "$nsController\Contact" => "$nsController\ContactController",
            "$nsController\Group" => "$nsController\GroupController"
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'book/index/index' => __DIR__ . '/../view/book/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
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
);
