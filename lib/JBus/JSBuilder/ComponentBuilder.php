<?php
namespace JBus\JSBuilder;
use JBus\Widget\Component;
use JBus\WidgetLoader;
use JBus\Template\TemplateParser;
use JBus\Widget\StateWidget;
use JBus\Widget\StateCache;

class ComponentBuilder
{
    protected $code;
    protected $codeContainer;
    protected $reflection;
    protected $widget;    
    protected $widgetProperty='';
    protected $widgetType='';
    protected $widgetId='';
    protected $state;
    protected $rootWidget=NULL;
    protected $widgetPropertyList=array();
    private $built=false;
    private $parentWidget=NULL;
    
    const EVENT_CLICK='click';
    const EVENT_KEYDOWN='keydown';
    const TOOLTIP_MESSAGE='tooltipMessage';
    const STYLE='style';
    const CLAS='class';
    const KEY_LIST='keyList';
    const KEY_CODE='keyCode';
    
    function __construct(Component $widget)
    {
        $this->widget=$widget;
        $codeContainer=new CodeContainer();
        $this->code=new ComponentCodeBuilder($codeContainer,$widget);
    }
    
    public function setCode($code)
    {
        $this->code=$code;
    }
   
    public function setWidgetId($widgetId)
    {
        $this->widget->getState()->set(StateWidget::KEY_ID, $widgetId);
        return $this;
    }
    
    public function getWidgetId()
    {
        return $this->widget->getState()->get(StateWidget::KEY_ID);
    }
    
    private function generateId()
    {
        return uniqid();
    }
    
    public function setParentWidget(Component $widget)
    {
        $this->parentWidget=$widget;
    }
    
    public function getParentWidget()
    {
        return $this->parentWidget;
    }
    
    public function hasParentWidget()
    {
        return NULL!=$this->parentWidget;
    }
    
    public function getCodeContainer()
    {
        return $this->code->getCodeContainer();
    }
    
    public function getCodeBuilder()
    {
        return $this->code;
    }
    
    public function getWidgetType()
    {
        if(empty($this->widgetType))
        {   
            $rootWidget=$this->getRootWidget();
            $propertyPath=implode('>', $this->getWidgetPropertyList());
            $parentWidgetName=NULL!=$rootWidget?$rootWidget->getBuilder()->getWidgetName():'';            
            $this->widgetType=WidgetLoader::getInstance()->createWidgetType(                    
                    $this->getWidgetName(),                    
                    $parentWidgetName,
                    $propertyPath          
            );
        }
        return $this->widgetType;
    }
    
    public function initRootWidgetOption()
    {
        $currentWidgetBuilder=$this;
        $parentWidget=NULL;
        $propertyList=array();
        //$propertyList[]=$this->getWidgetProperty();
        while($currentWidgetBuilder->hasParentWidget())
        {
            array_unshift($propertyList, $currentWidgetBuilder->getWidgetProperty());
            $parentWidget=$currentWidgetBuilder->getParentWidget();
            $currentWidgetBuilder=$parentWidget->getBuilder();                        
        }
        $this->rootWidget=$parentWidget;
        $this->widgetPropertyList=$propertyList;
        return array('rootWidget'=>$parentWidget,'propertyList'=>$propertyList);
    }
    
    public function getRootWidget()
    {
        return $this->rootWidget;
    }
    
    public function getWidgetPropertyList()
    {
        return $this->widgetPropertyList;
    }
    
    public function getWidgetName()
    {
        return $this->getReflection()->getName();
    }
    
    public function getNamespace()
    {
        return $this->getReflection()->getNamespaceName();
    }    
    
    public function setWidgetProperty($widgetProperty)
    {
        $this->widgetProperty=$widgetProperty;
    }
    
    public function getWidgetProperty()
    {
        return $this->widgetProperty;
    }
    
