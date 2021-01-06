<?php
namespace JBus\Validate;
use JBus\Validate\JSBuilder\BuilderRange;

class ValidateRange extends ValidateBase
{   
    protected $message="Value should be between %s and %s";
    protected $min;
    protected $max;
    
    function __construct($valueName='',$min,$max)
    {
        $this->min=$min;
        $this->max=$max;
        $this->message=sprintf($this->message,$min,$max);
        parent::__construct($valueName);
        $this->builder=new BuilderRange($this);
    }
    public function isValid($value,$params = NULL)
    {       
        $value=intval($value);
        $result=$value>=$this->min&&$value<=$this->max;
        return $result;
    }
    
    public function getMin()
    {
        return $this->min;
    }
    
    public function getMax()
    {
        return $this->max;
    }
}