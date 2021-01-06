<?php

namespace JBus\JSBuilder;
use JBus\JSBuilder\ComponentBuilder;
use JBus\Widget\StateWidget;
use WidgetBase\FormElement;




class DatePickerBuilder extends FormElementBuilder
{
	

	public function onBuild()
	{
		parent::onBuild();
		
			$store=$this->code->object(array('datePattern'=>'dd/MM/yyyy'));
			$this->code->compSet($this->code->arguments(array($this->code->valueString('constraints'),$store)));
		
	
		$this->defineOnClientRequest();
	}
	
	protected function defineOnClientRequest()
	{
		$codeClientRequest=$this->code->createLocal();
		$codeClientRequest->compStateSet('value', $codeClientRequest->compGet($this->code->valueString('value')));
		$codeClientRequest->compStateSet('displayedValue', $codeClientRequest->compGet($this->code->valueString('displayedValue')));
		$this->code->compDefineOnClientRequest($codeClientRequest->getCodeContainer()->getBody());
	}

	
}
	