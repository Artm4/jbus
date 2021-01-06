<?php
namespace WidgetBase;
use JBus\Widget\Component;
use JBus\JSBuilder\FunctionList;
use WidgetBase\Uploader\FileList;
use WidgetBase\Uploader\Input;
use WidgetBase\Uploader\DropTarget;
use JBus\JSBuilder\UploaderBuilder;
use JBus\Upload\UploadFile;

class Uploader extends Component
{
    protected $templatePath='uploader-template.php';
    public $input;
    public $fileList;
    public $dropTarget;
    public static $urlDefault;
    
    protected $fileUpload;
    
    public function onCreate()
    {
        $this->input=new Input();
        $this->dropTarget=new DropTarget();
        $this->fileList=new FileList();
        $this->fileUpload=new UploadFile(array());
        $this->setUrl(self::$urlDefault);
    }
    
    public static function setUrlDefault($url)
    {
        self::$urlDefault=$url;
    }
    
    public function onUpdate()
    {
        //print_r($_FILES);
        $this->fileUpload=new UploadFile($_FILES);
    }
    
    public function registerOnUpload($obj,$methodCallback)
    {
        $this->input->getEventWidget()->addEvent(UploaderBuilder::EVENT_UPLOAD, $obj,$methodCallback);
    }
    
    public function getFileUpload()
    {
        return $this->fileUpload;
    }
    
    public function getBuilder()
    {
    	if(NULL==$this->builder)
    	{    
    		$this->builder=new UploaderBuilder($this);
    	}
    	return $this->builder;
    }
    
    public function setUrl($url)
    {
        $this->input->getState()->set('url', $url,true);
        return $this;
    }
    
    public function setMultiple()
    {
        $this->input->getState()->set('multiple', true,true);
        return $this;
    }
}