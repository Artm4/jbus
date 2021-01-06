define([
    "dojo/_base/declare", "dojo/request/notify" ,"dojo/topic",
    "ext/model/Event",'ext/widget/MessageBoard','ext/model/ResponseData',
    'dojo/date',"dojo/date/locale","dojo/json","dojox/widget/Toaster","dojo/dom-style",
    "dojo/on","dojo/_base/array","dijit/focus","ext/requestJbus","dojo/dom-class"
    
], function(declare,notify,topic,Event,MessageBoard,ResponseData,date,locale,json,Toaster,style,
        on,array,focus,requestJbus,domClass
        ) {

    return {    	
    	url:function(module,controller,action)
    	{
    		var urlPattern=dojoConfig.app.urlPattern;   
        	if(typeof action==='undefined')
        	{
        		action='';
        	}        	
        	urlPattern=urlPattern.replace('module',module)
        	   .replace('controller',controller)
        	   .replace('action',action);
        	
        	return urlPattern;
    	}
    	,
    	createMessageBoard:function()
    	{
    	    var that=this;
    	    if(null!=this.messageBoard)
	        {
    	        return this.messageBoard;
	        }
            this.messageBoard=true;
            var errors = new Toaster({
                    messageTopic: '/app/error',
                    positionDirection: 'br-up',
                    duration: 10000
                });
                var info = new Toaster({
                    messageTopic: '/app/info',
                    positionDirection: 'tr-down',
                    duration: 10000
                });
    	    return this.messageBoard;
    	},
    	style:function(node,prop,value)
    	{
    	    style.set(node,prop,value);
    	},
    	styleWidget:function(widget,prop,value)
        {
            this.style(widget.domNode,prop,value);
        },
    	registerKeyEvent:function(widget,keyList)
    	{
    	    on(widget.domNode, "keydown", function(event) {
    	        if(focus.curNode && focus.curNode.nodeName=='TEXTAREA')
    	        {
    	            //console.log('Textarea focused');
    	            return;
    	        }
    	        //console.log(event.keyCode);        
    	        var result=array.indexOf(keyList, event.keyCode);
    	        if(-1!=result)
    	        {
    	            //console.log("enter key: " + event.keyCode);
    	            var widgetId=jbus.tool.getWidgetId(widget);
    	            var state=jbus.tool.getState(widgetId);
    	            state['keyCode']=event.keyCode;
    	            if(typeof jbus.OP.gI().get(widgetId)['onClientRequest']!='undefined'){jbus.OP.gI().get(widgetId).onClientRequest()}else{};
    	            requestJbus.postJbus(widgetId,'keydown');    	            
    	        }
    	    })
    	},
    	addClass:function(widget,value)
    	{
    		domClass.replace(widget.domNode,value);
    	}
    }
});