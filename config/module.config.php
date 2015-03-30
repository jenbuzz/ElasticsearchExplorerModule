<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'ElasticsearchExplorer\Controller\ElasticsearchExplorer' => 'ElasticsearchExplorer\Controller\ElasticsearchExplorerController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'elasticsearchexplorer' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/elasticsearchexplorer',
                    'defaults' => array(
                        'controller' => 'ElasticsearchExplorer\Controller\ElasticsearchExplorer',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'search' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/search',
                            'defaults' => array(
                                'action' => 'search',
                            ),
                        ),
                    ),
                    'config' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/config',
                            'defaults' => array(
                                'action' => 'config',
                            ),
                        ),
                    ),
                    'plugins' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/plugins',
                            'defaults' => array(
                                'action' => 'plugins',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            'elasticsearchexplorer' => __DIR__ . '/../view',
        ),
    ),
);
