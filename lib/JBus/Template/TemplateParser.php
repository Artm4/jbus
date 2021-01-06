<?php
namespace JBus\Template;

class TemplateParser
{  
    protected $templateString;
    protected $widgetId;
    protected $dom=NULL;
    protected $simpleXML=NULL;
    const ID_PREFIX='id';
    
    function __construct($templateString,$widgetId)
    {
        $this->templateString=$templateString;
        $this->widgetId=$widgetId;
    }
    
    public function getDom()
    {
        if(NULL==$this->dom)
        {
            $this->parseAsDom();
        }
        return $this->dom;
    }
    
    public function getSimpleXML()
    {
        if(NULL==$this->simpleXML)
        {
            $this->parseAsDom();
        }
        return $this->simpleXML;
    }
   
    private function setElementId($simpleXmlElement,$id)
    {   
        $this->dom->setAttribute('id',$id);
    }
    
    private function parseAsDom()
    {   
        $this->simpleXML=simplexml_load_string($this->templateString);
        $this->dom=dom_import_simplexml($this->simpleXML);
        $this->setElementId($this->dom, $this->widgetId);
        if(false===$this->dom)
        {
            throw new \Exception("Cannot parse template");
        }        
        return $this;
    }
}