<?php
namespace Report\Form;


use Zend\Form\Form;

class KetoanForm extends Form
{
	public function __construct($name = null)
    {

        parent::__construct('ketoan');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');
         
        $this->add(array(
            'name' => 'profilename',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Profile Name',
            ),
        ));
 
         
        $this->add(array(
            'name' => 'fileupload',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => 'File Upload',
            ),
        )); 
         
         
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Upload Now'
            ),
        )); 
    }

	 
	
}



