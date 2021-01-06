<?php
namespace TestWidget;
use JBus\Widget\Component;
use TestWidget\InnerWidget;
use WidgetBase\Button;
use WidgetBase\Text;
use WidgetBase\Textarea;
use WidgetBase\Autocomplete;
use WidgetBase\Grid;
use JBus\Validate\ValidateRegex;
use JBus\Validate\Validate;
use JBus\Validate\ValidateRequired;
use JBus\Validate\ValidateRange;
use WidgetBase\Label;
use WidgetBase\Image;
use WidgetBase\Dialog;
use WidgetBase\Window;
use WidgetBase\Uploader;
use JBus\Widget\KeyList;
use JBus\JSBuilder\FunctionList;
use JBus\Upload\UploadFile;

class FormA extends Component 
{
    protected $jsFunctionName=FunctionList::COMPONENT;
    
    protected $templatePath='formA-template.php';
    protected $innerWidget;
    public $search;
    public $company;
    public $productName;
    public $description;
    public $sku;
    public $grid;    
    public $gridB;
    public $compositeWidget;
    public $label;
    public $image;
    public $dialog;
    public $openDialog;
    public $uploader;
    
    public function onCreate()
    {       
        Uploader::setUrlDefault('target.php');
        $this->uploader=new Uploader();
        $this->uploader->setMultiple();
        $this->uploader->registerOnUpload($this, 'onUpload');
        $this->search=new Button();
        $this->search->setLabel('Some Label'); 
        $this->search->registerOnClick($this, 'onClick'); 
        
        $this->openDialog=new Button();
        $this->openDialog->setLabel('Open dialog');
        $this->openDialog->registerOnClick($this, 'onClickOpenDialog');
        
        $this->productName=new Text();
        $this->productName->registerOnChange($this, 'onChange');        

        $this->productName->registerOnKeyDown($this, 'onKeyDownLabel', array(KeyList::ENTER,KeyList::ESC));
        
        $validatorInteger=new ValidateRegex('Product Name 11','\d+');
        $validatorInteger->setMessage('Value not valide 2');
        
        $validatorRequired=new ValidateRequired();
        $this->productName->addValidator($validatorRequired);
        
        $this->company=new Text();
        $validatorRequire=new ValidateRegex('Product Name','\d+');
        $validatorRequire->setMessage('Value not valide 1');
        $validatorInteger=new ValidateRegex('Product Name 1','\d+');
        $validatorInteger->setMessage('Value not valide 2');
        $validatorRange=new ValidateRange('Company',1,100);
        $this->company->addValidator($validatorRequire);
        $this->company->addValidator($validatorInteger);
        $this->company->addValidator($validatorRange);
    
        
        $this->description=new Textarea();
        $this->description->registerOnClick($this, 'onChangeTextArea');        
        
        $this->sku=new Autocomplete();
                
        $this->sku->registerOnChange($this, 'onChangeSku');
        $this->sku->registerOnSearch($this, 'onSearchSku');
        
        /*$this->sku->setData(array(
                '45'=>'45',
                '5'=>'5',
                '6'=>'6',
        ));
        */
        
        $gridButton=new Button();
        $gridButton->setLabel('Edit');
        $gridButton->registerOnClick($this, 'onClickGridButton');
        
        $gridButtonDelete=new Button();
        $gridButtonDelete->setLabel('Delete');
        $gridButtonDelete->registerOnClick($this, 'onDeleteGridButton');
        
        $url='http://dev.widewebdata.com/tms/www/images/header.gif';
        $imageGrid=new Image();
        $imageGrid->setWidth(150);        
        $imageGrid->registerOnClick($this, 'onClickGridImage');
        
        $this->grid=new Grid();
        $this->grid->setRowsPerPage(3);
        $this->grid->registerOnQuery($this,'onQueryGrid');
        $this->grid->setColumns(array(
                'id'=>'Id',
                'name'=>'Name',
                'img'=>array('Image',array($imageGrid)),
                'action'=>array('Action',array($gridButton,$gridButtonDelete))
        ));
        $this->grid->setPrimaryName('id');
        
        
        $this->gridB=new Grid();
        $this->gridB->registerOnQuery($this,'onQueryGridB');
        $this->gridB->setColumns(array(
                'id'=>'Id',
                'name'=>'Name'               
        ));
        $this->gridB->setPrimaryName('id');
        //$gridButton=new Button();
        //$this->grid->setColumnComponent('action',$gridButton);
        
        $this->compositeWidget=new CompositeWidget();
        $this->compositeWidget->setForm($this);
        
        $this->label=new Label();
        $this->label->setValue('Some text');
        $this->label->setBackgroundColor('blue');
        $this->label->setTextColor('red');
        
        $url='http://dev.widewebdata.com/tms/www/images/header.gif';
        $this->image=new Image();
        $this->image->setWidth(150);
        $this->image->setUrl($url);
        
        $this->image->registerOnClick($this, 'onClickImage');
        

        $buttonDialog=new Button();
        $buttonDialog->setLabel('Dialog button');
        $buttonDialog->registerOnClick($this, 'closeDialog');
        
        $compositeWidget=new CompositeWidget();
               
        $this->dialog=new Window(array('body'=>$compositeWidget));    
        $this->dialog->setWidth('50%');
        $this->dialog->setHeight('50%');
        $this->dialog->hide();
        //$this->registerOnKeyDown($this, 'onKeyDown', array(KeyList::ENTER,KeyList::ESC));
    }
    
