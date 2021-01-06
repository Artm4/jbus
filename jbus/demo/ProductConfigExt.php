<?php
class ProductConfigExt extends WidgetComposite
{
    protected $template='productConfig/template.php';
    
    function __construct()
    {
        $this->childCol->button=new \Widget\Button;
        $this->childCol->button->setLabel('label');
      
    }
    
    protected function onLoaded()
    {
        
    }
}