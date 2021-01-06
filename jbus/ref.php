<?php
class Arr
{
    protected $arr=array('a'=>array());
    function &some()
    {        
        return $this->arr['a'];
    }
    
    public function getArr()
    {
        return $this->arr;
    }
}
$a=new Arr();
$arr=&$a->some();
$arr[]=1;
print_r($a->getArr());