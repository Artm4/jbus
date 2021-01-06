<?php 
namespace WidgetBase;

use JBus\JSBuilder\FunctionList;
use JBus\JSBuilder\TimePickerBuilder;
use JBus\Widget\Component;

class DateAndTimePicker extends Component
{
	
	//protected $jsFunctionName=FunctionList::TIME;
	protected $templatePath='dateAndTimePicker-template.php';
	
	public $time;
	public $date;
	
	public function onCreate()
	{
		$this->time=new TimePicker();
		$this->date=new DatePicker();
	}
	
	public function setValue($value)
	{
		$this->date->setValue($value);
		$this->time->setValue($value);
		return $this;
	}
	
	public function getValue()
	{
		
		$value=$this->date->getValue()." ".$this->time->getValue();
		return $value;
	}
	
	public function setTimeFormat24h()
	{
		$this->time->setFormat24h();
	}
	
	public function setTimeFormat12h()
	{
		$this->time->setFormat12h();
	}
	
	public function showTimeDopDownSeconds()
	{	
		$this->time->showSeconds();
	}
	
	public function setTimeDropDownIncrement($hour,$minutes)
	{
		$this->time->setDropDownIncrement($hour, $minutes);
	}
	
	public function setTimeDropDownBaseIncrement($hour,$minutes)
	{
		$this->time->setDropDownBaseIncrement($hour, $minutes);
	}
	
}


?>