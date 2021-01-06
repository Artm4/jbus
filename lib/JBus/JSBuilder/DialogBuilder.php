<?php
namespace JBus\JSBuilder;
use JBus\JSBuilder\ComponentBuilder;
use JBus\Widget\StateWidget;

class DialogBuilder extends ComponentBuilder
{    
    public function onBuild()
    {  
        parent::onBuild();        
        if($this->state->isPropSet(StateWidget::KEY_CREATE))
        {            
            $codeClientRequest=$this->code->createLocal();            
            $codeClientRequest->compStateSet('open', $codeClientRequest->compGet($this->code->valueString('open')));
            $style="";
            $width='';
            $height='';
            if($this->state->isPropSet('width'))
            {
                $width=$this->state->get('width','');
                $style.='width:'.$width.';';
            }
            if($this->state->isPropSet('height'))
            {
                $height=$this->state->get('height','');
                $style.='height:'.$height.';';
            }
            $this->code->compSet($this->code->arguments(array($this->code->valueString('style'),$this->code->valueString($style))));
            $this->code->compDefineOnClientRequest($codeClientRequest->getCodeContainer()->getBody());            
            $this->code->compCallMethod('startup');            
        }
        if($this->state->isPropSet('show'))
        {
            $this->code->compCallMethod('show');
        }
        if($this->state->isPropSet('hide'))
        {
            $this->code->compCallMethod('hide');
        }
               
    }
}