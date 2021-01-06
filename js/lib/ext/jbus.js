var jbus=jbus||{};

jbus.OP=function()
{
    this.hash={}    
}
jbus.OP.prototype.set=function(id,obj)
{
    this.hash[id]=obj;
    obj.jbId=id;
}
jbus.OP.prototype.get=function(id)
{
    return this.hash[id];
}
jbus.OP.prototype.has=function(id)
{
    return typeof this.hash[id]!='undefined';
}
jbus.OP.instance=null;
jbus.OP.gI=function()
{
    if(null==jbus.OP.instance)
    {
        jbus.OP.instance=new jbus.OP();
    }
    return jbus.OP.instance;
}

jbus.CodeExecutor=function()
{    
    this.postExecute=false;
    this.postCodeContainerList=[];
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
jbus.CodeExecutor.prototype.executeContainer=function(codeContainer)
{   
    var that=this;
    var toIncludePath=[];
    var toIncludeName=[];
    var hasInclude=false;
    var objectPool=jbus.OP.gI();
    
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
            codeContainer.callCallback(that);          
        });
    }
    else
    {
        codeContainer.callCallback(that);
    }    
}
jbus.CodeExecutor.prototype.execute=function(codeContainer)
{
    this.executeContainer(codeContainer);    
}

jbus.CodeExecutor.prototype.enablePostExecute=function()
{
    this.postExecute=true;
}

jbus.CodeExecutor.prototype.disablePostExecute=function()
{
    this.postExecute=false;
}

jbus.CodeExecutor.prototype.postBuildTreeExecute=function(codeContainer)
{
    if(!this.postExecute)
    {
        this.executeContainer(codeContainer);
        return false;
    }
    this.postCodeContainerList.push(codeContainer)
    return true;
}
jbus.CodeExecutor.prototype.executePostList=function()
{
    var codeCallback;
    while(this.postCodeContainerList.length)
    {
        var codeContainer=this.postCodeContainerList.shift();
        this.executeContainer(codeContainer);
    }
}

jbus.CodeContainer=function()
{
    this.requireList={};
    this.codeCallback=null;
    this.deferred=null;
}
jbus.CodeContainer.prototype.setRequireList=function(list)
{
    this.requireList=list;
}
jbus.CodeContainer.prototype.setCodeCallback=function(callback)
{
    this.codeCallback=callback;
}
jbus.CodeContainer.prototype.setDeferred=function(deferred)
{
    this.setRequireList({'Deferred':"dojo/Deferred"});
    this.deferred=deferred;
}
jbus.CodeContainer.prototype.getDeferred=function(deferred)
{
    return this.deferred;
}
jbus.CodeContainer.prototype.hasDeferred=function()
{
    return this.deferred!=null;
}
jbus.CodeContainer.prototype.callCallback=function(obj)
{
    var promise=this.codeCallback.call(obj);
    var that=this;
    if(typeof promise!='undefined' && typeof promise['then']=='function'&&this.hasDeferred())
    {
        promise.then(function(result){
            that.getDeferred().resolve(result);
            return result;
        });
    }
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
jbus.tool.buildStateTree=function(id,result)
{   
    var state=jbus.tool.getState(id);
    var rootId=id;
    if(state['rId']['length']>0)
    {
        rootId=state['rId'];
    }
    jbus.tool.populateStateList(rootId,result);
    return result;            
}
jbus.tool.getState=function(id)
{
    var obj=jbus.OP.gI().get(id);
    var state=obj['jbstate'];
    return state;
}
jbus.tool.getWidgetId=function(widget)
{
    return widget.jbId;
}
jbus.tool.populateStateList=function(id,result)
{   
    var state=jbus.tool.getState(id);
    var childList=state['chList'];
    if(typeof jbus.OP.gI().get(id)['onClientRequest']!='undefined')
    {
        jbus.OP.gI().get(id).onClientRequest();
    }
    
    result.push(state);
    
    for(childId in childList)
    {
        jbus.tool.populateStateList(childList[childId],result);                
    }
    return true;
}
jbus.tool.validator= function(value, constraints){
    var error=''; 
    var isValid=true;
    var state=this['jbstate'];   
        
    if(typeof state['validatorList']=='undefined')
    {
        return true;
    }
    for(i in state['validatorList'])
    {
        var isValidCurrent=state['validatorList'][i]['isValid'].call(this,value, constraints);
        if(!isValidCurrent)
        {
             error+=state['validatorList'][i]['message']+'<br/>';
             isValid=isValidCurrent;
        }
    }
    if(error.length)
    {
        this.set('invalidMessage',error);
    }    
    return isValid;
}
jbus.tool.addValidator=function(id,validatorFunction,message)
{
    var state=jbus.tool.getState(id);
    if(typeof state['validatorList']=='undefined')
    {
        return false;
    }
    var validator={};
    validator['isValid']=validatorFunction;
    validator['message']=message;
    state['validatorList'].push(validator);
    return true;
}

jbus.tool.redirect = function(url)
{
    window.location=url;
}
jbus.tool.newTab = function(url)
{
	window.open(url, '_blank');
	
}