    protected function onBuild()
    {
        if($this->state->isPropSet(self::EVENT_CLICK))
        {
            $this->code->compOnEvent(self::EVENT_CLICK);
        }
        if($this->state->isPropSet(self::EVENT_KEYDOWN))
        {
            $keyList=$this->state->get(self::KEY_LIST);
            $this->code->_callMethod($this->code->getJSFunction(FunctionList::TOOL), 'registerKeyEvent',$this->code->arguments(array($this->code->compGetObject(),$this->code->arr($keyList))));
        }
        if($this->state->isPropSet(self::TOOLTIP_MESSAGE))
        {   
            $this->code->_callMethod(
                $this->code->getJSFunction(FunctionList::TOOLTIP_JBUS),
                'show',
                $this->code->arguments(
                    array(
                        $this->code->valueString($this->state->get(self::TOOLTIP_MESSAGE)),
                        $this->code->prop($this->code->compGetObject(),'domNode')
                    )
                )
            );
        }
        if($this->state->isPropSet(self::STYLE))
        {
            $styleProp=$this->state->get(self::STYLE);
            $object=$this->code->compGetObject();
            $prop=$this->code->valueString($styleProp[0]);
            $value=$this->code->valueString($styleProp[1]);
            $this->code->_callMethod($this->code->getJSFunction(FunctionList::TOOL),'styleWidget',$this->code->arguments(array($object,$prop,$value)));
        }
        
        if($this->state->isPropSet(self::CLAS))
        {
        	$classProp=$this->state->get(self::CLAS);
        	$object=$this->code->compGetObject();        	
        	$value=$this->code->valueString($classProp);
        	$this->code->_callMethod($this->code->getJSFunction(FunctionList::TOOL),'addClass',$this->code->arguments(array($object,$value)));
        }
    }        
    
    protected function getReflection()
    {
        if($this->reflection==NULL)
        {
            $this->reflection=new \ReflectionClass($this->widget);
        }
        return $this->reflection;
    }
    
    protected function getWidget()
    {
        return $this->widget;
    }
    
    private $childPropertyList=array();
    public function generateWidgetId()
    {
        if(!strlen($this->getWidgetId()))
        {
            $this->setWidgetId($this->generateId());
        }
    }
   
    public function initCompositeWidget()
    {
        $widgetList=$this->getChildIterator();
        foreach($widgetList as $widgetPropertyName)
        {
            $childWidget=$this->getChildWidget($widgetPropertyName);
            if($childWidget->isRoot())
            {
                throw new \Exception("{$widgetPropertyName} is not Root Element");
            }
            $builder=$childWidget->getBuilder();            
            $builder->setParentWidget($this->widget);
            $builder->setWidgetProperty($widgetPropertyName);  
            $builder->initRootWidgetOption();
            $this->prepareState($childWidget);
            $builder->initCompositeWidget();
        }
    }
    
    public function prepareState($widget)
    {
        $builder=$widget->getBuilder();
        $builder->stateCreate();
        //if cache av change stage load
        $widget->getState()->setStage(StateWidget::STAGE_LOAD);
        StateCache::getInstance()->loadState($widget);
        $widget->getState()->setStage(StateWidget::STAGE_UPDATE);
    }
    
    public function build()
    {
        if($this->built)
        {
            return false;
        }
        $this->built=true;
        //$this->loadPropertyList();
        $this->prepareBuilder();        
        $this->buildCreate();
        $this->buildState();
        $this->onBuild();
        
        $this->buildChild();
        return true;
    }
    
    protected function buildCreate()
    {
        $targetState=$this->widget->getState()->getTargetState();
        if($targetState->isPropSet(StateWidget::KEY_CREATE)
            &$targetState->get(StateWidget::KEY_CREATE)
            )
        {
            if($this->widget->hasJsFunctionName())
            {
                $arguments='';
                if($this->hasTemplate())
                {                
                    $arguments=$this->code->arguments(array('{}',$this->code->valueString($this->getWidgetId())));
                }
                $this->code->compCreateObject($this->widget->getJsFunctionName(),$arguments);
            }
            else
            {
                $this->code->compSetObject('{}');
            }
            $this->code->compCreateState();           
        }        
        
        return true;       
    }
    
