<?php
namespace JBus\JSBuilder;
use JBus\JSBuilder\ComponentBuilder;
use JBus\Widget\StateWidget;

class WindowBuilder extends ComponentBuilder
{    
    public function onBuild()
    {  
        parent::onBuild();
        if($this->state->isPropSet('resizable'))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('resizable'),$this->code->valueBoolean($this->state->get('resizable')))));
        }
        if($this->state->isPropSet('dockable'))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('dockable'),$this->code->valueBoolean($this->state->get('dockable')))));
        }
        if($this->state->isPropSet(StateWidget::KEY_CREATE))
        {   
            $style="position:absolute;top:100px;left:50px;visibility:hidden !important;";
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
            $codeClientRequest=$this->code->createLocal();            
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