<?php 

$app=new App();
class App
{
    protected $requireQueue;
    protected $body;
    function __construct()
    {
        $this->requireQueue=new RequireQueue();
    }
    
    public function getRequireQueue()
    {
        return $this->requireQueue;
    }
    
    public function getBody()
    {
        return $this->body;
    }
    
    public function setBody($body)
    {
        return $this->body=$body;
    }
}

class RequireQueue
{
    private $require=array();
    public function add($function)
    {
        if(!isset(JSFunctionList::$functSet[$function]))
        {
            throw new Exception("Cannot find {$function}");
        }
        $this->require[$function]=JSFunctionList::$functSet[$function];
    }
    public function getIterator()
    {
        return $this->require;
    }
}



class JSFunctionList
{
    const AT='at';
    const DOM='dom';
    const STATEFUL='Stateful';
    const FILTERING_SELECT='FilteringSelect';
    const MEMORY='Memory';
    const READY='ready';
    public static $functSet=array( 
        self::AT=>'dojox/mvc/at',  
        self::DOM=>'dojo/dom',
        self::STATEFUL=>"dojo/Stateful",
        self::FILTERING_SELECT=>'dijit/form/FilteringSelect',
        self::MEMORY=>"dojo/store/Memory",
        self::READY=>"dojo/domReady!",
    );
}

function getObject($id)
{
    return "ObjectPool.getInstance().get('{$id}')";
}

function setObject($id,$functionName)
{   
     $function=getJSFunction($functionName);     
     //$code="(function(){var obj=new {$function}({});\n";
     //$code.="ObjectPool.getInstance().set('{$id}',obj)}())";
     $code="ObjectPool.getInstance().set('{$id}',new {$function}({}))";
     return $code;
}

function getJSFunction($functionName)
{
    global $app;
    $app->getRequireQueue()->add($functionName);
    return $functionName;
}


function callMethod($id,$method,$argString='')
{
    return sprintf("%s.%s(%s)",getObject($id),$method,$argString);
}

function callFunction($functionName,$argString='')
{
    return sprintf("%s(%s)",$functionName,$argString);
}

function buildComponent()
{    
    global $app;
    ?>
    require([
    <?php 
        echo "'".implode("','",$app->getRequireQueue()->getIterator())."'"
    ?>
    ], function( <?php 
        echo implode(",",array_keys($app->getRequireQueue()->getIterator()))
    ?>){
        <?php 
        echo $app->getBody();
        ?>
    
    });
    <?
}
?>
<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <title>Demo: Title box</title>
        <link rel="stylesheet" href="../../css/demo.css" media="screen">
        <link rel="stylesheet" href="../../js/core/dijit/themes/claro/claro.css" media="screen">
        <link rel="stylesheet" href="../../js/core/dgrid/css/dgrid.css" media="screen">
        <link rel="stylesheet" href="../../js/core/dgrid/css/skins/claro.css" media="screen">
        <style>
            #dialog { min-width: 200px; }
        </style>
        <script type="text/javascript">
        dojoConfig = {
                baseUrl: "../../js/",
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
        <script src="../../js/core/dojo/dojo.js.uncompressed.js" data-dojo-config="isDebug: 1, async: 1, parseOnLoad: 1"></script>
        
        <script>
        
        var ObjectPool=function()
        {
			this.hash={}	
        }
        ObjectPool.prototype.set=function(id,obj)
        {
			this.hash[id]=obj;
        }
        ObjectPool.prototype.get=function(id)
        {
			return this.hash[id];
        }
        ObjectPool.instance=null;
        ObjectPool.getInstance=function()
        {
			if(null==ObjectPool.instance)
			{
			    ObjectPool.instance=new ObjectPool();
			}
			return ObjectPool.instance;
        }
        <?php 
            
            ob_start();
            ?>
            
			<?
			echo setObject('state123', JSFunctionList::STATEFUL);			
			?>
			
			console.log(<?echo getObject('state123')?>);			
			<?php 
			echo callMethod('state123','set',"'name',1");
			?>
			
			console.log(<?echo callMethod('state123','get',"'name'")?>);
			<?
			echo setObject('mem1', JSFunctionList::MEMORY);
			echo "\n";
			$string=<<<EOT
			[{id: 1, name:"we", label:"<i>we</i> <img src='http://placekitten.com/50/70' />"},
                     {id: 2, name:"are", label:"<u>are</u> <img src='http://placekitten.com/50/60' />"},
                     {id: 3, name:"kittens", label:"<b>kittens</b> <img src='http://placekitten.com/50/50' />"}]
EOT;
			echo callMethod('mem1','setData',$string);
			echo "\n";
			echo setObject('filt1', JSFunctionList::FILTERING_SELECT);
			echo "\n";
			echo callMethod('filt1','placeAt',"'grid'");
			echo "\n";
			echo callMethod('filt1','set',"'store',".getObject('mem1'));
			echo "\n";
			echo callMethod('filt1','startup');
			?>
			
            <?
            $content=ob_get_contents();            
            ob_end_clean();            
            $app->setBody($content);
            $app->getRequireQueue()->add(JSFunctionList::READY);
            buildComponent();
        ?>
</script>

        
    </head>
<body class="claro">
<!-- and later in the page -->
<div id="grid"></div>
</body>
</html>
