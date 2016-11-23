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

class Collected implements InputFilterAwareInterface{
    public $id;
    public $client;
    public $streakID;
    public $campaign_name;
    public $contract;
    public $contract_value;
    public $collected_1;
    public $collected_date1;
    public $collected_2;
    public $collected_date2;
    public $collected_3;
    public $collected_date3;
    public $collected_4;
    public $collected_date4;
    public $vat;
    public $remain;   
    protected $inputFilter;

    public function exchangeArray($data){
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->client = (!empty($data['client'])) ? $data['client'] : '';
        $this->streakID = (!empty($data['streakID'])) ? $data['streakID'] : '';
        $this->campaign_name = (!empty($data['campaign_name'])) ? $data['campaign_name'] : '';
        $this->contract = (!empty($data['contract'])) ? $data['contract'] : '';
        $this->contract_value = (!empty($data['contract_value'])) ? $data['contract_value'] : '';
        $this->collected_1 = (!empty($data['collected_1'])) ? $data['collected_1'] : '';
        $this->collected_date1 = (!empty($data['collected_date1'])) ? $data['collected_date1'] : '';
        $this->collected_2 = (!empty($data['collected_2'])) ? $data['collected_2'] : '';
        $this->collected_date2 = (!empty($data['collected_date2'])) ? $data['collected_date2'] : '';
        $this->collected_3 = (!empty($data['collected_3'])) ? $data['collected_3'] : '';
        $this->collected_date3 = (!empty($data['collected_date3'])) ? $data['collected_date3'] : '';
        $this->collected_4 = (!empty($data['collected_4'])) ? $data['collected_4'] : '';
        $this->collected_date4 = (!empty($data['collected_date4'])) ? $data['collected_date4'] : '';
        $this->vat = (!empty($data['vat'])) ? $data['vat'] : '';
        $this->remain = (!empty($data['remain'])) ? $data['remain'] : '';               
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
                'name' => 'client',
                'required' => TRUE,
            ));
            $inputFilter->add(array(
                'name' => 'streakID',
                'required' => TRUE,
            ));
            $inputFilter->add(array(
                'name' => 'campaign_name',
                'required' => TRUE,
            ));
            $inputFilter->add(array(
                'name' => 'contract',
                'required' => TRUE,
            ));
            $inputFilter->add(array(
                'name' => 'contract_value',
                'required' => TRUE,
            ));
            $inputFilter->add(array(
                'name' => 'collected_1',
                'required' => FALSE,
            ));
            $inputFilter->add(array(
                'name' => 'collected_date1',
                'required' => FALSE,
            ));
             $inputFilter->add(array(
                'name' => 'collected_2',
                'required' => FALSE,
            ));
            $inputFilter->add(array(
                'name' => 'collected_date2',
                'required' => FALSE,
            ));
             $inputFilter->add(array(
                'name' => 'collected_3',
                'required' => FALSE,
            ));
            $inputFilter->add(array(
                'name' => 'collected_date3',
                'required' => FALSE,
            ));  
             $inputFilter->add(array(
                'name' => 'collected_4',
                'required' => FALSE,
            ));
            $inputFilter->add(array(
                'name' => 'collected_date4',
                'required' => FALSE,
            ));          
            
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
} 