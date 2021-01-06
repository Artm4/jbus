<?php
namespace JBus\JSBuilder;
use JBus\JSBuilder\ComponentBuilder;
use JBus\Widget\StateWidget;

class LabelBuilder extends ComponentBuilder
{    
    public function onBuild()
    {  
        parent::onBuild();
        if(isset($this->state->value))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('value'),$this->code->valueString($this->state->value))));
        }
        if(isset($this->state->backgroundColor))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('backgroundColor'),$this->code->valueString($this->state->backgroundColor))));
        }
        if(isset($this->state->textColor))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('textColor'),$this->code->valueString($this->state->textColor))));
        }
        if($this->state->isPropSet(StateWidget::KEY_CREATE))
        {
            $codeClientRequest=$this->code->createLocal();        
            $codeClientRequest->compStateSet('value', $codeClientRequest->compGet($this->code->valueString('value')));
            $codeClientRequest->compStateSet('backgroundColor', $codeClientRequest->compGet($this->code->valueString('backgroundColor')));
            $codeClientRequest->compStateSet('textColor', $codeClientRequest->compGet($this->code->valueString('textColor')));
            $this->code->compDefineOnClientRequest($codeClientRequest->getCodeContainer()->getBody());
        }        
    }
}