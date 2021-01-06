<?php
namespace WidgetBase\Grid;

use JBus\Widget\Component;

class GridColumn
{
    protected $label;
    protected $widgetList=array();
    protected $columnKey;
    
    public function __construct($columnKey,$label)
    {
        $this->columnKey=$columnKey;
        $this->label=$label;        
    }
    
    public function setLabel($label)
    {
        $this->label=$label;
        return $this;
    }
    
    public function getColumnKey()
    {
        return $this->columnKey;
    }
    
    public function getLabel()
    {
        return $this->label;
    }
    
    public function setWidgetList($widgetList=array())
    {
        $this->widgetList=$widgetList;
        return $this;
    }
    
    public function getWidgetList()
    {
        return $this->widgetList;
    }
    
    public function hasWidget()
    {
        return count($this->widgetList)>0;
    }
    
    public function addWidget(Component $widget)
    {
        $this->widgetList[]=$widget;
        return $this;
    }
       
}