<?php

namespace WidgetBase;

use JBus\JSBuilder\FunctionList;
use JBus\JSBuilder\SpinnerBuilder;
use JBus\Widget\State;

class Spinner extends FormElement {

    protected $jsFunctionName = FunctionList::SPINNER;
    protected $templatePath = 'spinner-template.php';

    	public function onCreate()
    	{
    		parent::onCreate();
       	}    
    
    	public function getBuilder()
    	{
    		if(NULL==$this->builder)
    		{
    
    		$this->builder=new SpinnerBuilder($this);
    		}
    		return $this->builder;
    	}
    
   
    	public function setConstraints($min,$max,$off)
    	{
    		$this->state->place=$off;
    		$this->state->min=$min;
    		$this->state->max=$max;
    	}
    	public function setSmallDelta($small)
    	{
    		$this->state->small=$small;
    
    	}
    	public function setDisabled()
    	{
    		$this->state->disabled='true';
    	}
    	public function setEnabled()
    	{
    		$this->state->disabled='false';
    	}
    }
    


