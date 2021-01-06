<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <title>Demo: Title box</title>
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
                    { name: "app", location: "lib/app" }
                ]               
            };        
        </script>
        <script src="../js/core/dojo/dojo.js" data-dojo-config="isDebug: 1, async: 1, parseOnLoad: 1"></script>
        
        <script>
<?php
$text='123\n';
?>
        require([
                 'dojo/_base/declare',
                 'dstore/RequestMemory',
                 'dgrid/Grid',
                 'dgrid/extensions/Pagination',
                 "dijit/form/Textarea"
             ], function (declare, RequestMemory, Grid, Pagination,Textarea) {
                 var text=new Textarea({},'textarea');
                 eval("text.set('value','<?=$text?>')");
             });
</script>

        
    </head>
<body class="claro">
<!-- and later in the page -->
<div id="textarea"></div>
</body>
</html>
