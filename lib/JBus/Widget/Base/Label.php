<?php
namespace WidgetBase;

use JBus\JSBuilder\ButtonBuilder;
use JBus\JSBuilder\FunctionList;
use JBus\Widget\Component;
use JBus\Widget\State;
use JBus\JSBuilder\LabelBuilder;

class Label extends Component
{
    protected $jsFunctionName=FunctionList::LABEL;
    protected $templatePath='label-template.php';
   
    public function onCreate()
    {
        parent::onCreate();       
        $this->state->set('value','');
    }
    
    public function getBuilder()
    {
        if(NULL==$this->builder)
        {
            $this->builder=new LabelBuilder($this);
        }
        return $this->builder;
    }
    
    public function setValue($value)
    {
        $this->state->set('value',$value);
    }
    
    public function setTextColor($value)
    {
        return $this->state->set('textColor',$value);
    }
    
    public function getTextColor()
    {
        return $this->state->get('textColor');
    }
    
    public function setBackgroundColor($value)
    {
        $this->state->set("backgroundColor",$value);
    }
    
    public function getBackgroundColor()
    {
        return $this->state->get("backgroundColor");
    }
    
    public function getValue()
    {
        return $this->state->get('value');
    }    
}