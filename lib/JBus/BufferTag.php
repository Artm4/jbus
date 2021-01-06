<?php 
namespace JBus;

class BufferTag
{
    protected $container=array();
    
    public function prepend($tag,$content)
    {
        $this->prepareList($tag);
        array_unshift($this->container[$tag],$content);        
    }
    
    public function append($tag,$content)
    {
        $this->prepareList($tag);
        array_push($this->container[$tag],$content);
    }
    
    protected function prepareList($tag)
    {
        if(!isset($this->container[$tag]))
        {
            $this->container[$tag]=array();
        }
    }
    
    public function start()
    {
        ob_start();
    }
    
    public function end()
    {
        $out=ob_get_contents();
        ob_end_clean();
        return $out;
    }
    
    public function endPrepend($tag)
    {
        $this->prepend($tag,$this->end());
        return $this;
    }
    
    public function endAppend($tag)
    {
        $this->append($tag,$this->end());
        return $this;
    }
    
    public function out($tag)
    {
        $this->prepareList($tag);
        echo $this->getContent($tag);
        $this->clean($tag);    
        return $this;
    } 

    public function getContentClean($tag)
    {
        $this->prepareList($tag);
        $content=$this->getContent($tag);
        $this->clean($tag);
        return $content;
    }
   
    public function getContent($tag)
    {
        $content='';
        foreach($this->container[$tag] as $v)
        {
            $content.=$v."\n";
        }        
        return $content;
    }
    
    public function clean($tag)
    {
        unset($this->container[$tag]);
    }
    
    public function get($tag)
    {
        return $this->container[$tag];
    }
    
    public function getList()
    {
        return $this->container;
    }
}
?>