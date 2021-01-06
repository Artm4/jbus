<?php
namespace WidgetBase\Uploader;
use JBus\Widget\Component;
use JBus\JSBuilder\FunctionList;
use JBus\JSBuilder\Uploader\InputBuilder;

class Input extends Component
{
    protected $templatePath='input-template.php';
    protected $jsFunctionName=FunctionList::UPLOADER;
    
    public function getBuilder()
    {
        if(NULL==$this->builder)
        {
    
            $this->builder=new InputBuilder($this);
        }
        return $this->builder;
    }
}