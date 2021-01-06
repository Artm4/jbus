<?php
namespace jbus;
use JBus\Response;
use WidgetBase\Button;

include 'boot.php';
$response=new Response();
$response->setTargetUrl('target.php');

$el=new \TestWidget\Button(true);
$el->setLabel('label1');

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
<script src="../js/core/dojo/dojo.js" data-dojo-config="isDebug: 1, async: 1, parseOnLoad: 1"></script>
<script src="../js/lib/ext/jbus.js"></script>



</head>
<body class="claro">
<!-- and later in the page -->
<div id="grid">
<?
echo $el;
?>
</div>

<script>
<?echo $response?>
</script>
</body>
</html>