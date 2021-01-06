<?php
use JBus\JSBuilder\CodeContainer;
use JBus\JSBuilder\CodeBuilder;
use JBus\JSBuilder\ComponentCodeBuilder;
use JBus\JSBuilder\FunctionList;
use WidgetBase\Button;

class JSBuilderTest extends PHPUnit_Framework_TestCase
{ 
    function setUp()
    {
        
    }
    
    public function test()
    {
        
    }
    
    public function testListOptionAll()
    {
        
    }
    
    public function testCodeContainer()
    {
        $code=new CodeContainer();
        $builder=new CodeBuilder($code);        
        $builder->createObject('state123', FunctionList::STATEFUL);
        
        $builder->createObject('mem1', FunctionList::MEMORY);       
        $builder->setVar('data',$builder->arr(
                array(
                        $builder->object(
                                array('id'=> $builder->valueInt(1), 'name'=>$builder->valueString('we'),'label'=>$builder->valueString("<i>we</i> <img src='http://placekitten.com/50/70' />",'"'))
                                ),
                        $builder->object(
                                array('id'=> $builder->valueInt(2), 'name'=>$builder->valueString('are'),'label'=>$builder->valueString("<u>are</u> <img src='http://placekitten.com/50/60' />",'"'))
                                ),
                       $builder-> object(
                                array('id'=> $builder->valueInt(3), 'name'=>$builder->valueString('kittens'),'label'=>$builder->valueString("<b>kittens</b> <img src='http://placekitten.com/50/50' />",'"'))
                                )
                )
                ));
        $builder->callMethod('mem1','setData',$builder->arguments('data'));        
        $builder->createObject('filt1', FunctionList::FILTERING_SELECT,
                $builder->object(array(
                        'store'=>$builder->valueCode($builder->getObject('mem1')),
                        'obj'=> $builder->object(
                                array('some'=>$builder->valueString('value'))
                                ),
                        'labelAttr'=>$builder->valueString('label'),
                        'labelType'=>$builder->valueString('html'),
                ))
                );       
        $builder->setVar('var1',$builder->object(array(
                'store'=>$builder->valueCode($builder->getObject('mem1')),
                'obj'=> $builder->object(
                        array('some'=>$builder->valueString('value'))
                        ),
                'labelAttr'=>$builder->valueString('label'),
                'labelAttr'=>$builder->valueInt('12'),
        )));
        $builder->callMethod('filt1','placeAt',$builder->arguments('grid'));       
        $builder->callMethod('filt1','startup');
        $builder->set('state123',$builder->arguments(array('key','val')));
        $this->assertCount(9, $code->getBlockIterator());
        echo implode(";\n", $code->getBlockIterator());
    }
    
    public function testCodeWidgetContainer()
    {
        $el=new Button();
        $code=new CodeContainer();
        $builder=new ComponentCodeBuilder($code,$el);
        $builder->set('state123',$builder->arguments(array('key','val')));
        $builder->compSet($builder->arguments(array('key1','val1')));
        $builder->compCreateObject('Button');
        $builder->compStateSet('label',$builder->valueString('some String'));
        echo implode(";\n", $code->getBlockIterator());
    }
    
    public function testCodeBuilder()
    {
    
    }
}