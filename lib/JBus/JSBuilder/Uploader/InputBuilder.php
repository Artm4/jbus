<?php
namespace JBus\JSBuilder\Uploader;
use JBus\Widget\Component;
use JBus\JSBuilder\ComponentBuilder;
use JBus\WidgetLoader;
use JBus\Template\TemplateParser;
use JBus\Widget\StateWidget;
use JBus\Widget\StateCache;

class InputBuilder extends ComponentBuilder
{
    public function onBuild()
    {
        parent::onBuild();        
        if($this->state->isPropSet('multiple'))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('multiple'),$this->code->valueBoolean(true))));
        }
        if($this->state->isPropSet('url'))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('url'),$this->code->valueString($this->state->get('url')))));
        }
        $this->code->compCallMethod('startup');        
    }
}