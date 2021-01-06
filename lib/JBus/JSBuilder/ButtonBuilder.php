<?php
namespace JBus\JSBuilder;
use JBus\JSBuilder\ComponentBuilder;
use JBus\Widget\StateWidget;

class ButtonBuilder extends ComponentBuilder
{    
    public function onBuild()
    {  
        parent::onBuild();
        if(isset($this->state->label))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('label'),$this->code->valueString($this->state->label))));
        }
        if($this->state->isPropSet(StateWidget::KEY_CREATE))
        {
            $codeClientRequest=$this->code->createLocal();        
            $codeClientRequest->compStateSet('label', $codeClientRequest->compGet($this->code->valueString('label')));
            $this->code->compDefineOnClientRequest($codeClientRequest->getCodeContainer()->getBody());
        }
        if(isset($this->state->disabled))
        {
        $this->code->compSet($this->code->arguments(array($this->code->valueString('disabled'),$this->code->valueCode($this->state->disabled))));
        }
    }
}