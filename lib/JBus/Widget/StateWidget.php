<?php
namespace JBus\Widget;
use JBus\State as State;

class StateWidget extends State
{  
    public $state=NULL;
    public $stateSwap=NULL;
    
    public $stateList=array();
    
    protected $stage=NULL;
    protected $stageHistory=array();
    
    protected $constantKeyList=array();
   
    const STAGE_ALL=0;
    const STAGE_CREATE=1;
    const STAGE_LOAD=2;
    const STAGE_UPDATE=3;
    
    const MODEL_PREFIX='wpr_';
        
    const KEY_CHILD_LIST='chList';
    const KEY_CREATE='create';
    const KEY_ID='id';
    const KEY_TYPE='type';
    const KEY_PARENT_ID='pId';
    const KEY_ROOT_ID='rId';
    
    
    function __construct()
    {        
        $this->stateSwap=$this->stateList[self::STAGE_CREATE]=new State();
        $this->stateList[self::STAGE_LOAD]=new State();
        $this->stateList[self::STAGE_UPDATE]=new State();        
        $this->state=new State();
    }
    
    public function getStage()
    {
        return $this->stage;
    }
    
    public function isTargetStageAll()
    {
        return self::STAGE_ALL==Component::$stageTarget;
    }
    
    public function setStage($stage)
    {
        $this->stage=$stage;
        $this->stageHistory[]=$stage;
        $this->stateSwap=$this->stateList[$stage];
    }
    
    public function getStateSwap()
    {
        return $this->stateSwap;
    }
    
    public function getTargetState()
    {
        if(self::isTargetStageAll())
        {
            return $this->state;
        }
        return $this->getStateByStage(Component::$stageTarget);
    }
    
    public function getStateByStage($stage)
    {
        return $this->stateList[$stage];
    }
    
    function __set($key,$value)
    {
        $this->stateSwap->set($key,$value);
        $this->state->set($key,$value);
    }
    
    function __get($key)
    {
        return $this->state->get($key);
    }
    
    public function __isset($key)
    {
        return ($this->state->isPropSet($key));
    }
    
    public function getData()
    {
        return $this->state->getData();
    }
    
    public function get($key,$default=NULL)
    {
        return $this->state->get($key,$default);
    }
    
    public function set($key,$value,$isConstant=false)
    {
        $this->stateSwap->set($key,$value);
        $this->state->set($key,$value);
        if($isConstant)
        {
            $this->setPropConstant($key);
        }
    }
    
    public function modelSet($key,$value)
    {
        return $this->set($this->getModelKey($key),$value);
    }
    
    public function modelGet($key,$default=NULL)
    {
        return $this->get($this->getModelKey($key),$default);
    }
    
    public function modelIsPropSet($key)
    {
        return $this->isPropSet($this->getModelKey($key));
    }
    
    protected function getModelKey($key)
    {
        return self::MODEL_PREFIX.$key;
    }
    
    public function isPropSet($key)
    {
        return $this->state->isPropSet($key);
    }
    
    public function setFromArray($arr)
    {
        $this->stateSwap->setFromArray($arr);
        $this->state->setFromArray($arr);
    }   
    
    public function encode()
    {
        return json_encode($this->state->getData());
    }
    
    public function decode()
    {
        return json_decode($this->state->getData(),true);
    }
    
    public function setPropConstant($key)
    {
        $this->constantKeyList[$key]=1;
    }
    
    public function isPropConstant($key)
    {
        return isset($this->constantKeyList[$key]);
    }
    
    
    //public function
}
