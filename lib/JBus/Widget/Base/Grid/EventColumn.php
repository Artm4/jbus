<?php
namespace WidgetBase\Grid;

use JBus\Widget\EventWidget;
use JBus\Widget\Component;

class EventColumn extends EventWidget
{
    protected $grid;
    function __construct(Component $widget,$grid)
    {   
        $this->grid=$grid;
        parent::__construct($widget);
        $this->copyOriginalEvent();
    }
    
    public function addEvent($eventName,$obj,$methodCallback,$domEvent='')
    {
        $nameClear=$this->createEventName($name);        
        $this->grid->getEventWidget()->addEvent($nameClear,$obj,$methodCallback,$domEvent);
        parent::addEvent($nameClear,$obj,$methodCallback,$domEvent);
    }
    
    protected function copyOriginalEvent()
    {
        $originalList=$this->widget->getEventWidget()->getEventList();        
        foreach($originalList as $name => $callback)
        {
            $eventName=$this->createEventName($name);
            $this->grid->getEventWidget()->addEventCallback($eventName,$callback,$this->widget->getState()->get($name));
            $this->addEventCallback($eventName,$callback,$this->widget->getState()->get($name));
        }
    }
    
    protected function createEventName($eventNameBase)
    {
        $counter=$this->grid->getBuilder()->columnEventCounter;
        $this->grid->getBuilder()->columnEventCounter++;
        $nameClear=sprintf("%s_%s",$eventNameBase,$counter);
        return $nameClear;
    }
}