    public function stateCreate()
    {   
        $this->widget->getState()->set(StateWidget::KEY_CHILD_LIST,$this->getChildIdList());
        $this->widget->getState()->set(StateWidget::KEY_CREATE,1,true);
        $parentWidgetId=$this->hasParentWidget()?$this->getParentWidget()->getBuilder()->getWidgetId():0;
        $this->widget->getState()->set(StateWidget::KEY_PARENT_ID,$parentWidgetId);
        $rootWidgetId=NULL!=$this->getRootWidget()?$this->getRootWidget()->getBuilder()->getWidgetId():0;
        $this->widget->getState()->set(StateWidget::KEY_ROOT_ID,$rootWidgetId);
        $this->widget->getState()->set(StateWidget::KEY_TYPE,$this->widget->getBuilder()->getWidgetType());
    }
  
    protected function buildState()
    {
        $data=$this->widget->getState()->getTargetState()->getData();
        foreach($data as $key=>$value)
        {
            if(!$this->widget->getState()->isPropConstant($key))
            {
                $this->code->compStateSet($key,$this->code->valueByType($value));
            }
        }
    }
    
    protected function prepareBuilder()
    {
        $this->state=$this->widget->getState()->getTargetState();
        $this->code->prepare();
    }
    
    public function hasTemplate()
    {
        return strlen($this->widget->getTemplatePath())>0;
    }
    
    public function buildTemplate()
    {
        $templatePath='';
        if(!WidgetLoader::isPathAbsolute($this->widget->getTemplatePath()))
        {
            $fileName=$this->getReflection()->getFileName();
            $templatePath=dirname($fileName).DIRECTORY_SEPARATOR.$this->widget->getTemplatePath();
        }
        elseif(strlen($this->widget->getTemplatePath()))
        {
            $templatePath=$this->widget->getTemplatePath();
        }
        if(!file_exists($templatePath))
        {
            throw new \Exception("Cannot build Template {$templatePath}");
        }
        $templateString=$this->widget->readTemplate($templatePath); 
        $parser=new TemplateParser($templateString,$this->getWidgetId());
        
        return $parser->getDom()->ownerDocument->saveXML($parser->getDom()->ownerDocument->documentElement);
    }
    
    public function callOnUpdateTree()
    {        
        $widgetList=$this->getChildIterator();
        foreach($widgetList as $widgetPropertyName)
        {
            $childWidget=$this->getChildWidget($widgetPropertyName);
            $childWidget->getBuilder()->callOnUpdateTree();            
        }
        $this->widget->onUpdate();
        $this->notifyOnParentUpdated();
    }
    
    public function notifyOnParentUpdated()
    {
        $widgetList=$this->getChildIterator();
        foreach($widgetList as $widgetPropertyName)
        {
            $childWidget=$this->getChildWidget($widgetPropertyName);
            $childWidget->onParentUpdated();
        }
    }
    
    protected function buildChild()
    {
        $widgetList=$this->getChildIterator();
        foreach($widgetList as $widgetPropertyName)
        {
            $childWidget=$this->getChildWidget($widgetPropertyName);
            $builder=$childWidget->getBuilder();
    
            $builder->build();
        }
    }
    
    protected function getChildIterator()
    {
        return $this->childPropertyList;
    }
    
    private function getChildWidget($propertyName)
    {
        return $this->widget->{$propertyName};
    }
    
    public function loadPropertyList()
    {
        $refl=$this->getReflection();
        $properties=$refl->getProperties();        
        foreach($properties as $property)
        {
            $propertyName=$property->getName();
            if(!$property->isPublic())
            {
                continue;
            }
            if($property->isStatic())
            {
                continue;
            }
            $obj=$this->widget->{$propertyName};
            if(NULL==$obj)
            {
                continue;
            }
            if(($obj instanceof Component))
            {
                $this->childPropertyList[]=$propertyName;
            }
        }
    }
    
    private function getChildIdList()
    {
        $result=array();
        $widgetList=$this->getChildIterator();
        foreach($widgetList as $widgetPropertyName)
        {
            $childWidget=$this->getChildWidget($widgetPropertyName);
            $builder=$childWidget->getBuilder();            
            $result[]=$builder->getWidgetId();            
        }
        return $result;
    }
}