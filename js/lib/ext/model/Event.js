define([
    "dojo/_base/declare", 
    
], function(declare
        ) {

    return declare([], {
    	data:null,
    	message:"",
    	eventTag:"",
    	objectType:"",
    	construct:function(init)
    	{
    		if(typeof init!='undefined')
			{
    		    if(typeof init['type']!='undefined')
		        {
    			    this.setType(init['type']);
			    }
    		    if(typeof init['tag']!='undefined')
                {
                    this.setEventTag(init['tag']);
                }
			}
    	},    	
    	getData:function()
    	{
    		return this.data;
    	},
    	setData:function(data)
        {
    		this.data=data;
        },    	
    	getMessage:function()
    	{
    		return this.message;
    	},
    	setMessage:function(message)
    	{
    		this.message=message;
    	},    	
    	setType:function(type)
    	{
    		this.objectType=type;
    	},
    	getType:function()
    	{
    	    return this.objectType;
    	},
    	setEventTag:function(tag)
    	{
    	    this.eventTag=tag;
    	}
    });

});