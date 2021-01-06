<?php
namespace WidgetBase;

use JBus\JSBuilder\FunctionList;

class Textarea extends FormElement
{   
    protected $jsFunctionName=FunctionList::TEXT_AREA;
    protected $templatePath='textarea-template.php';    
}