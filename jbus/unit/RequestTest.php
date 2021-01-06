<?php
use JBus\WidgetLoader;
use JBus\JSBuilder\CodeContainer;

class RequestTest extends PHPUnit_Framework_TestCase
{
    function setUp()
    {
        
    }
    
    function testCreateWidget()
    {   
        $type=WidgetLoader::getInstance()->createWidgetType('WidgetBase', 'Button', '', '');
        $result=WidgetLoader::getInstance()->parseWidgetType($type);        
                
        $instance=WidgetLoader::getInstance()->createClass($type);
        $builder=$instance->getBuilder();        
    }
}