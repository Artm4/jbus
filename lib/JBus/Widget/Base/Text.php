<?php
namespace WidgetBase;

use JBus\JSBuilder\FunctionList;

class Text extends FormElement
{   
    protected $jsFunctionName=FunctionList::TEXT;
    protected $templatePath='text-template.php';    
}