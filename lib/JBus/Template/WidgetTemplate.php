<?php
namespace JBus\Template;
use JBus\Template\TemplateParser;

class WidgetTemplate
{
    private $templatePath;
    private $placeToId;
    private $parser;
    private $widgetId='';
    
    function __construct($templateString)
    {       
        $this->widgetId=$this->generateId();
        $this->parser=new TemplateParser($templateString,$this->widgetId);
    }
    
    /**
     * @param string $templatePath
     * @throws Exception
     * @return \JBus\Template\WidgetTemplate
     */
    public static function createFromFile($templatePath)
    {
        if(!file_exists($templatePath))
        {
            throw new \Exception("Cannot load template file {$templatePath}");   
        }        
        $templateContent=file_get_contents($templatePath);
        
        return new self($templateContent);       
    }
    
    public function placeIn(WidgetTemplate $template,$placeTag)
    {
        $xmlToAdd=$this->getParser()->getDom();
        $xmlTarget=$template->getParser()->getDomByPlace($placeTag);
        
        $toDom = dom_import_simplexml($xmlTarget);
        $fromDom = dom_import_simplexml($xmlToAdd);
        $toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));
    }
    
    public function getWidgetId()
    {
        return $this->widgetId;
    }
    
    public function getParser()
    {
        return $this->parser;
    }
    
    public function asXml()
    {
        return $this->getParser()->getDom()->asXML();
    }
    
    private function generateId()
    {
        return uniqid();
    }
}