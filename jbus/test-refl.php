<?php
class Refl
{
    protected $prop=NULL;
    protected $reflection;
    public function getReflection()
    {
        if($this->reflection==NULL)
        {
            $this->reflection=new \ReflectionClass($this);
        }
        return $this->reflection;
    }   
    
    public function test()
    {
        $refl=$this->getReflection();
        $properties=$refl->getProperties();
        foreach($properties as $property)
        {
            $propertyName=$property->getName();
            $obj=$this->{$propertyName};
            var_dump($obj);
        }
    }
}

$r=new Refl();
$r->test();
echo $r->getReflection()->getFileName();