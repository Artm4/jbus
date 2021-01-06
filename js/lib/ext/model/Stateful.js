define([
    "dojo/_base/declare",
    "dojo/Stateful"    
    
], function(declare,Stateful
        ) {

    return declare([Stateful], {
    	getData:function()
    	{
    		var data={};
    		for(prop in this)
			{
    			if(typeof this[prop]!='function'&&this.hasOwnProperty(prop))
				{
    				data[prop]=this[prop];
				}
			}
    		return data;
    	},
    	resetData:function(value)
        {            
            var initValue='';
            if(typeof value!='undefined')
            {
                initValue=value;
            }
            for(prop in this)
            {
                if(typeof this[prop]!='function'&&this.hasOwnProperty(prop))
                {
                    this.set(prop,initValue);                    
                }
            }
            return this;
        },
    });

});