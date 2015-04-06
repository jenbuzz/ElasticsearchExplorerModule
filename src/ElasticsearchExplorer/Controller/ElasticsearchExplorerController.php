<?php

namespace ElasticsearchExplorer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ElasticsearchExplorerController extends AbstractActionController
{
    public function indexAction()
    {
        $objElasticsearchManager = $this->getServiceLocator()->get('ElasticsearchManager');

        return new ViewModel();
    }

    public function searchAction()
    {
        $objElasticsearchManager = $this->getServiceLocator()->get('ElasticsearchManager');

        return new ViewModel();
    }

    public function configAction()
    {
        $objElasticsearchManager = $this->getServiceLocator()->get('ElasticsearchManager');

        $arrConfiguration = $objElasticsearchManager->getConfiguration();

        return new ViewModel(array(
            'hosts' => $arrConfiguration['hosts'],
        ));
    }

    public function pluginsAction()
    {
        $objElasticsearchManager = $this->getServiceLocator()->get('ElasticsearchManager');

        $arrPlugins = $objElasticsearchManager->getPlugins();

        // Get the host of elasticsearch.
        $host = '';
        $arrConfiguration = $objElasticsearchManager->getConfiguration();
        if (is_array($arrConfiguration) && isset($arrConfiguration['hosts']) && !empty($arrConfiguration['hosts'])) {
            $host = $arrConfiguration['hosts'][0];
        }

        return new ViewModel(array(
            'plugins' => $arrPlugins,
            'hosts' => $host,
        ));
    }
}
