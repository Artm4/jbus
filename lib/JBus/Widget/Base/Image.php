<?php
namespace WidgetBase;

use JBus\JSBuilder\FunctionList;
use JBus\Widget\Component;
use JBus\Widget\State;
use JBus\JSBuilder\ImageBuilder;

class Image extends Component
{
    protected $jsFunctionName=FunctionList::IMAGE;
    protected $templatePath='image-template.php';
   
    public function onCreate()
    {
        parent::onCreate();       
        $this->setValue('');
    }
    
    public function getBuilder()
    {
        if(NULL==$this->builder)
        {
            $this->builder=new ImageBuilder($this);
        }
        return $this->builder;
    }
    
    public function setValue($value)
    {
        $this->state->set('value',$value);
        return $this;
    }
    
    public function getValue($value)
    {
        $this->state->get('value');
    }
    
    public function setUrl($url)
    {
        $this->setValue($url);
        return $this;
    }
    
    public function getUrl()
    {
        $this->getValue();
    }
    
    public function setWidth($value)
    {
        $this->state->set('width',$value);
        return $this;
    }
    
    public function getWidth($value)
    {
        return $this->state->get('width');
    }
    
    public function setHeight($value)
    {
        $this->state->set('height',$value);
        return $this;
    }
    
    public function getHeight($value)
    {
        return $this->state->get('height');
    } 
}