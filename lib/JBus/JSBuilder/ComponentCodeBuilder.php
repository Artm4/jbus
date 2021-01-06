<?php
namespace JBus\JSBuilder;
use JBus\Widget\Component;


class ComponentCodeBuilder extends CodeBuilder
{
    protected $codeContainer;
    protected $widget;
    protected $state;
    protected $id;
    
    const KEY_STATE='jbstate';
    
    public function __construct(CodeContainer $codeContainer,Component $widget)
    {        
        $this->widget=$widget;        
        parent::__construct($codeContainer);        
    }   
    
    public static function createLocalInstance(Component $widget)
    {
        $codeContainer=CodeContainer::createLocalInstance();
        return new self($codeContainer,$widget);
    }
    
    public function createLocal()
    {
        return self::createLocalInstance($this->widget);
    }
    
    public function getCodeContainer()
    {
        return $this->codeContainer;
    }
    
    public function setCodeContainer($container)
    {
        $this->codeContainer=$container;
        return $this;
    }
    
    public function compGetObject()
    {   
        return parent::getObject($this->getId());
    }
    
    public function compSetObject($objectName)
    {   
        return parent::setObject($this->getId(), $objectName);
    }
    
    public function compCreateObject($functionName,$argString='')
    {        
        return parent::createObject($this->getId(), $functionName,$argString);    
    }
    
    public function compCreateEmpty()
    {
        $code=sprintf("jbus.OP.gI().set({$this->quot}%s{$this->quot},{})",$this->getId());
        return $this->codeContainer->addBlock($code);
    }
    
    public function compCreateState()
    {
        $code=$this->assign($this->prop($this->compGetObject(), self::KEY_STATE), '{}');
        return $this->codeContainer->addBlock($code);
    }
    
    public function compCallMethod($method,$argString='')
    {
        return parent::callMethod($this->getId(), $method, $argString);
    }
    
    public function compCallMethodResult($method,$argString='')
    {
        return parent::callMethodResult($this->getId(), $method, $argString);
    }
    
    public function compSet($argString='')
    {
        return parent::set($this->getId(), $argString);
    }
    
    public function compGet($argString='')
    {
        return parent::get($this->getId(), $argString);
    }
    
    public function compStateSet($key, $value)
    {        
        $prop=$this->prop($this->prop($this->compGetObject(),self::KEY_STATE),$key);
        $code=$this->assign($prop,$value);
        return $this->codeContainer->addBlock($code);
    }
    
    public function compPlaceAt($domId,$position='replace')
    {
        return $this->compCallMethod('placeAt',$this->arguments(array($this->valueString($domId),$this->valueString('wrap'))));        
    }
    
    public function compDefineFunction($name, $body)
    {
        return $this->defineFunction($this->getId(), $name, $body);
    }
    
    public function compProp($prop)
    {
        return $this->prop($this->getObject($this->getId()), $prop);
    }
    
    public function defineEventMethod($eventName)
    {
        $this->getJSFunction(FunctionList::REQUEST_JBUS);
        $this->codeContainer->stopBlockStore();
        $ifStatement=sprintf("typeof %s!={$this->quot}undefined{$this->quot}",$this->compProp(self::METHOD_ON_CLIENT_REQUEST));
        $trueBlock=$this->compCallMethod(self::METHOD_ON_CLIENT_REQUEST);
        $callMethod=$this->ifBlock($ifStatement, $trueBlock);
        $callMethod.=$this->separator();
        $callMethod.=$this->callMethod(
                FunctionList::REQUEST_JBUS,
                'postJbus',
                $this->arguments(
                        array(
                                $this->valueString($this->getId()),
                                $this->valueString($eventName)
                        )
                        ));
        
        $callMethod.=$this->separator();
        //$callMethod.="console.log('changed'+this.get('value'));";
        
        $this->codeContainer->restoreBlockStore();
        return $callMethod;
    }
    
    public function compOnEvent($eventName,$domEvent='')
    {
        if(!strlen($domEvent))
        {
            $domEvent=$eventName;
        }
       
        $callMethod=$this->defineEventMethod($eventName);
        //$callMethod.="console.log('changed'+this.get('value'));";
        
        $this->codeContainer->restoreBlockStore();
        return $this->compCallMethod('on',$this->arguments(
                array(
                        $this->valueString($domEvent),
                        $this->valueFunction('', $callMethod),
                )
        ));
    }
    
    public function compDefineOnClientRequest($body)
    {
        return $this->compDefineFunction(self::METHOD_ON_CLIENT_REQUEST, $body);
    }
    
    public function getId()
    {
        return $this->widget->getBuilder()->getWidgetId();
    }
    
    public function prepare()
    {
           
    }
}