<?php
namespace JBus\Validate;
class ValidateWidget
{
    protected $validatorList=array();
    protected $widgetList=array();
    protected $resultMessageList=array();
    protected $params=array();
    
    public function isValid($widgetList=array())
    {
        if(count($widgetList))
        {
            $this->addWidgetList($widgetList);
        }
        $isValid=true;
        $isValidCurrent=true;
        foreach($this->validatorList as $validatorData)
        {
            $validator=$validatorData[0];
            $value=$validatorData[1];
            $isValidCurrent=$validator->isValid($value,$this->params);
            if(!$isValidCurrent)
            {
                $isValid=false;
                $this->addResultMessage($validator->getResultMessage());
            }
        }
        return $isValid;
    }
    
    public function setParams($params)
    {
        $this->params=$params;
    }
    
    public function addWidget($widget)
    {   
        $list=$widget->getValidatorList();
        foreach($list as $validator)
        {
            $this->addValidator($validator, $widget->getValue());
        }
    }
    
    public function addWidgetList($widgetList)
    {
        foreach($widgetList as $widget)
        {
            $this->addWidget($widget);
        }
    }
    
    public function addValidator(ValidateBase $validator,$value)
    {
        $this->validatorList[]=array($validator,$value);
    }
    
    /*
     * array(
     *     array(validator,value),
     *     array(validator,value)
     * )
     */
    public function addValidatorList($validatorList)
    {
        foreach($validatorList as $validatorData)
        {
            $this->addValidator($validatorData[0],$validatorData[1]);
        }
    }
    
    public function getResultMessageList()
    {
        return $this->resultMessageList;
    }
    
    public function getResultMessage($separator="\n")
    {
        return implode($separator, $this->getResultMessageList());
    }
   
    protected function addResultMessage($message)
    {
        $this->resultMessageList[]=$message;
    }
}