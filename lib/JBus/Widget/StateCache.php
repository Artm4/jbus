<?php
namespace JBus\Widget;
use JBus\Request;


class StateCache
{
    private $container=array();
    public static $_instance=null;
    
    public static function getInstance()
    {
        if(!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function populate(Request $request)
    {
        foreach($request->getTree() as $state)
        {
            if($request->getTargetId()==$state[StateWidget::KEY_ID])
            {
                $request->setTarget($state);
            }
            $this->container[$state[StateWidget::KEY_TYPE]]=$state;
        }
    }
    
    public function loadState(Component $widget)
    {
        $type=$widget->getBuilder()->getWidgetType();        
        if(!isset($this->container[$type]))
        {
            return false;
        }
        $stateData=$this->container[$type];
        //print_r($stateData);
        $widget->getState()->setFromArray($stateData);
    }
}