    public function onUpdate()
    {
        
    }
    
    public function onUpload()
    {       
        $uploadFile=$this->uploader->getFileUpload();        
        
        $uploadPath=ROOT_PATH.'/etc/uploads';
        $result=array();
        $uploadFile->move($uploadPath);
        $msg='';
        foreach($uploadFile->getUploadResult() as $uploadResult)
        {
            if($uploadResult->isSuccessfull())
            {
                $msg.=sprintf("%s uploaded",$uploadResult->getFile()->getOriginalName());
            }
            else
            {
                $msg.=sprintf("%s failed",$uploadResult->getFile()->getOriginalName());
            }            
        }
        $this->uploader->showMessage($msg);
    }
    
    public function onKeyDown()
    {
        if(KeyList::ENTER==$this->getKeyCode())
        {
            $this->description->showMessage('Enter down');
        }
        elseif(KeyList::ESC==$this->getKeyCode())
        {
            $this->description->showMessage('Esc down');
        }
    }
    
    public function onKeyDownLabel()
    {
        if(KeyList::ENTER==$this->productName->getKeyCode())
        {
            $this->company->setValue('Enter down Label');
        }
        elseif(KeyList::ESC==$this->productName->getKeyCode())
        {
            $this->company->setValue('Esc down Label');
        }
    }
    
    public function closeDialog()
    {
        $this->dialog->hide();
    }
    
    public function onClickOpenDialog()
    {
        $this->dialog->show();
    }
    
    public function onClickGridImage()
    {
        $this->description->showMessage('Image Clicked');
    }
    
    public function onClickImage()
    {
        $this->image->showMessage('Image clicked');
    }
    
    public function onClickGridButton()
    {
        $this->description->showMessage('Button Clicked');
        //print_r($this->grid->getRowObject());
    }
    
    public function onDeleteGridButton()
    {
        $this->description->showMessage('Button Clicked Delete');
        //print_r($this->grid->getRowObject());
    }

    public function onClick()
    {
        //$this->description->setValue($this->productName->getValue());
        // $this->grid->refresh();
         //$this->productName->showMessage("Some message");
         //$this->description->showMessage("Some Desc");
        //$this->grid->remove(2);
        //$this->grid->put(array('id'=>'1','name'=>'new Name'));
        //$validate=new Validate();
        //$validate->isValid(array($this->company,$this->productName));
        //$this->description->showMessage($validate->getResultMessage('<br/>'));
        //$this->sku->setValue(45);
        /*$this->sku->setItem(45,45);
        $this->description->setValue("some value \n 23332 \n wefsf");
        $this->company->setValue($this->label->getBackgroundColor());
        $this->label->setValue('New Some text');
        */
        //$this->response()->gotoUrl('/');
        $this->label->show();
    }
    
    public function onChange()
    {        
        //$this->search->setLabel('New Label2');
    }
    
    public function onChangeTextArea()
    {
        $this->label->hide();
        //$this->productName->setValue('New Label');
    }
    
    public function onChangeSku()
    {  
        //$this->productName->setValue($this->sku->getValue());
    }
    
    public function onSearchSku()
    {
        $this->sku->setData(array(
                '45'=>'45',
                '5'=>'5',
                '6'=>'6',
        ));
        //$this->productName->setValue('Searching Sku '.$this->sku->getSearchText());
    }
    
    public function onQueryGrid()
    {
        $url='http://dev.widewebdata.com/tms/www/images/header.gif';
        $this->productName->setValue($this->productName->getValue()."my value");        
        $this->grid->setData(array(
            array('id'=>'11','name'=>'name1','img'=>$url),
            array('id'=>'2','name'=>'name2','img'=>$url),
            array('id'=>'3','name'=>'name3','img'=>$url),
                array('id'=>'12','name'=>'name1','img'=>$url),
                array('id'=>'13','name'=>'name2','img'=>$url),
                array('id'=>'14','name'=>'name3','img'=>$url),
                array('id'=>'15','name'=>'name1','img'=>$url),
                array('id'=>'8','name'=>'name2','img'=>$url),
                array('id'=>'9','name'=>'name3','img'=>$url),
                
        ),50+rand(1,50));
        
    }
    
    public function onQueryGridB()
    {
        $this->productName->setValue($this->productName->getValue()."my value");
        $this->gridB->setData(array(
                array('id'=>'1','name'=>'name11'),
                array('id'=>'2','name'=>'name22'),
                array('id'=>'3','name'=>'name33')
    
        ),100);
        
    }
}