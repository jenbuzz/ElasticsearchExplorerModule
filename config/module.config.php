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
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'album' => __DIR__ . '/../view',
        ),
    ),
);
