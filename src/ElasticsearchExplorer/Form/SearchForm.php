<?php

namespace ElasticsearchExplorer\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class SearchForm extends Form 
{
    public function __construct($name = null)
    {
        parent::__construct('album');

        $searchIndex = new Element\Select('searchindex');
        $searchIndex->setLabel('Select index:');
        $searchIndex->setValueOptions(array(
            '-1' => '...',
        ));
        $this->add($searchIndex);
       
        $searchType = new Element\Select('searchtype');
        $searchType->setLabel('Select type:');
        $searchType->setValueOptions(array(
            '-1' => '...',
        ));
        $this->add($searchType); 

        $searchField = new Element\Select('searchfield[]');
        $searchField->setAttribute('multiple', true);
        $searchField->setLabel('Search in field: <a href="#" data-reveal-id="modalFields"><span class="fi-info"></span></a>');
        $searchField->setValueOptions(array(
            '0' => 'Test X',
            '1' => 'Test Y',
        ));
        $searchField->setLabelOptions(array('disable_html_escape' => true));
        $this->add($searchField);

        $searchTerm = new Element\Text('searchterm');
        $searchTerm->setLabel('Search for:');
        $this->add($searchTerm);

        $submit = new Element\Submit('submit');
        $submit->setAttribute('class', 'button');
        $submit->setValue('Start search!');
        $this->add($submit);
    }

    public function updateSearchIndexOptions ($indexes)
    {
        $searchIndex = $this->get('searchindex');

        $arrIndexes = $searchIndex->getValueOptions();
        foreach ($indexes AS $index) {
            $arrIndexes[$index['name']] = $index['name'];
        }

        $searchIndex->setValueOptions($arrIndexes);
    }

    public function updateSearchTypeOptions ($types)
    {
        $searchType = $this->get('searchtype');

        $arrTypes = $searchType->getValueOptions();
        foreach ($types AS $type) {
            $arrTypes[$type['name']] = $type['name'];
        }

        $searchType->setValueOptions($arrTypes);
    }
}
