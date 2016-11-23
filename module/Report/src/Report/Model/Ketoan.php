<?php
namespace Report\Model;

 class Ketoan
 {
     public $id;
     public $streakID;
     public $name;
     public $sale;
     public $stage;
     public $days_in_stage;
     public $date_created;
     public $note;
     public $dealsize;
     public $cost_plan;
     public $gp_plan;
     public $model;
	 public $assigned_to;
	 public $channel;
	 public $insource_or_outsource;
	 public $start_date;
	 public $end_date;
	 public $total_days;
	 public $remain_days;
	 public $total_kpi;
	 public $sell_price_cpc_cpm_cpa_usd;
	 public $max_unit_cost_cpc_cpm_cpa_usd;
	 public $actual_unit_cost_cpc_cpm_cpa_usd;
	 public $actual_sold_value_vnd;
	 public $lobby;
	 public $entertainment;
	 public $actual_cost_usd;
	 public $actual_cost_vnd;
	 public $actual_profit;
	 public $actual_gp;
	 public $kpi_per_day;
	 public $date;
	 public $id_join;
	 

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->streakID = (!empty($data['streakID'])) ? $data['streakID'] : null;
         $this->name  = (!empty($data['name'])) ? $data['name'] : null;
         $this->sale  = (!empty($data['sale'])) ? $data['sale'] : null;
         $this->stage  = (!empty($data['stage'])) ? $data['stage'] : null;
         $this->days_in_stage  = (!empty($data['days_in_stage'])) ? $data['days_in_stage'] : null;
         $this->date_created  = (!empty($data['date_created'])) ? $data['date_created'] : null;
         $this->note  = (!empty($data['note'])) ? $data['note'] : null;
         $this->dealsize  = (!empty($data['dealsize'])) ? $data['dealsize'] : null;
         $this->cost_plan  = (!empty($data['cost_plan'])) ? $data['cost_plan'] : null;
         $this->gp_plan  = (!empty($data['gp_plan'])) ? $data['gp_plan'] : null;
         $this->model  = (!empty($data['model'])) ? $data['model'] : null;
         $this->assigned_to  = (!empty($data['assigned_to'])) ? $data['assigned_to'] : null;
         $this->channel  = (!empty($data['channel'])) ? $data['channel'] : null;
         $this->insource_or_outsource  = (!empty($data['insource_or_outsource'])) ? $data['insource_or_outsource'] : null;
         $this->start_date  = (!empty($data['start_date'])) ? $data['start_date'] : null;
         $this->end_date  = (!empty($data['end_date'])) ? $data['end_date'] : null;
         $this->total_days  = (!empty($data['total_days'])) ? $data['total_days'] : null;
         $this->remain_days  = (!empty($data['remain_days'])) ? $data['remain_days'] : null;
         $this->total_kpi  = (!empty($data['total_kpi'])) ? $data['total_kpi'] : null;
         $this->sell_price_cpc_cpm_cpa_usd  = (!empty($data['sell_price_cpc_cpm_cpa_usd'])) ? $data['sell_price_cpc_cpm_cpa_usd'] : null;
         $this->max_unit_cost_cpc_cpm_cpa_usd  = (!empty($data['max_unit_cost_cpc_cpm_cpa_usd'])) ? $data['max_unit_cost_cpc_cpm_cpa_usd'] : null;
         $this->actual_unit_cost_cpc_cpm_cpa_usd  = (!empty($data['actual_unit_cost_cpc_cpm_cpa_usd'])) ? $data['actual_unit_cost_cpc_cpm_cpa_usd'] : null;
         $this->actual_sold_value_vnd  = (!empty($data['actual_sold_value_vnd'])) ? $data['actual_sold_value_vnd'] : null;
         $this->lobby  = (!empty($data['lobby'])) ? $data['lobby'] : null;
         $this->entertainment  = (!empty($data['entertainment'])) ? $data['entertainment'] : null;
         $this->actual_cost_usd  = (!empty($data['actual_cost_usd'])) ? $data['actual_cost_usd'] : null;
         $this->actual_cost_vnd  = (!empty($data['actual_cost_vnd'])) ? $data['actual_cost_vnd'] : null;
         $this->actual_profit  = (!empty($data['actual_profit'])) ? $data['actual_profit'] : null;
         $this->actual_gp  = (!empty($data['actual_gp'])) ? $data['actual_gp'] : null;
         $this->kpi_per_day  = (!empty($data['kpi_per_day'])) ? $data['kpi_per_day'] : null;
         $this->date  = (!empty($data['date'])) ? $data['date'] : null;
         $this->id_join  = (!empty($data['id_join'])) ? $data['id_join'] : null;
     }
 }