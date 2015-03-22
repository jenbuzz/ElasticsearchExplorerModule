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
}
