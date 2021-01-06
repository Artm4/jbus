<?php
namespace JBus\JSBuilder;
use JBus\JSBuilder\ComponentBuilder;
use JBus\Widget\StateWidget;

class FormElementBuilder extends ComponentBuilder
{    
    const EVENT_CHANGE='change';
    
    public function onCreate()
    {
        parent::onCreate();
        $this->state->set('value','');
    }
    
    public function onBuild()
    {  
        parent::onBuild();         
        if(isset($this->state->value))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('value'),$this->code->valueString($this->state->value))));
        }
        if(isset($this->state->required))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('required'),$this->code->valueBoolean($this->state->required))));
        }
        if(isset($this->state->placeholder))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('placeholder'),$this->code->valueString($this->state->placeholder))));
        }
        if($this->state->isPropSet(StateWidget::KEY_CREATE))
        {  
            $this->defineOnClientRequest();
            $this->defineValidator();
        }
        if($this->state->isPropSet(self::EVENT_CHANGE))
        {
            $this->code->compOnEvent(self::EVENT_CHANGE);
        }     
        $this->addValidator();
    }
    
    protected function defineOnClientRequest()
    {
        $codeClientRequest=$this->code->createLocal();
        $codeClientRequest->compStateSet('value', $codeClientRequest->compGet($this->code->valueString('value')));
        $this->code->compDefineOnClientRequest($codeClientRequest->getCodeContainer()->getBody());
    }
    
    protected function defineValidator()
    {
        $code=$this->code->assign($this->code->prop($this->code->compGetObject(), 'validator'),
                'jbus.tool.validator');
        $this->code->addBlock($code);
        $this->code->compStateSet('validatorList', $this->code->arr(array()));
    }
    
    protected function addValidator()
    {
        if(!$this->state->isPropSet('validatorList'))
        {
            return false;
        }
        $validatorList=$this->state->get('validatorList');
        foreach($validatorList as $validator)
        {   
            $validator->getBuilder()->build();
        }
    }
}