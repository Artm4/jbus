<?php
include 'boot.php';

use JBus\Test;

$test=new Test;

if(!1==$test->test())
{
    echo "Failed";
}

interface O
{
    
}

class A
{
    public $val=1;
}

class B extends A
{
    public $val=2;
}

class C
{
    function setObj(A $o)
    {
        print_r($o);
    }
}
    
class D extends C
{
    function setObj(B $o)
    {
        print_r($o);
    }
}

$o=new A();
$h=new D();
$h->setObj($o);