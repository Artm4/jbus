<?php
namespace WidgetBase;

use JBus\Widget\Component;
use JBus\Widget\State;
use JBus\JSBuilder\CodeContainer;

abstract class DialogAbstract extends Component
{       
    public $body;
    protected static $codeContainer;
    /**
     * @param string $isRoot
     * @param array $option
     */
    function __construct($param1=false,$param2=array())
    {   
        $this->readConstructArg($param1,$param2);
        if(isset($this->option['body']))
        {
            $this->body=$this->option['body'];
        }
        parent::__construct($param1,$param2);
    }
   
    public function getBody()
    {
        return $this->body;
    }
    
    public function show()
    {
        $this->state->set('show',true,true);
    }
    
    public function hide()
    {
        $this->state->set('hide',true,true);
    }
    
    public function setTitle($title)
    {
        $this->state->set('title',$title);
    }
    
    public function getTitle()
    {
        $this->state->get('title');
    }
    
    public function setWidth($value)
    {
        return parent::setWidth($value);
    }
    
    public function setHeight($value)
    {
        return parent::setHeight($value);
    }
    
    public function render()
    {
        $content=parent::render();
        if(NULL==self::$codeContainer)
        {
            return $content;   
        }        
        self::$codeContainer->addBlock($content);
        return '';
    }
    
    public static function setRenderContainer(CodeContainer $codeContainer)
    {
        self::$codeContainer=$codeContainer;
    }
}