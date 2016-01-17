<?php

namespace ElasticsearchExplorer\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class SearchForm extends Form 
{
    public function __construct($name = null)
    {
        parent::__construct('album');

        $this->setAttribute('method', 'GET');

        $searchIndex = new Element\Select('searchindex');
        $searchIndex->setAttribute('id', 'search-select-index');
        $searchIndex->setLabel('Select index');
        $searchIndex->setValueOptions(array(
            '-1' => '...',
        ));
        $this->add($searchIndex);
       
        $searchType = new Element\Select('searchtype');
        $searchType->setAttribute('id', 'search-select-type');
        $searchType->setLabel('Select type');
        $searchType->setValueOptions(array(
            '-1' => '...',
        ));
        $this->add($searchType); 

        $searchField = new Element\Select('searchfield');
        $searchField->setAttribute('multiple', true);
        $searchField->setAttribute('required', true);
        $searchField->setLabel('Search in field');
        $searchField->setLabelOptions(array('disable_html_escape' => true));
        $this->add($searchField);

        $searchTerm = new Element\Text('searchterm');
        $searchTerm->setAttribute('required', true);
        $searchTerm->setLabel('Search for');
        $this->add($searchTerm);

        $submit = new Element\Submit('submit');
        $submit->setAttribute('class', 'button');
        $submit->setLabel('Start search');
        $this->add($submit);
    }

    public function updateSearchIndexOptions ($indexes, $selected)
    {
        $searchIndex = $this->get('searchindex');

        $arrIndexes = $searchIndex->getValueOptions();
        foreach ($indexes AS $index) {
            $arrIndexes[$index['name']] = $index['name'];
        }

        $searchIndex->setValueOptions($arrIndexes);

        $searchIndex->setValue($selected);
    }

    public function updateSearchTypeOptions ($types, $selected)
    {
        $searchType = $this->get('searchtype');

        $arrTypes = $searchType->getValueOptions();
        foreach ($types AS $type) {
            $arrTypes[$type['name']] = $type['name'];
        }

        $searchType->setValueOptions($arrTypes);

        $searchType->setValue($selected);
    }

    public function updateSearchFieldOptions ($fields, $selected)
    {
        $searchField = $this->get('searchfield');

        $arrFields = $searchField->getValueOptions();
        foreach ($fields AS $field) {
            $arrFields[$field['name']] = $field['name'];
        }

        $searchField->setValueOptions($arrFields);

        $searchField->setValue($selected);
    }
}
