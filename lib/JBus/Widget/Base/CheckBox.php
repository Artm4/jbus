<?php
namespace WidgetBase;

use JBus\JSBuilder\FunctionList;
use JBus\JSBuilder\CheckBoxBuilder;
use JBus\Widget\Component;

class CheckBox extends Component
{
	protected $jsFunctionName=FunctionList::CHECKBOX;
	protected $templatePath='checkBox-template.php';
	protected $checkedValue=1;
	
	public function onCreate()
	{
		parent::onCreate();
		$this->setCheckedValue(1);
		
	}
	
	public function getBuilder()
	{
		if(NULL==$this->builder)
		{
			$this->builder=new CheckBoxBuilder($this);
		}
		return $this->builder;
	}
	
	public function setChecked()
	{
		$this->state->checked='true';
		return $this;
	}
	
	public function setUnChecked()
	{
		$this->state->checked='false';
		return $this;
	}
	
	public function registerOnChange($obj,$methodCallback)
	{
		$this->getEventWidget()->addEvent('change', $obj,$methodCallback);
		return $this;
	}
	
	public function getCheckedValue()
	{
		return $this->checkedValue;		
	}
	
	public function setCheckedValue($value)
	{
		$this->checkedValue=$value;
		$this->state->set('value',$value);
		$this->setUnChecked();
	}
	
	public function isChecked()
	{
		return  $this->state->get('value')!=false;
	}
}


?>