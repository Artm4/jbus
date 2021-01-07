# jbus
GUI framework for web application, based on components. Instead of traditional MVC flow, mediator is the component tree itself. 
The idea is to write only PHP server components that are translated to client components, similar to JWt.
Dojo framework is used for javascript frontend.

##Examples

_CompositeWidget.php_
```
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
```

_Php file page3.php that includes root widget and is served to the client_
```
<?php
namespace jbus;
use JBus\Response;
use WidgetBase\Button;
use TestWidget\CompositeWidget;

include 'boot.php';
$response=new Response();
$response->setTargetUrl('target.php');

$el=new CompositeWidget(true);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Demo: Page 1</title>
<link rel="stylesheet" href="../css/demo.css" media="screen">
<link rel="stylesheet" href="../js/core/dijit/themes/claro/claro.css" media="screen">
<link rel="stylesheet" href="../js/core/dgrid/css/dgrid.css" media="screen">
<link rel="stylesheet" href="../js/core/dgrid/css/skins/claro.css" media="screen">


<link rel="stylesheet" href="../js/core/dojox/layout/resources/FloatingPane.css" media="screen">
<link rel="stylesheet" href="../js/core/dojox/layout/resources/ResizeHandle.css" media="screen">
<style>
#dialog { min-width: 200px; }
.widget-pane { width:100%; height:100%; margin:0; padding:0;  }
html, body { height: 100%; height:100%;margin: 0; }
</style>
<script type="text/javascript">
dojoConfig = {
    baseUrl: "../js/",
    packages: [
    { name: "dojo", location: "core/dojo" },
    { name: "dijit", location: "core/dijit" },
    { name: "dojox", location: "core/dojox" },
    { name: "dstore", location: "core/dstore/" },
    { name: "dgrid", location: "core/dgrid/" },
    { name: "app", location: "lib/app" },
    { name: "ext", location: "lib/ext" }
    ]
};
</script>
<script src="../js/core/dojo/dojo.js.uncompressed.js" data-dojo-config="isDebug: 1, async: 1, parseOnLoad: 0"></script>
<script src="../js/lib/ext/jbus.js"></script>


<script>
    // Require the Dialog class
    require(["dijit/registry", "dojo/parser", "dijit/Dialog", "dijit/form/Button", "dojo/domReady!"], function(registry, parser){
        // Show the dialog
        showDialog = function() {
            registry.byId("terms").show();
            parseOnLoad: false;
        }
        // Hide the dialog
        hideDialog = function() {
            registry.byId("terms").hide();
        }
        parser.parse();
    });
</script>


</head>
<body class="claro">


    <?
    echo $el;
    ?>



<script>
<?echo $response?>
</script>
</body>
</html>
```


_Php sub request handlers (target.php), serves widget requests_
```
<?php
namespace JBus;
use JBus\Widget\StateCache;

header('Content-Type: application/json');
include 'boot.php';
$request=Request::create();
//print_r($request->getTree());
$response=new Response();
$params=$request->restGetAllParams();
//print_r($params);
$request->handleEvent();
//print_r($response->createAjaxResponse());
echo json_encode($response->createAjaxResponse());
```