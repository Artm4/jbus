<?php
namespace WidgetBase;

use JBus\JSBuilder\FormElementBuilder;
use JBus\Widget\Component;
use JBus\Widget\State;
use JBus\Validate\ValidateBase;
use JBus\Validate\ValidateRequired;

abstract class FormElement extends Component
{   
    protected $validatorList=array();
    public function onCreate()
    {
        parent::onCreate();        
        $this->state->set('value','');
        $this->state->set('required','false',1);
    }
    
    public function getBuilder()
    {
        if(NULL==$this->builder)
        {
            $this->builder=new FormElementBuilder($this);
        }
        return $this->builder;
    }
    
    public function setValue($value)
    {
        $this->state->set('value',$value);
        return $this;
    }
    
    public function getValue()
    {
        return $this->state->get('value','');
    }
    
    public function setPlaceholder($placeholder)
    {
        $this->state->set('placeholder',$placeholder,true);
        return $this;
    }
    
    public function registerOnChange($obj,$methodCallback)
    {
        $this->getEventWidget()->addEvent(FormElementBuilder::EVENT_CHANGE, $obj,$methodCallback);
        return $this;
    }
    
    public function addValidator(ValidateBase $validator)
    {
        $this->validatorList[]=$validator;
        if(!$validator->hasBuilder())
        {
            return $this;
        }
        $validator->getBuilder()->setWidget($this);
        if(!$this->state->isPropSet('validatorList'))
        {
            $this->state->set('validatorList',array(),1);
        }
        $list=$this->state->get('validatorList');        
        $list[]=$validator;       
        $this->state->set('validatorList',$list,1);
        return $this;
    }    
    
    public function getValidatorList()
    {
        return $this->validatorList;
    }
    
    public function setRequired($valueName='')
    {
        $validatorRequired=new ValidateRequired($valueName);
        $this->addValidator($validatorRequired);
        $this->state->set('required',true,true);
        return $this;
    }
}