<?php

namespace Report\Form;

use Zend\Form\Form;

class UsersForm extends Form{
    public function __construct(){

        parent::__construct('users');
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
            'name' => 'position',
            'type' => 'Select',
            'attributes' => array(
                'id'          => 'position',
                'class'       => 'form-control position',
            ),
            'options' => array(
                'value_options' => array(
                    '0' => 'Select',
                    'normal' => 'Normal',
                    'senior' => 'Senior',
                ),
            ),
        ));
        
        
        $this->add(array(
            'name' => 'senior',
            'type' => 'Select',
            'attributes' => array(
                'id'          => 'senior',
                'class'       => 'form-control senior',
            ),
            'options' => array(
                'value_options' => array(
                    '' => 'Select',                  
                ),
            ),
        ));
        
        
        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'attributes' => array(
                'id'          => 'password',
                'placeholder' => 'Password',
                'class'       => 'form-control',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'confirm-password',
            'type' => 'Password',
            'attributes' => array(
                'id'          => 'confirm-password',
                'placeholder' => 'Confirm Password',
                'class'       => 'form-control',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'fullname',
            'type' => 'Text',
            'attributes' => array(
                'id'          => 'fullname',
                'placeholder' => 'Fullname',
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