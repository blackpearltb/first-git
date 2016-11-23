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

class Users implements InputFilterAwareInterface{
    public $id;
    public $parent_id;
    public $password;
    public $fullname;
    public $email;
    public $type;
    public $position;       
    protected $inputFilter;

    public function exchangeArray($data){
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->parent_id = (!empty($data['parent_id'])) ? $data['parent_id'] : '';
        $this->password = (!empty($data['password'])) ? $data['password'] : '';
        $this->fullname = (!empty($data['fullname'])) ? $data['fullname'] : '';
        $this->email = (!empty($data['email'])) ? $data['email'] : '';
        $this->type = (!empty($data['type'])) ? $data['type'] : '';
        $this->position = (!empty($data['position'])) ? $data['position'] : '';                       
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
                'name' => 'parent_id',
                'required' => TRUE,
            ));
            $inputFilter->add(array(
                'name' => 'password',
                'required' => TRUE,
            ));
            $inputFilter->add(array(
                'name' => 'fullname',
                'required' => TRUE,
            ));
            $inputFilter->add(array(
                'name' => 'email',
                'required' => TRUE,
            ));
            $inputFilter->add(array(
                'name' => 'type',
                'required' => TRUE,
            ));
            $inputFilter->add(array(
                'name' => 'position',
                'required' => TRUE,
            ));
                                  
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
} 