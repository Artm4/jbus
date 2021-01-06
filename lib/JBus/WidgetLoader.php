<?php
namespace JBus;

class WidgetLoader
{
    public static $_instance=null;   
    
    public static function getInstance()
    {
        if(!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    protected function encodeWidgetName($widgetName)
    {
        return str_replace('\\', '_', $widgetName);
    }
  
    public function createWidgetType($widgetName,$parentWidgetName,$widgetProperty)
    {   
        $widgetNameClear=$this->encodeWidgetName($widgetName);
        $parentWidgetNameClear=$this->encodeWidgetName($parentWidgetName);
        $widgetType=sprintf("%s-%s-%s",        
            $widgetNameClear,           
            $parentWidgetNameClear,
            $widgetProperty
        );
        return $widgetType;
    }    

    public function parseWidgetType($type)
    {
        $result=array();        
        preg_match("/(\w*)\-(\w*)\-(.*)/", $type,$matches);
        //$namespace,$widgetName,$parentWidgetName,$widgetProperty;      
        $result['widgetName']=$matches[1];       
        $result['parentWidgetName']=$matches[2];
        $result['widgetProperty']=$matches[3];
        return $result;        
    }

    public function createClass($widgetName)
    {
        $widgetNameClear=str_replace('_', '\\', $widgetName);
        $class=$widgetNameClear;
        $instance=new $class(true);
        return $instance;
    }
    
    public static function isPathAbsolute($path)
    {
        $directorySeparator = DIRECTORY_SEPARATOR;    
        if(DIRECTORY_SEPARATOR == '\\')
        {
            $directorySeparator = '\\\\';
        }
        //Remove trailing slashes
        $path = preg_replace("#".$directorySeparator."+$#", "", $path);
        $absPath = realpath($path);
        return $flAbsolute = $path == $absPath;
    }    
}