<?php
namespace JBus\Widget;

class EventWidget
{
    protected $eventList=array();
    protected $widget;
    
    function __construct($widget)
    {
        $this->widget=$widget;
    }
    public function addEvent($eventName,$obj,$methodCallback,$domEvent='')
    {
        $eventCallback=array($obj,$methodCallback);
        $this->eventList[$eventName]=$eventCallback;
        $this->widget->getState()->set($eventName,$domEvent,true);
    }    
    public function addEventCallback($eventName,$eventCallback,$domEvent='')
    {
        $this->eventList[$eventName]=$eventCallback;
        $this->widget->getState()->set($eventName,$domEvent,true);
    }
    public function getEventCallback($eventName)
    {
        if(!$this->hasEvent($eventName))
        {
            throw new \Exception("Cannot find event {$eventName}");
        }
        return $this->eventList[$eventName];
    }
    public function callEventCallback($eventName)
    {
        $eventCallback=$this->getEventCallback($eventName);
        return call_user_func($eventCallback);
    }
    public function hasEvent($eventName='')
    {
        if(''==$eventName)
        {
            return count($this->eventList);
        }
        return isset($this->eventList[$eventName]);
    }
    public function getEventList()
    {
        return $this->eventList;
    }    
    public function resetEventList()
    {
        $this->eventList=array();
    }
}