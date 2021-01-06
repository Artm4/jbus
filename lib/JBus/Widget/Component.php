<?php
namespace JBus\Widget;
use JBus\Widget\StateWidget;
use JBus\JSBuilder\ComponentBuilder;
use JBus\JSBuilder\FunctionList;
use JBus\JSBuilder\DomEvent;
use JBus\Response;

abstract class Component
{
    protected $templatePath='';
   
    protected $builder=NULL;
    private $type='';
    public static $stageTarget=StateWidget::STAGE_ALL;
    protected $state=NULL;
    protected $jsFunctionName=FunctionList::EMPTY_COMP;
    protected $eventWidget;    
    protected $isRoot=false;
    protected $option=array();
    
    protected function readConstructArg($param1=false,$param2=array())
    {
        if(is_bool($param1))
        {
            $this->isRoot=$param1;
        }
        if(is_array($param1))
        {
            $this->option=$param1;
        }
        else
        if(is_array($param2))
        {
            $this->option=$param2;
        }
    }
    
    /*
     * __construct($isRoot=false,$option=array())
     * __construct($option=array())
     */
    function __construct($param1=false,$param2=array())
    {   
        $this->readConstructArg($param1,$param2);
        $this->state=new StateWidget();        
        $this->state->setStage(StateWidget::STAGE_CREATE);
        $this->getBuilder()->generateWidgetId();
        $this->eventWidget=new EventWidget($this);
        
        if(StateWidget::STAGE_UPDATE==self::$stageTarget)
        {
            $this->onCreate();
        }
        else
        {
            $this->onCreate();
            $this->onInit();
        }
        $this->getBuilder()->loadPropertyList();
        //$this->getBuilder()->initCompositeWidget();
        if($this->isRoot)
        {            
            $this->getBuilder()->initCompositeWidget();
            $this->getBuilder()->prepareState($this);
        }       
    }
    
    public function isRoot()
    {
        return $this->isRoot;
    }
    
    protected function setTemplatePathRelative($templatePathRelative)
    {
        $this->templatePathRelative=$templatePathRelative;
    }
    
    public function getEventWidget()
    {
        return $this->eventWidget;
    }
    
    public function setEventWidget($eventWidget)
    {
        $this->eventWidget=$eventWidget;
        return $this;
    }
    
    public function getTemplatePath()
    {
        return $this->templatePath;
    }
   
    public function hasJsFunctionName()
    {
        return strlen($this->jsFunctionName)>0;
    }
    
    public function getJsFunctionName()
    {
        return $this->jsFunctionName;
    }
    
    public static function setStageTarget($target)
    {
        self::$stageTarget=$target;
    }
    
    public function getState()
    {
        return $this->state;
    }
   
    public function setType($type)
    {
        $this->type=$type;
    }
        
    public function getType()
    {
        return $this->type;
    }
    
    public function getBuilder()
    {
        if(NULL==$this->builder)
        {
            $this->builder=new ComponentBuilder($this);            
        }
        return $this->builder;
    }
   
    public function onInit(){}
    public function __toString()
    {
       return $this->render();
    }
    public function render()
    {
        try
        {
            $this->getBuilder()->build();
            $result=$this->getBuilder()->buildTemplate();
        }
        catch(Exception $e)
        {
            $result=$e->getMessage();
        }
        return $result;
    }
    public function readTemplate($templatePath)
    {
        ob_start();
        require $templatePath;
        $templateString=ob_get_clean();
        return $templateString;
    }
    public function registerOnClick($obj,$methodCallback)
    {
        $this->getEventWidget()->addEvent(ComponentBuilder::EVENT_CLICK, $obj,$methodCallback,DomEvent::CLICK);
    }
    public function registerOnKeyDown($obj,$methodCallback,$keyList)
    {
        if(!is_array($keyList))
        {
            throw new \Exception("registerOnKeyDown expects key list");
        }
        $this->getState()->set(ComponentBuilder::KEY_LIST,$keyList,true);
        $this->getEventWidget()->addEvent(ComponentBuilder::EVENT_KEYDOWN, $obj,$methodCallback,DomEvent::KEYDOWN);
    }
    public function showMessage($message)
    {
        $this->getState()->set(ComponentBuilder::TOOLTIP_MESSAGE,$message,true);
        return $this;
    }
    public function showError($message)
    {
        return $this->showMessage($message);
    }
    public function response()
    {
        return Response::getInstance();
    }    
    public function setStyle($prop,$value)
    {
        $this->state->set(ComponentBuilder::STYLE,array($prop,$value),true);
        return $this;
    }
    
    public function setClass($value)
    {
    	$this->state->set(ComponentBuilder::CLAS,$value,true);
    	return $this;
    }
    public function show()
    {
        $this->setStyle('display', '');
    }
    public function hide()
    {
        $this->setStyle('display', 'none');
    }
    public function getKeyCode()
    {
        return $this->state->get(ComponentBuilder::KEY_CODE);
    }
    public function onCreate(){}
    public function onUpdate(){}
    public function onParentUpdated(){}
    protected function setWidth($value)
    {
        $this->state->set('width',$this->getClearSize($value));
        return $this;
    }
    protected function setHeight($value)
    {
        $this->state->set('height',$this->getClearSize($value));
        return $this;
    }
    private function getClearSize($size)
    {
        if(is_numeric($size))
        {
            $size.='px';
        }
        return $size;
    }   
}