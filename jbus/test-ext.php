<?php

interface O
{
    
}

class A
{
    public $val=1;
    function echoA()
    {
        print_r('a');
    }
}

class B extends A
{
    public $val=2;
    function echoA()
    {
        print_r('b');
    }
    function echoB()
    {
        print_r('b');
    }
}

class C
{
    function setObj(A $o)
    {
        print_r($o);
        $o->echoA();
        $o->echoB();
    }
    
    public static function create(A $o)
    {
    
    }
}
    
class D extends C
{
    public static function create(B $o)
    {
        
    }
}

$o=new B();
$h=new D();
$h->setObj($o);