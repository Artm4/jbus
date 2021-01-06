<?php
namespace JBus\Validate;
use JBus\Validate\JSBuilder\BuilderRegex;

class ValidateRegex extends ValidateBase
{
    protected $regexServer='';
    protected $regexClient='';
    protected $regexDelimeter='/';
    protected $message="Value is not valid";
    
    function __construct($valueName='',$regexServer='',$regexClient='')
    {
        parent::__construct($valueName);
        if(strlen($regexServer))
        {
            $this->regexServer=$regexServer;
        }
        if(strlen($regexClient))
        {
            $this->regexClient=$regexClient;
        }
        if(!strlen($this->regexClient))
        {
            $this->regexClient=$regexServer;
        }
        $this->builder=new BuilderRegex($this);
    }
    public function isValid($value,$params = NULL)
    {       
        $regexClear=sprintf("%s%s%s",$this->regexDelimeter,$this->regexServer,$this->regexDelimeter);
        $result=preg_match($regexClear, $value);
        return $result;
    }
    
    public function getRegexClient()
    {
        return $this->regexClient;
    }
}