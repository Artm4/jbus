<?php
namespace WidgetBase;

use JBus\JSBuilder\FunctionList;
use JBus\JSBuilder\TimePickerBuilder;

class TimePicker extends FormElement
{
	public $seconds;
	public $format;
	protected $jsFunctionName=FunctionList::TIME;
	protected $templatePath='timePicker-template.php';
	
	public function onCreate()
	{
		parent::onCreate();
		 
	}
	
	public function getBuilder()
	{
		if(NULL==$this->builder)
		{
	
			$this->builder=new TimePickerBuilder($this);
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
		
		$timeStamp=strtotime($value);
		if ($timeStamp===false)
		{
			$newTime ='';
		}
		else
		{
			$newTime = date('H:i:s',$timeStamp );
		}
			
		return $newTime;
	}
	
	public function setFormat24h()
	{
		$this->format=24;
		$this->state->timePattern='HH:mm'.$this->seconds;
	}
	
	public function setFormat12h()
	{
		$this->format=12;
		$this->state->timePattern='h:mm'.$this->seconds.' a';
	}
	
	public function showSeconds()
	{
		$this->seconds=':ss';
		
		if ($this->format==24)
		{
			$this->setFormat24h();
		}
		else 
		{
			$this->setFormat12h();
		}
		
	}
	
	public function setDropDownIncrement($hour,$minutes)
	{		
		$h=sprintf('%02d',$hour);
		$m=sprintf('%02d',$minutes);
		$this->state->dropDownIncrement="T$h:$m:00";
	}
	
	public function setDropDownBaseIncrement($hour,$minutes)
	{
		$h=sprintf('%02d',$hour);
		$m=sprintf('%02d',$minutes);
		$this->state->dropDownBaseIncrement="T$h:$m:00";
	}
	
	public function setValue($value)
	{
		$timeStamp=strtotime($value);
		
		if ($timeStamp===false)
		{
			$newTime ='00:00:00';
		}
		else 
		{
			$newTime = date('H:i:s',$timeStamp );
		}
		
		$this->state->set('value',"T".$newTime);
		return $this;
	}
}