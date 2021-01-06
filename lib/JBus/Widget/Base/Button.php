<?php
namespace WidgetBase;

use JBus\JSBuilder\ButtonBuilder;
use JBus\JSBuilder\FunctionList;
use JBus\Widget\Component;
use JBus\Widget\State;

class Button extends Component
{
    protected $jsFunctionName=FunctionList::BUTTON;
    protected $templatePath='button-template.php';
   
    public function onCreate()
    {
        parent::onCreate();       
        $this->state->set('label','');
    }
    
    public function getBuilder()
    {
        if(NULL==$this->builder)
        {
            $this->builder=new ButtonBuilder($this);
        }
        return $this->builder;
    }
    
    public function setLabel($value)
    {
        $this->state->set('label',$value);
    }
    public function setEnabled()
    {
    	$this->state->disabled='false';
    }
    public function setDisabled()
    {
    	$this->state->disabled='true';
    }
    
    public function getLabel()
    {
        return $this->state->get('label','');
    }
    
    public function setValue($value)
    {
        $this->state->set('value',$value);
    }
    
    public function getValue()
    {
        return $this->state->get('value','');
    }    
}