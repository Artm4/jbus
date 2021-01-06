<?php
namespace Ext;

define('LIB_PATH', realpath(dirname(__FILE__).'/../lib'));
define('ROOT_PATH', dirname(__FILE__));
define('VENDOR_PATH', dirname(__FILE__).'/../vendor');

$loader=require VENDOR_PATH.'/autoload.php';



use Ext\TestComposeB;
use ReflectionClass;

$c=new TestComposeB();
$c->runTest();
new ReflectionClass($c);