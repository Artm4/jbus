<?php
namespace JBus\JSBuilder;

class CheckBoxBuilder extends ComponentBuilder
{    
    public function onBuild()
    {  
    	
    	parent::onBuild();
    	
    
    	if(isset($this->state->value))
    	{
    		$this->code->compSet($this->code->arguments(array($this->code->valueString('value'),$this->code->valueString($this->state->value))));
    	}
    	if($this->state->isPropSet('change'))
    	{
    		$this->code->compOnEvent('change');
    	}
    	
    	if(isset($this->state->checked))
    	{
    		$this->code->compSet($this->code->arguments(array($this->code->valueString('checked'),$this->code->valueCode($this->state->checked))));
    	}
    	$this->defineOnClientRequest();
    	
    	
    }
    protected function defineOnClientRequest()
    {
    	$codeClientRequest=$this->code->createLocal();
    	$codeClientRequest->compStateSet('value', $codeClientRequest->compGet($this->code->valueString('value')));    	
    	$this->code->compDefineOnClientRequest($codeClientRequest->getCodeContainer()->getBody());
    }
    
}