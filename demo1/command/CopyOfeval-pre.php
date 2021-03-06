<?php 

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
        return $this->body;
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
        $require[$function]=JSFunctionList::$functSet[$function];
    }
    public function getIterator()
    {
        return $this->require;
    }
}



class JSFunctionList
{
    const AT='at';
    public static $functSet=array( 
        self::AT=>'dojox/mvc/at',            
    );
}

function getObject($id)
{
    return "ObjectPool.get({$id})";
}

function getJSFunction($function)
{
    
    //add in require
    return $function;
}


function callMethod($obj,$argString)
{
    
}

function callFunction($function,$argString)
{

}

function buildComponent($app)
{    
    ?>
    require([
    <?php 
        echo implode("','",$app->getRequireQueue()->getIterator())
    ?>
    ], function( <?php 
        echo implode("','",array_keys($app->getRequireQueue()->getIterator()))
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
        <script src="../../js/core/dojo/dojo.js" data-dojo-config="isDebug: 1, async: 1, parseOnLoad: 1"></script>
        
        <script>

        require([
                 "dojo/dom", "dojo/store/Memory", "dijit/form/FilteringSelect","dstore/Rest", 
                 "dojo/_base/declare",'dstore/SimpleQuery',"dijit/form/ComboBox",  'dstore/legacy/DstoreAdapter', "dojox/mvc/at",   "dojo/Stateful",
                 "dojo/domReady!"
             ], function(dom, Memory, FilteringSelect,Rest,declare,SimpleQuery,ComboBox,DstoreAdapter, at, Stateful){
                var dstoreRest = new Rest({target:'../rest/filtering-select.php'});
                var filterBuilder=new dstoreRest.Filter();
                var filter=filterBuilder.eq('organizationType',1);
                resultSet=dstoreRest.filter(filter);
                
                dojoStore = new DstoreAdapter(resultSet);
                //var dojoStore = new Rest({target:'rest/filtering-select.php'});
                
                var model=new Stateful({
                name: '',
                value: '',
                position: '',
                })
                
                var fs = new FilteringSelect({
                      id: "dojoBox",
                      //value: 3,
                      store: dojoStore,
                      searchAttr: "name",
                      name: "xyz",
                      labelAttr: "label",
                      labelType: "html",
                      autocomplete: false,
                      placeholder: "Select your favorite song.",
                      onChange: function(value){console.log(1);}
                }, dom.byId("dojoBox"));
                       fs.startup();
                       
                
                
                function callEval()
                {
                    var toEval="obj.set('value',at(model,'name').direction(at.both));"
                    obj=this;
                    eval(toEval);
                }
                //callEval.call(fs)                
                
                var commandEval=function()
                {
                    
                }
                commandEval.prototype.setObject=function(obj)
                {
                    this.obj=obj;
                }
                commandEval.prototype.setEvalString=function(evalString)
                {
                    this.evalString=evalString;
                }
                commandEval.prototype.execute=function()
                { 
                    var obj=this.obj;
                    eval(this.evalString);
                }
                
                var toEval="obj.set('value',at(model,'name').direction(at.both));"
                var command=new commandEval();
                command.setObject(fs);
                command.setEvalString(toEval);
                command.execute();
                
                //fs.set('value',at(model,'name').direction(at.both));
                
                model.watch("name", function(name, oldValue, value){
                    console.log('watch2');
                    console.log(name+ oldValue +value);
                  });
                
                var someFun=function(arg1,arg2)
                {
                    console.log('arg1'+arg1);
                    console.log('arg2'+arg2);  
                    console.log('id'+this.get('id'));
                }
                someFun.apply(fs,[1,2,3]);
                
                var toEval2="ObjectPool.get('button-2234343').set('value',at(ObjectPool.get('model-2234343'),'name').direction(at.both));";
                console.log(arguments);
                
             });
</script>

        
    </head>
<body class="claro">
<!-- and later in the page -->
<div id="grid"></div>
</body>
</html>
