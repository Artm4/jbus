<?php
namespace JBus\Validate\JSBuilder;
use JBus\Widget\Component;
use JBus\Validate\ValidateBase;

class BuilderBase
{
    protected $widget;
    protected $code;
    protected $validator;
    function __construct(ValidateBase $validator)
    {        
        $this->validator=$validator;
    }
    
    public function build()
    {
        
    }
    
    public function setWidget(Component $widget)
    {
        $this->widget=$widget;
        $this->code=$widget->getBuilder()->getCodeBuilder();
    }
    
    protected function defineValidatorFunction($functionBody)
    {   
        $function=$this->code->valueFunction($this->code->arguments(array('value','constraints')),$functionBody);
        $this->code->_callMethod('jbus.tool', 'addValidator',
                $this->code->arguments(
                        array(
                            $this->code->valueString($this->widget->getBuilder()->getWidgetId()),
                            $function,
                            $this->code->valueString($this->validator->getMessage()),
                        )
                )
        );
    }
}