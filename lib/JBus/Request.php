<?php
namespace JBus;
use JBus\JSBuilder\ComponentCodeBuilder;
use JBus\WidgetLoader;
use JBus\Widget\Component;
use JBus\Widget\StateWidget;
use JBus\Widget\StateCache;

class Request
{    
    protected $rawBody;
    protected $rawBodyDecoded;
    
    protected $codeContainer;
    protected $code;
    protected $target=NULL;
    
    public function __construct($rawBody)
    {
        //$this->codeContainer=new CodeContainer();
        //$this->code=new CodeBuilder($this->codeContainer);
        $this->rawBody=$rawBody;
        $this->rawBodyDecoded=json_decode($this->rawBody,true);
        StateCache::getInstance()->populate($this);
    }
    
    public static function create()
    {       
        $body = isset($_POST['jbus']) ? $_POST['jbus'] : file_get_contents('php://input');
        return new self($body);
    }
    
    public function handleEvent()
    {
        if(NULL==$this->target)
        {
            throw new \Exception("Cannot find target in tree");
        }
        $type=$this->getTarget()['type'];
        $this->queryEvent($type, $this->getEvent());
    }
    
    public function restGetAllParams()
    {   
        return $this->rawBodyDecoded;
    }
    
    public function restGetParam($name,$default='')
    {
        $allParams=$this->restGetAllParams();
        return isset($allParams[$name])?$allParams[$name]:$default;
    }
    

    public function queryEvent($type,$event)
    {
        $loader=WidgetLoader::getInstance();
        list($widgetName,$parentWidgetName,$widgetProperty)=array_values($loader->parseWidgetType($type));
        $widgetNameClear=$widgetName;
        if(strlen($parentWidgetName))
        {
            $widgetNameClear=$parentWidgetName;
        }
        $eventInstance=$instanceRoot=$loader->createClass($widgetNameClear);
        if(strlen($widgetProperty))
        {   
            $widgetPropertyList=explode('>', $widgetProperty);
            do
            {
                 $property=array_shift($widgetPropertyList);
                 if($property)
                 {
                     $eventInstance=$eventInstance->{$property};
                 }
            }
            while($property);            
        }
        Component::setStageTarget(StateWidget::STAGE_UPDATE);
        $instanceRoot->getBuilder()->callOnUpdateTree();
        $eventInstance->getEventWidget()->callEventCallback($event);
        $instanceRoot->getBuilder()->build();
        return $this;
    }
    
    
    public function getTree()
    {
        return $this->rawBodyDecoded['tree'];
    }
    
    public function setTarget($targetState)
    {
        return $this->target=$targetState;
    }
    
    public function getTargetId()
    {
        return $this->rawBodyDecoded['target'];
    }    
    
    private function getEvent()
    {
        return $this->rawBodyDecoded['event'];
    }
    
    public function getTarget()
    {
        //$tree=$this->getTree();        
        //return $tree[0];
        return $this->target;
    }
    
}