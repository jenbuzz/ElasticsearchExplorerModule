<?php

namespace ElasticsearchExplorer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ElasticsearchExplorer\Service\ElasticsearchManager;
use ElasticsearchExplorer\Form\SearchForm;

class ElasticsearchExplorerController extends AbstractActionController
{
    protected $objElasticsearchManager;
    
    public function __construct(ElasticsearchManager $objElasticsearchManager)
    {
        $this->objElasticsearchManager = $objElasticsearchManager;
    }
    
    /**
     * Home.
     */
    public function indexAction()
    {
        $arrIndexes = $this->objElasticsearchManager->getIndexStats();

        return new ViewModel(array(
            'indexes' => $arrIndexes,
        ));
    }

    /**
     * Search.
     */
    public function searchAction()
    {
        $queryParams = $this->getRequest()->getQuery();

        $objElasticsearchManager = $this->getServiceLocator()->get('ElasticsearchManager');

        $searchindex = $this->params('searchindex');
        $searchtype = $this->params('searchtype');
        $searchfield = $this->params('searchfield');
        $searchterm = $this->params('searchterm');

        // Redirect to a pretty url after search submit.
        if ($searchindex && $searchtype && !empty($queryParams['searchfield']) && !empty($queryParams['searchterm'])) {
            $strSearchfield = "";
            foreach ($queryParams['searchfield'] as $field) {
                $strSearchfield .= $field.',';
            }
            $strSearchfield = rtrim($strSearchfield, ',');

            // Generate redirect url.
            return $this->redirect()->toRoute('elasticsearchexplorer/search/searchindex', array(
                'searchindex' => $searchindex,
                'searchtype' => $searchtype,
                'searchfield' => $strSearchfield,
                'searchterm' => $queryParams['searchterm'],
            ));
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

        // Search form.
        $form = new SearchForm();

        return new ViewModel(array(
            'searchindex' => $searchindex,
            'searchtype' => $searchtype,
            'searchfield' => $searchfield,
            'searchterm' => $searchterm,
            'indexes' => $arrIndexes,
            'fields' => $arrFields,
            'types' => $arrTypes,
            'results' => $arrResults,
            'form' => $form,
        ));
    }

    /**
     * Configuration.
     */
    public function configAction()
    {
        $objElasticsearchManager = $this->getServiceLocator()->get('ElasticsearchManager');

        $arrConfiguration = $objElasticsearchManager->getConfiguration();

        return new ViewModel(array(
            'hosts' => $arrConfiguration['hosts'],
        ));
    }

    /**
     * Plugins.
     */
    public function pluginsAction()
    {
        $objElasticsearchManager = $this->getServiceLocator()->get('ElasticsearchManager');

        $arrPlugins = $objElasticsearchManager->getPlugins();

        // Get the elasticsearch host to enable plugins linking.
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
