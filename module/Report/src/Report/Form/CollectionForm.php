<?php

namespace Report\Form;

use Zend\Form\Form;

class CollectionForm extends Form{
    public function __construct(){

        parent::__construct('collection');
        $this->attributes = array(
            'role'   => 'form',
            'method' => 'post'
        );
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
            'attributes' => array(
                'id'          => 'id',
                'placeholder' => 'ID',
                'class'       => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'client',
            'type' => 'Text',
            'attributes' => array(
                'id'          => 'client',
                'placeholder' => 'Client',
                'class'       => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'streakID',
            'type' => 'Text',
            'attributes' => array(
                'id'          => 'streakID',
                'placeholder' => 'Streak ID',
                'class'       => 'form-control',
            ),
        ));
         $this->add(array(
            'name' => 'campaign_name',
            'type' => 'Text',
            'attributes' => array(
                'id'          => 'campaign_name',
                'placeholder' => 'Campaign Name',
                'class'       => 'form-control',
            ),
        ));
         $this->add(array(
            'name' => 'contract',
            'type' => 'Text',
            'attributes' => array(
                'id'          => 'contract',
                'placeholder' => 'Contract',
                'class'       => 'form-control',
            ),
        ));
         $this->add(array(
            'name' => 'contract_value',
            'type' => 'Text',
            'attributes' => array(
                'id'          => 'contract_value',
                'placeholder' => 'Contract Value',
                'class'       => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'collected_1',
            'type' => 'Text',
            'attributes' => array(
                'id'          => 'collected_1',
                'placeholder' => 'Collected 1',
                'class'       => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'collected_date1',
            'type' => 'Text',
            'attributes' => array(
                'id'          => 'collected_date1',
                'placeholder' => 'Collected Date 1',
                'class'       => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'collected_2',
            'type' => 'Text',
            'attributes' => array(
                'id'          => 'collected_2',
                'placeholder' => 'Collected 2',
                'class'       => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'collected_date2',
            'type' => 'Text',
            'attributes' => array(
                'id'          => 'collected_date2',
                'placeholder' => 'Collected Date 2',
                'class'       => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'collected_3',
            'type' => 'Text',
            'attributes' => array(
                'id'          => 'collected_3',
                'placeholder' => 'Collected 3',
                'class'       => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'collected_date3',
            'type' => 'Text',
            'attributes' => array(
                'id'          => 'collected_date3',
                'placeholder' => 'Collected Date 3',
                'class'       => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'collected_4',
            'type' => 'Text',
            'attributes' => array(
                'id'          => 'collected_4',
                'placeholder' => 'Collected 4',
                'class'       => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'collected_date4',
            'type' => 'Text',
            'attributes' => array(
                'id'          => 'collected_date4',
                'placeholder' => 'Collected Date 4',
                'class'       => 'form-control',
            ),
        ));
        
    }
} 