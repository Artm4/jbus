<?php
namespace JBus\Validate;
abstract class ValidateBase
{
    protected $message;    
    protected $builder=NULL;    
    protected $valueName='';
    
    function __construct($valueName='')
    {
        $this->valueName=$valueName;
    }
    
    public function setValueName($valueName)
    {
        $this->valueName=$valueName;
    }
    
    public function setMessage($message)
    {          
        $this->message=$message;
    }

    public function getMessage()
    {
        return $this->message;
    }
    
    public function getMessages()
    {
        return array($this->message);
    }
  
    public function getResultMessage()
    {
        $messageClear=$this->message;
        if(!empty($this->valueName))
        {
            $messageClear=sprintf("%s says: %s",$this->valueName,$this->message);
        }
        return $messageClear;
    }
    
    public function getBuilder()
    {
        return $this->builder;
    }
    
    public function hasBuilder()
    {
        return NULL!=$this->builder;
    }
    
    abstract public function isValid($value,$params=null);
}
