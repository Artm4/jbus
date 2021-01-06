<?php
namespace JBus\JSBuilder;
use JBus\JSBuilder\FunctionList;

class CodeContainer
{
    private $require=array();    
    protected $blockList=array();
    protected $blockStore=true;
    public static $response=NULL;    
    private $local=false;
    
    const SEPARATOR_NEW_LINE="\n";
    const SEPARATOR_BLOCK=";\n";
    
    
    function __construct($option=array())
    {
        if(isset($option['local'])&&true===$option['local'])
        {
            $this->local=$option['local'];
        }
        
        if(NULL!=self::$response&&false===$this->local)
        {
            self::$response->addContainer($this);
        }
    }   
    
    public static function createLocalInstance()
    {
        $instance=new self(array('local'=>true));
        return $instance;
    }
    
    public function __toString()
    {
        return $this->getBody();
    }
    
    public function getBody($separator=self::SEPARATOR_BLOCK)
    {
        return implode($separator, $this->blockList).$separator;
    }
    
    public function addRequire($function)
    {
        if(!isset(FunctionList::$functSet[$function]))
        {
            throw new \Exception("Cannot find {$function}");
        }
        $this->require[$function]=FunctionList::$functSet[$function];
        if(NULL!=self::$response)
        {
            self::$response->addRequire($function,FunctionList::$functSet[$function]);
        }
    }
    
    public function getRequireIterator()
    {
        return $this->require;
    }
    
    public function getBlockIterator()
    {
        return $this->blockList;
    }
    
    public function addBlock($block)
    {
        if($this->blockStore)
        {
            $this->blockList[]=$block;
        }
        return $block;
    }
    
    public function stopBlockStore()
    {
        $this->blockStore=false;
    }
    
    public function restoreBlockStore()
    {
        $this->blockStore=true;
    }
}

