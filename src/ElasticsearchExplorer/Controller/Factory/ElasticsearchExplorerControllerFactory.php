<?php

namespace ElasticsearchExplorer\Controller\Factory;

use Interop\Container\ContainerInterface;
use ElasticsearchExplorer\Controller\ElasticsearchExplorerController;

class ElasticsearchExplorerControllerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $objElasticsearchManager = $container->get('ElasticsearchManager');        
        return new ElasticsearchExplorerController($objElasticsearchManager);
    }
}