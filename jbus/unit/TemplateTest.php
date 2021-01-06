<?php
use JBus\Template\TemplateParser;
use JBus\Template\WidgetTemplate;

class TemplateTest extends PHPUnit_Framework_TestCase
{ 
    private $parser;
    private $subject;
    private $toAdd;
    
    function setUp()
    {
        $this->subject=$subject="
<div>
    <button data-widget  ='button'>
    </button>
    <div data-widget='productExtConfig'>
    </div>        
    <div data-widget='inputName' id='123-inputName'>
    </div>
</div>";
        $this->parser=new TemplateParser($subject,'123');
        
        $this->toAdd=$toAdd="<div>
    <button data-widget  ='button'>
    </button>    
    <div data-widget='inputName' id='223-inputName'>
    </div>
</div>  
";      
        $this->parserSecond=new TemplateParser($toAdd,'223');
    }
    
    public function testGetDom()
    {
        
        $domNode=$this->parser->getDomByPlace('inputName');        
        $this->assertEquals('div', $domNode->getName());
    }
    
    public function testGetIdByPlace()
    {
        $id=$this->parser->getIdByPlaceTag('grid');
        $this->assertEquals('123-grid', $id);
    }    
    
    public function testImportTemplate()
    {
        $expected="
<div>
    <button data-widget  ='button'>
    </button>
    <div data-widget='productExtConfig'>
            <div>
                <button data-widget  ='button'>
                </button>    
                <div data-widget='inputName' id='223-inputName'>
                </div>
            </div> 
    </div>        
    <div data-widget='inputName' id='123-inputName'>
    </div>
</div>";
        
        $templateMain=new WidgetTemplate($this->subject);
        $templateToAdd=new WidgetTemplate($this->toAdd);
        
        $templateToAdd->placeIn($templateMain, 'productExtConfig');
               
        $expectedElement=dom_import_simplexml(simplexml_load_string($expected));
        $actualElement = dom_import_simplexml($templateMain->getParser()->getDom());
       
        $this->assertEqualXMLStructure($expectedElement, $actualElement);
    }
    
    public function testCreateWidgetFromFile()
    {
        $templatePath=dirname(__FILE__).'/src/template.php';
        $widgetTemplate=WidgetTemplate::createFromFile($templatePath);
        $this->assertNotEmpty($widgetTemplate->asXml());
    }
    
    public function testLoadDomByTag()
    {
        $this->parser->loadDomElementsByTag();        
        $this->assertContains('id=', $this->parser->getDom()->asXML());
    }
}