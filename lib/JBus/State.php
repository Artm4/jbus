<?php
namespace JBus;

class State
{
    protected $data=array();
    
    function __set($key,$value)
    {
        $this->data[$key]=$value;
    }
    
    function __get($key)
    {
        return $this->data[$key];
    }
    
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function get($key,$default=NULL)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }
    
    public function set($key,$value)
    {
        $this->data[$key]=$value;
    }
    
    public function isPropSet($key)
    {
        return isset($this->data[$key]);
    }
    
    public function setFromArray($arr)
    {
        foreach ($arr as $k => $v)
        {   
            $this->data[$k]=$v;
        }
    }
    
    function cloneArray($arr)
    {
        $newArr=array();
        foreach ($arr as $k=>$v)
        {
            if(is_array($v))
            {
                $newArr[$k]=$this->cloneArray($v);
            }
            else
            {
                $newArr[$k]=$v;
            }
        }
        return $newArr;
    }
    
    public function createClone()
    {
        $state=new State();        
        $state->setFromArray($this->cloneArray($this->data));
        return $state;
    }
}
