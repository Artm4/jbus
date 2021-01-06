<?php
class ProductConfig extends WidgetComposite
{
    protected $template='productConfig/template.php';
    protected $modelProp=array(
        'name'=>'',
        'refId'=>0,        
    );
    
    function __construct()
    {
        
    }
    
    /*
     * called on create/wake up
     */
    public function onCreate()
    {
        $this->button=new \Widget\Button(array('bind'=>$this->model->refId));
        $this->button->setLabel('Some Label');
        $this->grid=new \Widget\Grid;
        $this->selector=new ProductPrototypes();
        $this->childCol->grid->setDataSelector($this->selector);
        $this->autocompleteConfigs=new \Widget\AutoComplete;
        $this->grid->setDataSelector($this->selector);
        $this->productConfigExt=new \App\Widget\ProductConfigExt;
        
        $this->button->setOnClick(array($this,'onClick'));
        
        $this->button->placeAt($this,'somePlace');
        $this->productConfigExt->placeIn($this,'productConfigExt');
               
        $this->model=new \Widget\Model(
            $this->modelProp
        );
    }
    /*
     * called on create
     */
    public function onInit()
    {
        
    }
    
    public  function onLoaded()
    {
        
    }
    
    public function onClick()
    {
        $item=Item::createInstance();
        $item->setName($this->model->name);
        $item->save();
        $this->button->setLabel('Some Label');
        $this->grid->disable();
        $newData=array();
        $this->grid->setData($newData);
        $this->childCol->button->setLabel('Save');
    }
}