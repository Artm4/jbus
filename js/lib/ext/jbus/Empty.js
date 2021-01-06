define([
    "dojo/_base/declare",   
    "dojo/dom",
    "dojo/on"
], function(declare,dom,on
        ) {

    return declare([], {
        domNode: null,
        constructor: function(params, srcNodeRef)
        {
            this.create(params, srcNodeRef);
        },
        create: function(params, srcNodeRef){            
            // store pointer to original DOM tree
            this.domNode = dom.byId(srcNodeRef);
            this._created = true;
        },
        startup: function()
        {
            
        },
        on: function(event,callback)
        {
            on(this.domNode, event, callback);
        }
    });

});