<?php

namespace ElasticsearchExplorer;

class Module
{
    public function onBootstrap($e)
    {
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function ($e) {
            $controller = $e->getTarget();
            $controllerClass = get_class($controller);
            $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
            $config = $e->getApplication()->getServiceManager()->get('config');
            if (isset($config['module_layouts'][$moduleNamespace])) {
                $controller->layout($config['module_layouts'][$moduleNamespace]);
            }
        }, 100);

        $translator = $e->getApplication()->getServiceManager()->get('translator');
        $translator->setLocale('en_US');
    }

    public function getConfig()
    {
        $config = array();

        $configFiles = array(
            include __DIR__.'/config/module.config.php',
            include __DIR__.'/config/elasticsearch.config.php',
        );

        foreach ($configFiles as $file) {
            $config = \Zend\Stdlib\ArrayUtils::merge($config, $file);
        }

        return $config;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__.'/src/'.__NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'ElasticsearchManager' => function ($sm) {
                    // Get Elasticsearch configuration from config/elasticsearch.config.php and pass it to service
                    $config = $sm->get('config');
                    $configElasticsearch = (isset($config['elasticsearch'])) ? $config['elasticsearch'] : array();

                    $objElasticsearchManager = new \ElasticsearchExplorer\Service\ElasticsearchManager($configElasticsearch);

                    return $objElasticsearchManager;
                },
            ),
        );
    }
}
