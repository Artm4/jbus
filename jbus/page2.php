<?php
namespace jbus;
use JBus\Response;
use WidgetBase\Button;
use TestWidget\FormA;
use JBus\JSBuilder\CodeContainer;
use WidgetBase\DialogAbstract;

include 'boot.php';

$codeContainer=CodeContainer::createLocalInstance();
DialogAbstract::setRenderContainer($codeContainer);

$response=new Response();
$response->setTargetUrl('target.php');

$el=new FormA(true);
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
<link rel="stylesheet" type="text/css" href="../js/core/dojox/widget/Toaster/Toaster.css" >


<link rel="stylesheet" href="../js/core/dojox/layout/resources/FloatingPane.css" media="screen">
<link rel="stylesheet" href="../js/core/dojox/layout/resources/ResizeHandle.css" media="screen">
<style>
#dialog { min-width: 200px; }
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
    require(["dijit/registry", "dojo/parser","ext/Tool", "dijit/Dialog", "dijit/form/Button", "dojo/domReady!"], function(registry, parser,Tool){
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
        Tool.createMessageBoard();       
    });
</script>


</head>
<body class="claro">
<!-- and later in the page -->
<button onclick="showDialog();">View Terms and Conditions</button>

<div id="grid">
    <?
    echo $el;
    ?>
</div>

<div data-dojo-type="dijit/Dialog" style="width:600px;" data-dojo-props="title:'Terms and Conditions'" id="terms">
    
</div>

<script>
<?echo $response?>
</script>
<div>
<?php 
echo $codeContainer->getBody("\n");
?>
</div>
</body>
</html>