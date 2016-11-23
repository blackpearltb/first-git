<?php

namespace Report\Form;

use Zend\Form\Form;

class LoginForm extends Form{
    public function __construct(){

        parent::__construct('login');
        $this->attributes = array(
            'role'   => 'form',
            'method' => 'post'
        );
        $this->add(array(
            'name' => 'email',
            'type' => 'Text',
            'attributes' => array(
                'id'          => 'email',
                'placeholder' => 'Email',
                'class'       => 'form-control',
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
            'name' => 'rememberme',
            'type' => 'Checkbox',
            'attributes' => array(
                'id'          => 'rememberme',
                'placeholder' => 'Remember me',
                'class'       => '',
            ),
        ));
    }
} 