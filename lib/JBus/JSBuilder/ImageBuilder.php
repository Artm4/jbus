<?php
namespace JBus\JSBuilder;
use JBus\JSBuilder\ComponentBuilder;
use JBus\Widget\StateWidget;

class ImageBuilder extends ComponentBuilder
{    
    public function onBuild()
    {  
        parent::onBuild();
        if(isset($this->state->value))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('value'),$this->code->valueString($this->state->value))));
        }
        if(isset($this->state->width))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('width'),$this->code->valueString($this->state->width))));
        }
        if(isset($this->state->height))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('height'),$this->code->valueString($this->state->height))));
        }
        if($this->state->isPropSet(StateWidget::KEY_CREATE))
        {
            $codeClientRequest=$this->code->createLocal();        
            $codeClientRequest->compStateSet('value', $codeClientRequest->compGet($this->code->valueString('value')));
            $codeClientRequest->compStateSet('width', $codeClientRequest->compGet($this->code->valueString('width')));
            $codeClientRequest->compStateSet('height', $codeClientRequest->compGet($this->code->valueString('height')));
            $this->code->compDefineOnClientRequest($codeClientRequest->getCodeContainer()->getBody());
        }        
    }
}