<?php
namespace WidgetBase\Uploader;
use JBus\Widget\Component;
use JBus\JSBuilder\FunctionList;

class FileList extends Component
{
    protected $templatePath='fileList-template.php';
    protected $jsFunctionName=FunctionList::UPLOADER_FILE_LIST;
}