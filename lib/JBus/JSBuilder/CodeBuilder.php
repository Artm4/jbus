<?php
namespace JBus\JSBuilder;
use JBus\JSBuilder\CodeContainer;

class CodeBuilder
{
    protected $codeContainer;
    
    const METHOD_ON_CLIENT_REQUEST='onClientRequest';
    const QUOT='\'';
    protected $quot=self::QUOT;
        
    function __construct(CodeContainer $codeContainer)
    {
        $this->codeContainer=$codeContainer;
    }
    
    function getObject($id)
    {   
        return "jbus.OP.gI().get({$this->quot}{$id}{$this->quot})";
    }
    
    function setObject($id,$objectName)
    {
        $code=sprintf("jbus.OP.gI().set('%s',%s)",$id,$objectName);
        return $this->codeContainer->addBlock($code);
    }
    
    function createObject($id,$functionName,$argString='')
    {        
        $code=sprintf("jbus.OP.gI().set({$this->quot}%s{$this->quot},%s)",$id,$this->newInstance($functionName,$argString));
        return $this->codeContainer->addBlock($code);
    }
    
    function newInstance($functionName,$argString='')
    {
        $function=$this->getJSFunction($functionName);
        $code=sprintf("new %s(%s)",$function,$argString);
        return $code;
    }
    
    function getJSFunction($functionName)
    {    
        $this->codeContainer->addRequire($functionName);
        return $this->getObject($functionName);
    }
    
    function callMethod($id,$method,$argString='')
    {
        return $this->_callMethod($this->getObject($id),$method,$argString);        
    }    

    public function _callMethod($object,$method,$argString='')
    {
        return $this->codeContainer->addBlock(sprintf("%s.%s(%s)",$object,$method,$argString));
    }    

    function callMethodResult($id,$method,$argString='')
    {
        return (sprintf("%s.%s(%s)",$this->getObject($id),$method,$argString));
    }
    
    function _callMethodResult($object,$method,$argString='')
    {
        return (sprintf("%s.%s(%s)",$object,$method,$argString));
    }
   
    function valueFunction($functionArguments,$body)
    {
        $code=sprintf("function(%s){%s}",$functionArguments,$body);
        return $code;
    }
    
    function defineFunction($id,$name,$body)
    {
        $valueFunction=$this->valueFunction('', $body);
        $prop=$this->prop($this->getObject($id), $name);
        $code=$this->assign($prop, $valueFunction);
        $this->codeContainer->addBlock($code);
    }
        
    function setVar($varName,$value)
    {
        return $this->codeContainer->addBlock(sprintf("var %s=%s",$varName,$value));
    }
    
    function valueByType($v)
    {
        $code='';
        if(is_string($v)||NULL===$v)
        {
            $code=$this->valueString($v);
        }
        else
        if(is_int($v))
        {
            $code=$this->valueInt($v);
        }
        else
        if(is_array($v))
        {
            $code=$this->arr($v);
        }
        else
        {
            $code=$this->valueCode($v);
        }
        return $code;
        
    }
    
    function valueString($v,$quoteChar=self::QUOT)
    {
        return $quoteChar.$this->escape($v).$quoteChar;
    }
    
    function valueCode($v)
    {
        return $v;
    }
    
    function valueBoolean($v)
    {
        $boolean=$v?'true':'false';
        return $this->valueCode($boolean);
    }
    
    function valueInt($v)
    {
        return intval($v);
    }
    
    public function addBlock($code)
    {
        return $this->codeContainer->addBlock($code);
    }
    
    public function assign($operand,$value)
    {
        return sprintf("%s=%s",$operand,$value);        
    }
    
    public function prop($object,$prop)
    {
        return sprintf("%s[%s]",$object,$this->valueString($prop));        
    }
   
    public function set($id,$argString='')
    {
        return $this->callMethod($id, 'set', $argString);
    }
    
    public function get($id,$argString='')
    {
        return $this->callMethodResult($id, 'get', $argString);
    }
    
    public function ifBlock($ifStatement,$trueBlock,$elseBlock='')
    {
        return sprintf("if(%s){%s}else{%s}",$ifStatement,$trueBlock,$elseBlock='');
    }
    
    public function separator()
    {
        return CodeContainer::SEPARATOR_BLOCK;
    }
    
    function object($arr,$typeConversion=true)
    {
        $result="{";
        foreach ($arr as $k=>$v)
        {
            $vClear=$typeConversion?$this->valueByType($v):$v;
            $result.=sprintf("%s: %s,%s",$this->valueCode($k),$vClear,CodeContainer::SEPARATOR_NEW_LINE);
        }
        $result.="}";
        return $result;
    }
    
    function arr($arr,$typeConversion=true)
    {
        $result="[";
        foreach ($arr as $v)
        {
            $vClear=$typeConversion?$this->valueByType($v):$v;
            $result.=sprintf("%s,%s",$vClear,CodeContainer::SEPARATOR_NEW_LINE);
        }
        $result.="]";
        return $result;
    }
    
    function arguments($arr)
    {
        if(!is_array($arr))
        {
            $arr=array($arr);
        }
        return implode(",",$arr);
    }
    
    protected function escape($v)
    {
        return str_replace("\n", "\\n", $v);
        //return preg_replace("/\n/", "\\n", $v);
    }
}