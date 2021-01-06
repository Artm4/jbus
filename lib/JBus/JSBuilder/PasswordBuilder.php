<?php


namespace JBus\JSBuilder;
use JBus\JSBuilder\ComponentBuilder;
use JBus\Widget\StateWidget;


class PasswordBuilder extends ComponentBuilder
{
	

	public function onBuild()
	{
		parent::onBuild();
		if(isset($this->state->placeHolder))
		{
			$this->code->compSet($this->code->arguments(array($this->code->valueString('placeholder'),$this->code->valueString($this->state->placeHolder))));
		}
		
		
		if(isset($this->state->value))
		{
			$this->code->compSet($this->code->arguments(array($this->code->valueString('value'),$this->code->valueString($this->state->value))));
		}
		
		
	
			$codeClientRequest=$this->code->createLocal();
			$codeClientRequest->compStateSet('value', $codeClientRequest->compGet($this->code->valueString('value')));
			
			$this->code->compDefineOnClientRequest($codeClientRequest->getCodeContainer()->getBody());
	}

}

