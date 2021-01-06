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

function setObject($id,$objectName)
{       
     $code=sprintf("ObjectPool.getInstance().set('%s',%s)",$id,$objectName);
     return $code;
}

function createObject($id,$functionName,$argString='')
{
    $function=getJSFunction($functionName);
    //$code="(function(){var obj=new {$function}({});\n";
    //$code.="ObjectPool.getInstance().set('{$id}',obj)}())";
    $code=sprintf("ObjectPool.getInstance().set('%s',new %s(%s))",$id,$function,$argString);
    return $code;
}

function getJSFunction($functionName)
{
    global $app;
    $app->getRequireQueue()->add($functionName);
    return getObject($functionName);
}


function callMethod($id,$method,$argString='')
{
    return sprintf("%s.%s(%s)",getObject($id),$method,$argString);
}

function setVar($varName,$value)
{
    return sprintf("var %s=%s",$varName,$value);
}

function callFunction($functionName,$argString='')
{   
    return sprintf("%s(%s)",getObject($functionName),$argString);
}

function valueString($v,$quoteChar='\'')
{
    return $quoteChar.$v.$quoteChar;
}

function valueCode($v)
{
    return $v;
}

function valueInt($v)
{
    return intval($v);
}

function literal($arr)
{
    $result="{\n";
    foreach ($arr as $k=>$v)
    {
        $result.=sprintf("%s: %s,\n",$k,$v);
    }
    $result.="}\n";
    return $result;
}

function arr($arr)
{
    $result="[\n";
    foreach ($arr as $v)
    {
        $result.=sprintf("%s,\n",$v);
    }
    $result.="]\n";
    return $result;
}

function arguments($arr)
{   
    if(!is_array($arr))
    {
        $arr=array($arr);
    }
    return implode(",",$arr);
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
        foreach (array_keys($app->getRequireQueue()->getIterator()) as $function)
        {   
            echo setObject($function,$function);
            echo "\n";
        }
        echo "\n";
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
        ObjectPool.prototype.has=function(id)
        {
			return typeof this.hash[id]!='undefined';
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

        var loadLib=function(id,libPath)
        {
            var objectPool=ObjectPool.getInstance();
            if(objectPool.has(libPath))
            {
				return false;
            }
            require([libPath],function(libObj){
                objectPool.set(id,libObj);
            })
        }
        <?php 
            
            ob_start();
            ?>
            
			<?
			echo createObject('state123', JSFunctionList::STATEFUL);			
			?>
			
			console.log(<?echo getObject('state123')?>);			
			<?php 
			echo callMethod('state123','set',"'name',1");
			?>
			
			console.log(<?echo callMethod('state123','get',arguments(valueString('name')))?>);
			<?
			echo createObject('mem1', JSFunctionList::MEMORY);
			echo "\n";			
			echo setVar('data',arr(
                 array(
                    literal(
                        array('id'=> valueInt(1), 'name'=>valueString('we'),'label'=>valueString("<i>we</i> <img src='http://placekitten.com/50/70' />",'"'))
                    ),       
			        literal(
		                array('id'=> valueInt(2), 'name'=>valueString('are'),'label'=>valueString("<u>are</u> <img src='http://placekitten.com/50/60' />",'"'))
                    ),
			        literal(
			            array('id'=> valueInt(3), 'name'=>valueString('kittens'),'label'=>valueString("<b>kittens</b> <img src='http://placekitten.com/50/50' />",'"'))
	                )
			     )
	        ));
			echo callMethod('mem1','setData',arguments('data'));
			echo "\n";
			echo createObject('filt1', JSFunctionList::FILTERING_SELECT,
			      literal(array(
        			    'store'=>valueCode(getObject('mem1')),
        			    'obj'=> literal(
        			            array('some'=>valueString('value'))
        		            ),
			            'labelAttr'=>valueString('label'),
			              'labelType'=>valueString('html'),			           
			     ))	        
			);
			echo "\n";
			setVar('var1',literal(array(
			    'store'=>valueCode(getObject('mem1')),
			    'obj'=> literal(
			            array('some'=>valueString('value'))
	            ),
			    'labelAttr'=>valueString('label'),
			        'labelAttr'=>valueInt('12'),
			)));
			
			echo "\n";
			echo callMethod('filt1','placeAt',arguments('grid'));
			echo "\n";			
			echo callMethod('filt1','startup');
			echo "\n";
			//echo callMethod('button-a234234234','on',arguments(array('click',valueCode('function(){Rest.post({event:"click",id:"button-a234234234",model:ser})}'))))
			
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
