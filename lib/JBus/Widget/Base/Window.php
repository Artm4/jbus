<?php
namespace WidgetBase;

use JBus\JSBuilder\WindowBuilder;
use JBus\JSBuilder\FunctionList;
use JBus\Widget\Component;
use JBus\Widget\State;

class Window extends DialogAbstract
{
    protected $jsFunctionName=FunctionList::WINDOW;
    protected $templatePath='window-template.php';
   
    public function onCreate()
    {
        parent::onCreate();
        $this->setResizable(true);
        $this->setDockable(false);
    }
    
    public function getBuilder()
    {
        if(NULL==$this->builder)
        {
            $this->builder=new WindowBuilder($this);
        }
        return $this->builder;
    }    
    
    public function setResizable($flag=true)
    {
        $this->state->set('resizable',$flag,1);
    }
    
    public function setDockable($flag=false)
    {   
        $this->state->set('dockable',$flag,1);
    }    
}