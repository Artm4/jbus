<?php
namespace WidgetBase;
use JBus\Widget\Component;
use JBus\JSBuilder\FunctionList;
use JBus\JSBuilder\DateTextBoxBuilder;
use JBus\JSBuilder\DatePickerBuilder;
class DatePicker extends FormElement
{	
	protected $jsFunctionName=FunctionList::DATE;
	protected $templatePath='datePicker-template.php';
	
	public function onCreate()
	{
		parent::onCreate();
	}
	
	public function getBuilder()
	{
		if(NULL==$this->builder)
		{
	
			$this->builder=new DatePickerBuilder($this);
		}
		return $this->builder;
	}
	
	 
	public function getValueDisplayed()
	{
		return $this->state->get('displayedValue','');
		
	}
	
	public function getValue()
	{
		$value=$this->state->get('displayedValue','');
		$date = str_replace('/', '-', $value);
		$dateStamp=strtotime($date);
		if ($dateStamp===false)
		{
			$newDate = '';
		}
		else 
		{
			$newDate = date('Y-m-d',$dateStamp );
		}
					
		return $newDate;
	}
	
	public function setValue($value)
	{
		$dateStamp=strtotime($value);
	
		if ($dateStamp===false)
		{
			$newDate ='';
		}
		else
		{
			$newDate = date('Y-m-d',$dateStamp );
		}
	
		$this->state->set('value',$newDate);
		return $this;
	}
}


