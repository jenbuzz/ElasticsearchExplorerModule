<?php

namespace ElasticsearchExplorer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ElasticsearchExplorerController extends AbstractActionController
{
    public function indexAction()
    {
        $objElasticsearchManager = $this->getServiceLocator()->get('ElasticsearchManager');

        $arrIndexes = $objElasticsearchManager->getIndexStats();

        return new ViewModel(array(
            'indexes' => $arrIndexes,
        ));
    }

    public function searchAction($searchindex = false, $searchtype = false, $searchfield = false, $searchterm = false)
    {
        $objElasticsearchManager = $this->getServiceLocator()->get('ElasticsearchManager');

        $searchindex = $this->params('searchindex');
        $searchtype = $this->params('searchtype');
        $searchfield = $this->params('searchfield');
        $searchterm = $this->params('searchterm');
        
        // TODO: setup routing and change to zf request format
        // Redirect to a pretty url after search submit.
        if ($this->params('searchindex') && $this->params('searchtype') && !empty($this->params('searchfield'))  && !empty($this->params('searchterm'))) {
            $strSearchfield = "";
            foreach ($this->params('searchfield') as $field) {
                $strSearchfield .= $field.',';
            }
            $strSearchfield = rtrim($strSearchfield, ',');

            // Generate redirect url.
            $url = $this->generateUrl('dan_lyn_elasticsearch_explorer_search_term', array(
                'searchindex' => $this->params('searchindex'),
                'searchtype' => $this->params('searchtype'),
                'searchfield' => $strSearchfield,
                'searchterm' => $this->params('searchterm'),
            ));

            return $this->redirect($url);
        }

        // Get indexes.
        $arrIndexes = $objElasticsearchManager->getIndexStats();

        // Get types.
        $arrTypes = array();
        if ($searchindex) {
            $arrTypes = $objElasticsearchManager->getIndexMappingTypes($searchindex);
        }

        // Get fields.
        $arrFields = array();
        if ($searchindex && $searchtype) {
            $arrFields = $objElasticsearchManager->getFieldsInIndexType($searchindex, $searchtype);
        }

        // Get results.
        $arrResults = array();
        if ($searchindex && $searchtype && $searchfield && $searchterm) {
            $arrResults = $objElasticsearchManager->search($searchindex, $searchtype, $searchfield, $searchterm);

            // Create array of searchfields.
            $searchfield = $objElasticsearchManager->convertSearchfieldsToArray($searchfield);
        }

        return new ViewModel(array(
            'searchindex' => $searchindex,
            'searchtype' => $searchtype,
            'searchfield' => $searchfield,
            'searchterm' => $searchterm,
            'indexes' => $arrIndexes,
            'types' => $arrTypes,
        ));
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
