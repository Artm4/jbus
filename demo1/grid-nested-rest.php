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
			require([
				"dojo/_base/lang",
				"dgrid/List",
				"dgrid/OnDemandGrid",
				"dgrid/Selection",
				"dgrid/Editor",
				"dgrid/Keyboard",
				"dgrid/Tree",
				"dojo/_base/declare",
				"dstore/Rest",
				"dstore/Trackable",
				"dstore/Cache",
				"dstore/Tree",
				"dojo/domReady!"
			], function(lang, List, Grid, Selection, Editor, Keyboard, Tree, declare, Rest, Trackable, Cache, TreeStore){
					var CustomGrid = declare([Grid, Selection, Keyboard, Editor, Tree]);
					function createStore(config){
						var store = new declare([ Rest, Trackable, Cache, TreeStore ])(lang.mixin({
							target:"./rest/rest.php",
							put: function(object){
								return object;
							}
						}, config));
						store.getRootCollection = function () {
							return this.root.filter({ parent: undefined });
						};
						return store;
					}
					function getColumns(){
						return [
							{label:'Name', field:'name', sortable: false, renderExpando: true},
							{label:'Id', field:'id'},
							{label:'Comment', field:'comment', sortable: false, editor: "text"},
							{label:'Boolean', field:'boo', sortable: false, autoSave: true, editor: "checkbox"}
						];
					}
					window.grid = new CustomGrid({
						collection: createStore().getRootCollection(),
						sort: "id",
						getBeforePut: false,
						columns: getColumns()
					}, "grid");
					new CustomGrid({
						collection: createStore({useRangeHeaders: true}).getRootCollection(),
						sort: "id",
						getBeforePut: false,
						columns: getColumns()
					}, "gridRangeHeaders");
				});
		</script>
	</head>
	<body class="claro">
		<h2>A basic grid with Rest store</h2>
		<div id="grid"></div>

		<h2>A basic grid with Rest store using range headers</h2>
		<div id="gridRangeHeaders"></div>
	</body>
</html>