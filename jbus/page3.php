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