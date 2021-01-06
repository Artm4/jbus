<?php
namespace WidgetBase;

use JBus\JSBuilder\FunctionList;
use JBus\JSBuilder\PasswordBuilder;
use JBus\Widget\Component;

class Password extends FormElement
{   
    protected $jsFunctionName=FunctionList::PASSWORD;
    protected $templatePath='password-template.php';    
    
    public function onCreate()
    {
    	parent::onCreate();
    	
    }
    
    public function getBuilder()
    {
    	if(NULL==$this->builder)
    	{
    
    		$this->builder=new PasswordBuilder($this);
    	}
    	return $this->builder;
    }
    
    public function getValue()
    {
    	return $this->state->get('value');
    }
    public function setValue($value)
    {
    	$this->state->set('value',$value);
    }
    
    public function setPlaceHolder($placeHolder)
    {
    	$this->state->set('placeHolder',$placeHolder);
    }
   
}