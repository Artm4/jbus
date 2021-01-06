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
    const REQUEST_JBUS='RequestJbus';
    public static $functSet=array( 
        self::AT=>'dojox/mvc/at',  
        self::DOM=>'dojo/dom',
        self::STATEFUL=>"dojo/Stateful",
        self::FILTERING_SELECT=>'dijit/form/FilteringSelect',
        self::MEMORY=>"dojo/store/Memory",
        self::READY=>"dojo/domReady!",
        self::REQUEST_JBUS=>"ext/RequestJbus"
    );
}

function getObject($id)
{
    return "jbus.ObjectPool.getInstance().get('{$id}')";
}

function setObject($id,$objectName)
{       
     $code=sprintf("jbus.ObjectPool.getInstance().set('%s',%s)",$id,$objectName);
     return $code;
}

function compStateSet($id,$key, $value)
{
    $code=sprintf("%s['state'][%s]=%s",getObject($id),valueString($key),$value);
    return $code;
}

function createObject($id,$functionName,$argString='')
{
    $function=getJSFunction($functionName);
    //$code="(function(){var obj=new {$function}({});\n";
    //$code.="ObjectPool.getInstance().set('{$id}',obj)}())";
    $code=sprintf("jbus.ObjectPool.getInstance().set('%s',new %s(%s))",$id,$function,$argString); 
    $code.="\n";
    $code.=sprintf("%s['state']={}",getObject($id));
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

function sendEvent($eventCallback,$meta,$type,$id)
{
    $function=getJSFunction(JSFunctionList::REQUEST_JBUS);
    //$code="(function(){var obj=new {$function}({});\n";
    //$code.="ObjectPool.getInstance().set('{$id}',obj)}())";
    $code=sprintf("jbus.ObjectPool.getInstance().set('%s',new %s(%s))",$id,$function,$argString);
    return $code;
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
    	var result=[];
		jbus.tool.buildStateList('treeRoot',result);		
		console.log(result);
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
                    { name: "app", location: "lib/app" },
                    { name: "ext", location: "lib/ext" }
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

        var jbus=jbus||{};

        jbus.ObjectPool=function()
        {
			this.hash={}	
        }
        jbus.ObjectPool.prototype.set=function(id,obj)
        {
			this.hash[id]=obj;
        }
        jbus.ObjectPool.prototype.get=function(id)
        {
			return this.hash[id];
        }
        jbus.ObjectPool.prototype.has=function(id)
        {
			return typeof this.hash[id]!='undefined';
        }
        jbus.ObjectPool.instance=null;
        jbus.ObjectPool.getInstance=function()
        {
			if(null==ObjectPool.instance)
			{
			    jbus.ObjectPool.instance=new jbus.ObjectPool();
			}
			return jbus.ObjectPool.instance;
        }
        
        jbus.CodeExecutor=function()
        {
            
        }
        jbus.CodeExecutor.instance=null;
        jbus.CodeExecutor.getInstance=function()
        {
			if(null==jbus.CodeExecutor.instance)
			{
			    jbus.CodeExecutor.instance=new jbus.CodeExecutor();
			}
			return jbus.CodeExecutor.instance;
        }
        jbus.CodeExecutor.prototype.execute=function(codeContainer)
        {   
            var that=this;
            var toIncludePath=[];
            var toIncludeName=[];
            var hasInclude=false;
            var objectPool=jbus.ObjectPool.getInstance();
            
			for(funcName in codeContainer.requireList)
			{
				if(!objectPool.has(funcName))
				{   
				    toIncludeName.push(funcName);
				    toIncludePath.push(codeContainer.requireList[funcName]);
				    hasInclude=true;
				}
			}

			if(hasInclude)
			{
				require(toIncludePath,function(){					
					for(i in arguments)
					{
					    objectPool.set(toIncludeName[i],arguments[i])
					}
					codeContainer.codeCallback.call(that);					
				});
			}
			else
			{
			    codeContainer.codeCallback.call(that);
			}
        }
        
        jbus.CodeContainer=function()
        {
			this.requireList={};
			this.codeCallback=null;
        }
        jbus.CodeContainer.prototype.setRequireList=function(list)
        {
			this.requireList=list;
        }
        jbus.CodeContainer.prototype.setCodeCallback=function(callback)
        {
			this.codeCallback=callback;
        }
        
        jbus.ResponseData=function(data)
        {            
            this.data={};
            this.errorList=[];
            this.messageList=[];   
            this.indexError=0;
            this.indexMessage=0;
            if(typeof data!='undefined')
            {
                this.data=data; 
                if(typeof data['messages']!='undefined')
                {
                    this.messageList=data['messages'];
                }
                if(typeof data['errors']!='undefined')
                {
                    this.errorList=data['errors'];
                }
            }            
        }
        jbus.ResponseData.prototype.hasError=function()
        {
            if(typeof this.data['errors']=='undefined')
            {                                       
                return false;
            }
            
            if(typeof this.data['errors']['length']=='undefined')
            {
                return false;
            }           
            this.errorList=this.data['errors'];
            return !this._isEmptyError();
        }
        jbus.ResponseData.prototype.hasMessage=function()
        {
            if(typeof this.data['messages']=='undefined')
            {                                       
                return false;
            }            
            if(typeof this.data['messages']['length']=='undefined')
            {
                return false;
            }        
            this.messageList=this.data['messages'];
            return !this._isEmptyMessage();
        }
        jbus.ResponseData.prototype.getNextError=function()
        {          
            var errorObj=this.errorList[this.indexError];
            this.moveNextError();
            return errorObj.error;
        }
        jbus.ResponseData.prototype.getNextErrorObj=function()
        {   
            var errorObj=this.errorList[this.indexError];
            this.moveNextError();
            return errorObj;
        }
        jbus.ResponseData.prototype.getNextMessage=function()
        {
            var message=this.messageList[this.indexMessage];
            this.moveNextMessage();
            return message;
        }
        jbus.ResponseData.prototype.getBody=function()
        {
            if(typeof this.data['body']=='undefined')
            {
                return null;
            }
            return this.data.body;
        }
        jbus.ResponseData.prototype.getRequireList=function()
        {
            if(typeof this.data['requireList']=='undefined')
            {
                return null;
            }
            return this.data.requireList;
        }
        jbus.ResponseData.prototype.getCode=function()
        {
            if(typeof this.data['code']=='undefined')
            {
                return null;
            }
            return this.data.code;
        }
        jbus.ResponseData.prototype.getType=function()
        {
            if(typeof this.data['type']=='undefined')
            {
                return null;
            }
            return this.data.type;
        }
        jbus.ResponseData.prototype.getData=function()
        {
            return this.data;
        }
        jbus.ResponseData.prototype.moveNextError=function()
        {
            this.indexError++;
        }
        jbus.ResponseData.prototype.moveNextMessage=function()
        {
            this.indexMessage++;
        }
        jbus.ResponseData.prototype._isEmptyError=function()
        {
            return this.indexError>=this.errorList.length
        }
        jbus.ResponseData.prototype._isEmptyMessage=function()
        {
            return this.indexMessage>=this.messageList.length
        }        
        jbus.config=jbus.config||{};
        jbus.config.target='';
        jbus.config.setTargetUrl=function(url)
        {
            jbus.config.target=url;	
        }
        jbus.config.getTargetUrl=function(url)
        {
            return jbus.config.target;	
        }
        jbus.tool=jbus.tool||{};
        jbus.tool.requestData=function(id)
        {
            var obj=jbus.ObjectPool.getInstance().get(id);
            var result=[];
            jbus.tool.buildStateList(id,result);
            return result;            
        }
        jbus.tool.getState=function(id)
        {
            var obj=jbus.ObjectPool.getInstance().get(id);
            var state=obj['state'];
            return state;
        }
        jbus.tool.buildStateList=function(id,result)
        {   
            var state=jbus.tool.getState(id);
            var childList=state['_chList'];            
            result.push(state);        
            
            for(childId in childList)
            {
                jbus.tool.buildStateList(childList[childId],result);                
            }
            return true;
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
			
			echo createObject('treeRoot', JSFunctionList::FILTERING_SELECT, '{}');
			echo "\n";
			
			echo createObject('treeChild1', JSFunctionList::FILTERING_SELECT, '{}');
			echo "\n";
			
			echo createObject('treeChild2', JSFunctionList::FILTERING_SELECT, '{}');
			echo "\n";
			
			echo compStateSet('treeRoot', '_id', valueString('treeRoot'));
			echo "\n";
			echo compStateSet('treeRoot', '_chList', arr(array(valueString('treeChild1'),valueString('treeChild2'))));
			echo "\n";
			echo compStateSet('treeChild1', '_id', valueString('treeChild1'));
			echo "\n";
			echo compStateSet('treeChild1', '_chList', arr(array(valueString('treeChild1Child1'))));
			echo "\n";
			echo compStateSet('treeChild2', '_id', valueString('treeChild2'));
			echo "\n";
			echo compStateSet('treeChild2', '_chList', arr(array()));
			echo "\n";
			echo createObject('treeChild1Child1', JSFunctionList::FILTERING_SELECT, '{}');
			echo "\n";
			echo compStateSet('treeChild1Child1', '_chList', arr(array()));
			echo "\n";
			echo compStateSet('treeChild1Child1', '_id', valueString('treeChild1Child1'));
			?>
			
            <?
            $content=ob_get_contents();            
            ob_end_clean();            
            $app->setBody($content);
            $app->getRequireQueue()->add(JSFunctionList::READY);
            buildComponent();
        ?>
		
        var call=function()
        {
            var codeContainer=new jbus.CodeContainer();
            codeContainer.setRequireList({'button':'dijit/form/Button','input':'dijit/form/TextBox'});
            codeContainer.setCodeCallback(
                function(){
                    var button=new jbus.ObjectPool.getInstance().get('button');
              		console.log(button);      
                }
            );
            var codeExecutor=jbus.CodeExecutor.getInstance();
            codeExecutor.execute(codeContainer);

            var codeContainer=new jbus.CodeContainer();
            codeContainer.setRequireList({'button':'dijit/form/Button','input':'dijit/form/TextBox'});
            codeContainer.setCodeCallback(
                function(){
              		eval("var button=new jbus.ObjectPool.getInstance().get('button');console.log(button);");      
                }
            );

            setTimeout(function(){
                codeExecutor.execute(codeContainer);
            }, 2000);            
        }();
        
</script>

        
    </head>
<body class="claro">
<!-- and later in the page -->
<div id="grid"></div>
</body>
</html>
