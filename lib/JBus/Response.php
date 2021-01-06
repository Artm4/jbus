<?php
namespace JBus;
use JBus\JSBuilder\CodeContainer;
use JBus\JSBuilder\CodeBuilder;

class Response
{    
    const TAG_JS_CODE='jsCode';
    public static $_instance=null;
    
    public static function getInstance()
    {
        if(!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
        
    protected $errors=array();
    protected $messages=array();
    protected $body=array();
    protected $targetUrl;
    
    protected $containerList;
    public $require=array();
    
    protected $code;
    protected $bufferTag;
    
    public function __construct()
    {
        self::$_instance=CodeContainer::$response=$this;
        $this->codeContainer=new CodeContainer();
        $this->code=new CodeBuilder($this->codeContainer);
        $this->bufferTag=new BufferTag();
    }
    
    public function addRequire($function,$path)
    {   
        $this->require[$function]=$path;
    }
    
    public function addContainer($container)
    {
        $this->containerList[]=$container;
    }
       
    private function buildHttpResponse()
    {
        $this->bufferTag->start();
        ?>
var call=function(){
    var codeContainer=new jbus.CodeContainer();
    codeContainer.setRequireList(<?echo $this->code->object($this->require);?>);
    codeContainer.setCodeCallback(
            function(){
            	jbus.CodeExecutor.getInstance().enablePostExecute();
                <?php 
                for($i=count($this->containerList)-1;$i>=0;$i--)
                {
                    echo $this->containerList[$i]->getBody();
                }
                echo ";\n";
                ?>
                jbus.CodeExecutor.getInstance().executePostList();
                jbus.CodeExecutor.getInstance().disablePostExecute();
            }
        );
    var codeExecutor=jbus.CodeExecutor.getInstance();
    codeExecutor.execute(codeContainer);
}();
        <?
        return $this->bufferTag->end();
    }
    
    public function __toString()
    {
        return $this->buildHttpResponse();
    }
    
    public function setTargetUrl($url)
    {
        $this->targetUrl=$url;
        $block=sprintf("%s(%s)",'jbus.config.setTargetUrl',$this->code->valueString($url));
        $this->codeContainer->addBlock($block);
    }
    
    public function addMessage($msg)
    {
        $this->messages[]=$msg;
        return $this;
    }
    
    public function addError($error,$errno=0)
    {
        $this->errors[]=array('errno'=>$errno,'error'=>$error);
        return $this;
    }
    
    public function addErrors($errors,$errno=0)
    {
        foreach($errors as $error)
        {
            $this->addError($error,$errno);
        }
        return $this;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    public function getMessages()
    {
        return $this->messages;
    }
    
    public function getBody()
    {
        return $this->body;
    }
     
    public function setBody($body)
    {
        $this->body=$body;
        return $this;
    }
    
    public function createAjaxResponse()
    {
        $o=new \stdclass();
        $o->body=$this->getBody();
        $o->errors=$this->getErrors();
        $o->messages=$this->getMessages();
        $o->requireList=$this->require;
        $o->code='';
        foreach ($this->containerList as $container)
        {
            $o->code.=$container->getBody();
            $o->code.="\n";
        }
        return $o;
    }
    
    public function gotoUrl($url)
    {
        $this->code->_callMethod('jbus.tool', 'redirect', $this->code->valueString($url));
    }
    public function gotoUrlNewTab($url)
    {
    	$this->code->_callMethod('jbus.tool', 'newTab', $this->code->valueString($url));
    }
}
