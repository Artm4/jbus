<?php

namespace JBus\JSBuilder;
use JBus\JSBuilder\ComponentBuilder;
use JBus\Widget\StateWidget;
use WidgetBase\FormElement;

class SpinnerBuilder extends FormElementBuilder
{
	

	public function onBuild()
	{
		parent::onBuild();
		if (isset($this->state->small))
		{
			$this->code->compSet($this->code->arguments(array($this->code->valueString('smallDelta'),$this->state->small)));

		}
		if(isset($this->state->min)&&isset($this->state->max)&&isset($this->state->place))
		{
			$store=$this->code->object(array('min'=>$this->state->min,'max'=>$this->state->max,'places'=>$this->state->place));
			$this->code->compSet($this->code->arguments(array($this->code->valueString('constraints'),$store)));
		}
		if(isset($this->state->disabled))
		{
			$this->code->compSet($this->code->arguments(array($this->code->valueString('disabled'),$this->code->valueCode($this->state->disabled))));
		}
		
		
	}
	

}

