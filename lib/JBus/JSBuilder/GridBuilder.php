<?php
namespace JBus\JSBuilder;
use JBus\JSBuilder\ComponentBuilder;
use JBus\Widget\StateWidget;
use WidgetBase\Autocomplete;
use WidgetBase\Grid\GridColumn;

class GridBuilder extends ComponentBuilder
{    
    const EVENT_QUERY='query';
    public $columnEventCounter=0;
    public function onBuild()
    {  
        parent::onBuild();
        if(isset($this->state->create))
        {
            $this->defineOnClientRequest();
            $store=$this->code->newInstance(FunctionList::STORE,$this->code->object(array('id'=>$this->getWidgetId())));
            $this->code->compSet($this->code->arguments(array($this->code->valueString('collection'),$store)));
            $this->code->compCallMethod('startup');
        }
        if(isset($this->state->columns))
        {
            $columnsClear=array();
            foreach($this->state->columns as $gridColumn)
            {   
                $key=$gridColumn->getColumnKey();                
                $columnsClear[$key]=$this->defineCell($gridColumn);
            }
            $this->code->compSet($this->code->arguments(array($this->code->valueString('columns'),$this->code->object($columnsClear,false))));
        }
        if(isset($this->state->primaryName))
        {  
            $this->code->_callMethod($this->code->compGet($this->code->valueString('collection')),
                    'setIdentity',
                    $this->code->arguments(array($this->code->valueString($this->state->primaryName)))
                    );
        }
        if(isset($this->state->data))
        {   
            $arrBody=array();
            foreach($this->state->data as $obj)
            {
                $arrBody[]=$this->code->object($obj);                
            }
            $this->code->_callMethod($this->code->compGet($this->code->valueString('collection')),
                    'jbSetData',
            $this->code->object(array(
                'items'=>$this->code->arr($arrBody,false),
                'total'=>$this->state->total,
            ),false)        
            );            
        }
        
        if(isset($this->state->removeId))
        {            
            $this->code->_callMethod($this->code->compGet($this->code->valueString('collection')),
                    'remove',$this->code->valueInt($this->state->removeId)
            );
        }
        if(isset($this->state->putItem))
        {
            $this->code->_callMethod($this->code->compGet($this->code->valueString('collection')),
                    'put',$this->code->object($this->state->putItem)
                    );
        }
        if(isset($this->state->refresh))
        {
            $this->code->compCallMethod('refresh');
        }
        if(isset($this->state->rowsPerPage))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('rowsPerPage'),$this->code->valueInt($this->state->rowsPerPage))));
        }
    }
    
    protected function defineOnClientRequest()
    {
        $codeClientRequest=$this->code->createLocal();       
        
        $param=$codeClientRequest->_callMethodResult($codeClientRequest->compGet($this->code->valueString('collection')),
                'getProp',$codeClientRequest->valueString('jbQueryParams')
        );        
        $codeClientRequest->compStateSet('jbQueryParams', $param);
        $this->code->compDefineOnClientRequest($codeClientRequest->getCodeContainer()->getBody());
    }
    
    /*
     * Supports only buttons. Should be refactored as internal component builder.
     */
    protected function defineCell(GridColumn $gridColumn)
    {
        $code=$this->code->createLocal();
        $cellDefinition=array();
        $cellDefinition['label']=$code->valueString($gridColumn->getLabel());
        if($gridColumn->hasWidget())
        {
            $renderCellBody='';
            foreach($gridColumn->getWidgetList() as $widget)
            {                  
                $renderCellBody.=$this->defineCellComponent($widget);
                $renderCellBody.=$code->separator();
            }
            $cellDefinition['renderCell']=$code->valueFunction(
                $code->arguments(array('object', 'value', 'node')),
                $renderCellBody
            );
        }
        return $code->object($cellDefinition,false);
    }
    
    protected function defineCellComponent($widget)
    {
        $code=$this->code->createLocal();
        $object='widget';
        $code->setVar($object, $code->newInstance($widget->getJsFunctionName(),'{}'));
        if($widget->getState()->isPropSet('label'))
        {
            $code->_callMethod($object, 'set',
                    $this->code->arguments(array($code->valueString('label'),$code->valueString($widget->getState()->get('label'))))
                    );
        }  
        if($widget->getState()->isPropSet('value'))
        {
            $code->_callMethod($object, 'set',
                    $this->code->arguments(array($code->valueString('value'),$code->valueCode('value')))
                    );
        }
        if($widget->getState()->isPropSet('width'))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('width'),$this->code->valueString($widget->getState()->get('width')))));
        }
        if($widget->getState()->isPropSet('height'))
        {
            $this->code->compSet($this->code->arguments(array($this->code->valueString('height'),$this->code->valueString($widget->getState()->get('height')))));
        }
        foreach($widget->getEventWidget()->getEventList() as $eventName=>$eventCallback)
        {
            $domEvent=$widget->getState()->get($eventName);        
            
            $code->getCodeContainer()->stopBlockStore();
            $eventMethod=$code->compStateSet('rowObject', 'object');
            $code->getCodeContainer()->restoreBlockStore();
            $eventMethod.=$code->separator();
            $eventMethod.=$code->defineEventMethod($eventName);
            $eventMethod.=$code->separator();
            
                          
            $code->_callMethod($object,'on',$code->arguments(
                    array(
                            $code->valueString($domEvent),
                            $code->valueFunction('', $eventMethod),
                    )
            ));
            
        }
        
        $code->_callMethod($object,'placeAt',$code->arguments(array(
                'node',$code->valueString('last')
             )
        ));
        return $code->getCodeContainer()->getBody();
    }
}