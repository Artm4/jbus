<?php
define('LIB_PATH', realpath(dirname(__FILE__).'/../lib'));
define('ROOT_PATH', dirname(__FILE__));
define('VENDOR_PATH', dirname(__FILE__).'/../vendor');

$loader=require VENDOR_PATH.'/autoload.php';

//$loader->addPsr4('JBus\\',LIB_PATH.'/JBus');
//$loader->addPsr4('TestWidget\\',ROOT_PATH.'/unit/src/TestWidget');
//$loader->addPsr4('WidgetBase\\',LIB_PATH.'/JBus/Widget/Base');
