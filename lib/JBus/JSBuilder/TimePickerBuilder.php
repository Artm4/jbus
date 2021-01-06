<?php
namespace JBus\JSBuilder;

class TimePickerBuilder extends FormElementBuilder
{
	
	public function onBuild()
	{
		if (isset($this->state->timePattern))
		{
			$timePattern=$this->state->timePattern;
		}
		else 
		{
			$timePattern='HH:mm';
		}
		
		if (isset($this->state->dropDownIncrement))
		{
			$dropDownIncrement=$this->state->dropDownIncrement;
		}
		else
		{
			$dropDownIncrement='T00:15:00';
		}
		
		if (isset($this->state->dropDownBaseIncrement))
		{
			$dropDownBaseIncrement=$this->state->dropDownBaseIncrement;
		}
		else
		{
			$dropDownBaseIncrement='T00:15:00';
		}
		$store=$this->code->object(array(				
				'timePattern'=>$timePattern,
				'clickableIncrement'=>$dropDownIncrement,
				'visibleIncrement'=>$dropDownBaseIncrement,			
		));
		$this->code->compSet($this->code->arguments(array($this->code->valueString('constraints'),$store)));
	
		$this->defineOnClientRequest();
		parent::onBuild();
	}
	
	protected function defineOnClientRequest()
	{
		$codeClientRequest=$this->code->createLocal();
		$codeClientRequest->compStateSet('value', $codeClientRequest->compGet($this->code->valueString('value')));
		$codeClientRequest->compStateSet('displayedValue', $codeClientRequest->compGet($this->code->valueString('displayedValue')));
		$this->code->compDefineOnClientRequest($codeClientRequest->getCodeContainer()->getBody());
	}
}