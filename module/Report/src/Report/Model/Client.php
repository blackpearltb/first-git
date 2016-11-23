<?php
/**
 * Created by PhpStorm.
 * User: viet
 * Date: 6/6/14
 * Time: 9:22 AM
 */

namespace Report\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Client implements InputFilterAwareInterface{
    public $id;
    public $name;
    public $address;
    public $phone;
    public $mst;     
    protected $inputFilter;
	
    public function exchangeArray($data){
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : '';
        $this->address = (!empty($data['address'])) ? $data['address'] : '';
        $this->phone = (!empty($data['phone'])) ? $data['phone'] : '';
        $this->mst = (!empty($data['mst'])) ? $data['mst'] : '';                      
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
                       
            $inputFilter->add(array(
                'name' => 'name',
                'required' => TRUE,
            ));
            $inputFilter->add(array(
                'name' => 'address',
                'required' => TRUE,
            ));
            $inputFilter->add(array(
                'name' => 'phone',
                'required' => TRUE,
            ));
            
            $inputFilter->add(array(
                'name' => 'mst',
                'required' => FALSE,
            ));        
            
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
} 