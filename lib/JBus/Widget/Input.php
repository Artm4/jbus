<?php
namespace JBus\Widget;

use JBus\JSBuilder\InputBuilder;

class Input extends Component
{
    public $template='';
    private $builder=NULL;
   
    public function getBuilder()
    {
        if(NULL==$this->builder)
        {
            $this->builder=new InputBuilder($this);
        }
        return $this->builder;
    }
}