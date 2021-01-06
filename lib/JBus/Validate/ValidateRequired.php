<?php
namespace JBus\Validate;
class ValidateRequired extends ValidateRegex
{   
    protected $regexClient='.+';
    protected $message="Value is required!";
    
    public function __construct($valueName='')
    {
        parent::__construct($valueName);
    }
    
    public function isValid($value,$params = NULL)
    {   
        $result=!empty($value);       
        return $result;
    }
}