<?php
namespace TestWidget;
use JBus\Widget\Component;
use TestWidget\InnerWidget;
use WidgetBase\Button;
use WidgetBase\Text;
use WidgetBase\Grid;
use WidgetBase\Image;
use JBus\JSBuilder\FunctionList;

class CompositeWidget extends Component 
{   
    public $templatePath='compositeWidget-template.php';
    public $innerWidget;
    public $button;
    public $productName;
    public $gridD;
    
    private $form;
    
    public function onCreate()
    {
        $this->innerWidget=new InnerWidget();
        
        $this->button=new Button();
        $this->button->setLabel('Inner Button');
        $this->button->registerOnClick($this, 'onButtonClick');
        $this->productName=new Text();
        
        $url='http://dev.widewebdata.com/tms/www/images/header.gif';
        $imageGrid=new Image();
        $imageGrid->setWidth(150);
        $imageGrid->registerOnClick($this, 'onClickGridImage');
        
        $this->gridD=new Grid();
        $this->gridD->registerOnQuery($this,'onQueryGridD');
        $this->gridD->setColumns(array(
                'id'=>'Id',
                'name'=>'Name',
                'img'=>array('Image',array($imageGrid))
        ));
        
    }
    
    public function onButtonClick()
    {
        $this->productName->setValue($this->productName->getValue().'+Clicked');
        if($this->form)
        {
            $parentName=$this->form->productName;
            $parentName->setValue($parentName->getValue()."+ClickedP");
        }        
    }
    
    public function onUpdate()
    {
        $this->button->setLabel('Inner Button from onUpdate');
    }
    
    public function setForm($form)
    {
        $this->form=$form;
    }
    
    public function onClickGridImage()
    {
        $this->productName->showMessage('Image Clicked');
    }
    

    public function onQueryGridD()
    {
        $url='http://dev.widewebdata.com/tms/www/images/header.gif';
        $this->productName->setValue($this->productName->getValue()."my value");
        $this->gridD->setData(array(
                array('id'=>'1','name'=>'name1','img'=>$url),
                array('id'=>'2','name'=>'name2','img'=>$url),
                array('id'=>'3','name'=>'name3','img'=>$url)
    
        ),100);
    }
    
}