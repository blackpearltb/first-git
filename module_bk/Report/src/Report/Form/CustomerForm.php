<?php

namespace Report\Form;

use Zend\Form\Form;

class CustomerForm extends Form{
    public function __construct(){
		
        parent::__construct('clients');
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
            'name' => 'name',
            'type' => 'Text',
            'attributes' => array(
                'id'          => 'name',
                'placeholder' => 'Customer Name',
                'class'       => 'form-control',
            ),
        ));
                        
        
        
        $this->add(array(
            'name' => 'contact_person',
            'type' => 'Text',
            'attributes' => array(
                'id'          => 'contact_person',
                'placeholder' => 'Contact Person',
                'class'       => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'phone',
            'type' => 'Text',
            'attributes' => array(
                'id'          => 'phone',
                'placeholder' => 'Phone',
                'class'       => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'email',
            'type' => 'Text',
            'attributes' => array(
                'id'          => 'email',
                'placeholder' => 'Email',
                'class'       => 'form-control',
            ),
        ));
        
    }
} 