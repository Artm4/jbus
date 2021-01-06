<?php
namespace WidgetBase;

use JBus\JSBuilder\DialogBuilder;
use JBus\JSBuilder\FunctionList;
use JBus\Widget\Component;
use JBus\Widget\State;

class Dialog extends DialogAbstract
{
    protected $jsFunctionName=FunctionList::DIALOG;
    protected $templatePath='dialog-template.php';
      
    public function onCreate()
    {
        parent::onCreate();        
        $this->state->set('open',false,1);
    }
    
    public function getBuilder()
    {
        if(NULL==$this->builder)
        {
            $this->builder=new DialogBuilder($this);
        }
        return $this->builder;
    }
   
    public function isOpen()
    {
        return $this->state->open==true;
    }
}