<?php
namespace JBus\Validate\JSBuilder;
use JBus\Widget\Component;
use JBus\Validate\ValidateRange;

class BuilderRange extends BuilderBase
{
    protected $widget;
    protected $code;
    function __construct(ValidateRange $validator)
    {
        parent::__construct($validator);
    }
    
    public function build()
    {
        $functionBody='';
        $functionBody=sprintf("return %s>=%s&&%s<=%s",'value',$this->validator->getMin(),'value',$this->validator->getMax());        
        $this->defineValidatorFunction($functionBody);
    }
}