define([
    "dojo/_base/declare",
    "dojox/form/Uploader",  
    "ext/requestJbus",
], function(declare,Uploader,requestJbus) {

    return declare([Uploader], {
        upload: function(/*Object?*/ formData){ 
            //for default url jbus.config.getTargetUrl()
            // summary:
            //      When called, begins file upload. Only supported with plugins.            
            var options=requestJbus.postGetOptions(jbus.tool.getWidgetId(this),'upload');
            for(var field in options.data){
                formData[field]=options.data[field];
            }            
            this.inherited(arguments);
        },
        onComplete: function(result)
        {   
            this.inherited(arguments);
            requestJbus.handleResponseResult(result);
        }
    });

});