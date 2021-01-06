<?php
use TestWidget\CompositeWidget;
use TestWidget\InnerWidget;
use WidgetBase\Button;
use JBus\Widget\StateWidget;

class CompositeTest extends PHPUnit_Framework_TestCase
{
    function setUp()
    {
        
    }
    
    function testImportTemplate()
    {
        $el=new CompositeWidget();
        $el->getBuilder()->build();        
        echo $xml=$el->__toString();
        $this->assertNotEmpty($xml);
        print_r($el->button->getState()->getData());
        print_r($el->button->getBuilder()->getCodeContainer()->getBlockIterator());
    }
    
    function testUpdateState()
    {        
        Button::setStageTarget(StateWidget::STAGE_UPDATE);
        $el=new CompositeWidget();        
        echo $xml=$el;
        $this->assertNotEmpty($xml);
        $el->button->getState()->setStage(StateWidget::STAGE_UPDATE);
        $el->button->setLabel('SOme new label');
        $el->getBuilder()->build();
        print_r($el->button->getState()->getData());
        print_r($el->button->getBuilder()->getCodeContainer()->getBlockIterator());
    }
    
    function testUpdateNewState()
    {
        Button::setStageTarget(StateWidget::STAGE_UPDATE);
        $el=new CompositeWidget();        
        $el->button->getState()->setStage(StateWidget::STAGE_UPDATE);
        $el->button->setLabel('SOme new label');
        $el->getBuilder()->build();
        echo $xml=$el;
        $this->assertNotEmpty($xml);
        foreach($el->button->getState()->stateList as $state)
        {
            print_r($state->getData());
        }
        print_r($el->button->getState()->getData());
        print_r($el->button->getBuilder()->getCodeContainer()->getBlockIterator());
    }
    
    public function testCreateWidget()
    {
        $el=new Button();
        $el->setLabel('label');
        $el->getState()->setStage(StateWidget::STAGE_CREATE);
        print_r($el->getState()->getTargetState());
        $el->getBuilder()->build();
        $code=$el->getBuilder()->getCodeContainer();
        print_r($code->getBlockIterator());
        $el=new Button();    
        Button::setStageTarget(StateWidget::STAGE_UPDATE);
        //$el->getState()->setStage(StateWidget::STAGE_UPDATE);
        $el->setLabel('label');
        print_r($el->getState()->getTargetState());
        $el->getBuilder()->build();
        $code=$el->getBuilder()->getCodeContainer();
        print_r($code->getBlockIterator());
    }
    
    public function testCreateCompositeWidget()
    {
        $el=new CompositeWidget();
        $el->getBuilder()->build();
        print_r($el->getState()->getData());
        print_r($el->getBuilder()->getCodeContainer()->getBlockIterator());
    }
}