<?php
namespace JBus\JSBuilder;
use JBus\Widget\Component;
use JBus\WidgetLoader;
use JBus\Template\TemplateParser;
use JBus\Widget\StateWidget;
use JBus\Widget\StateCache;

class UploaderBuilder extends ComponentBuilder
{
    const EVENT_UPLOAD='upload';
    
    public function onBuild()
    {
        parent::onBuild();        
        $localCode=$this->code->createLocal();        
        $localCode->set($this->getWidget()->fileList->getBuilder()->getWidgetId(),
                $this->code->arguments(array($this->code->valueString('uploader'),
                $this->getWidget()->input->getBuilder()->getCodeBuilder()->compGetObject()))
        );
        
        $localCode->callMethod($this->getWidget()->input->getBuilder()->getWidgetId(),
                'addDropTarget',
                $localCode->arguments(
                        $localCode->prop($this->getWidget()->dropTarget->getBuilder()->getCodeBuilder()->compGetObject(),
                        'domNode'
                ))
        );
        $this->code->addBlock($localCode->getCodeContainer()->getBody());
    }
}