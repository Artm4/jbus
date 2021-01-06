<?php
namespace WidgetBase;

class LabelDateTime extends Label
{
		
	
	public function setValue($value)
	{
		$dateStamp=strtotime($value);
		
		if ($dateStamp===false)
		{
			$newDate ='';
		}
		else
		{
			$newDate = date('d/m/y H:i:s',$dateStamp );
		}
		
		$this->state->set('value',$newDate);
	}
	
}