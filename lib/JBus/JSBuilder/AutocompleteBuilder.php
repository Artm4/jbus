<?php
namespace JBus\JSBuilder;
use JBus\JSBuilder\ComponentBuilder;
use JBus\Widget\StateWidget;
use WidgetBase\Autocomplete;

class AutocompleteBuilder extends FormElementBuilder
{    
    const EVENT_SEARCH='search';
    public function onBuild()
    {  
        parent::onBuild();
        if(isset($this->state->disabled))
        {
        	$this->code->compSet($this->code->arguments(array($this->code->valueString('disabled'),$this->code->valueCode($this->state->disabled))));
        }
        if(isset($this->state->searchAttr))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('searchAttr'),$this->code->valueString($this->state->searchAttr))));
        }
        if(isset($this->state->labelAttr))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('labelAttr'),$this->code->valueString($this->state->labelAttr))));
        }
        if($this->state->isPropSet(StateWidget::KEY_CREATE))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('store'),$this->code->newInstance(FunctionList::MEMORY_LEGACY))));
        }
        if($this->state->isPropSet(self::EVENT_SEARCH))
        {
            $prop=($this->code->prop($this->code->compGetObject(),'_startSearch'));
            $propOld=($this->code->prop($this->code->compGetObject(),'_startSearchOld'));
            $code=$this->code->assign($propOld,$prop);
            $this->code->addBlock($code);
            
            $callMethod=$this->code->callMethodResult(
                    FunctionList::REQUEST_JBUS,
                    'postJbus',
                    $this->code->arguments(
                            array(
                                    $this->code->valueString($this->code->getId()),
                                    $this->code->valueString(self::EVENT_SEARCH)
                            )
                            ));
            
            $code=$this->code->assign($prop,
                    "function(text){return {$callMethod}.then(function(){return {$propOld}(text)})}");
            $this->code->addBlock($code);
            //$this->code->compOnEvent(self::EVENT_SEARCH);
        }
        if(isset($this->state->data))
        {   
            $arrBody=array();
            foreach($this->state->data as $obj)
            {
                $arrBody[]=$this->code->object($obj);                
            }
            $this->code->_callMethod($this->code->compGet($this->code->valueString('store')),
                    'setData',
            $this->code->arr($arrBody,false));            
        }
        if(isset($this->state->item))
        {
            $this->code->compSet($this->code->arguments(array(
                $this->code->valueString('item'),
                $this->code->object($this->state->item)
            )));
        }
    }
    
    protected function defineOnClientRequest()
    {
        $codeClientRequest=$this->code->createLocal();        
        $codeClientRequest->compStateSet('value', $codeClientRequest->compGet($this->code->valueString('value')));
        $codeClientRequest->compStateSet('searchText', 
             $codeClientRequest->prop($codeClientRequest->compGet($this->code->valueString('focusNode')),'value')
        );
        $this->code->compDefineOnClientRequest($codeClientRequest->getCodeContainer()->getBody());
    }
}