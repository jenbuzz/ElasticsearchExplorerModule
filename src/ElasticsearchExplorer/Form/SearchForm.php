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
        $searchIndex->setLabel('Select index');
        $searchIndex->setValueOptions(array(
            '0' => 'Test X',
            '1' => 'Test Y',
        ));
        $this->add($searchIndex);
       
        $searchType = new Element\Select('searchtype');
        $searchType->setLabel('Select type');
        $searchType->setValueOptions(array(
            '0' => 'Test X',
            '1' => 'Test Y',
        ));
        $this->add($searchType); 

        $searchField = new Element\Select('searchfield[]');
        $searchField->setAttribute('multiple', true);
        $searchField->setLabel('Select field');
        $searchField->setValueOptions(array(
            '0' => 'Test X',
            '1' => 'Test Y',
        ));
        $this->add($searchField);

        $searchTerm = new Element\Text('searchterm');
        $searchTerm->setLabel('Search for');
        $this->add($searchTerm);

        $submit = new Element\Submit('submit');
        $submit->setValue('Start search!');
        $this->add($submit);
    }
}
