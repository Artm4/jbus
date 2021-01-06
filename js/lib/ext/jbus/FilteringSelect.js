define([
    "dojo/_base/declare",  
    "dojo/store/Memory", "dijit/form/FilteringSelect",
    
    
], function(declare, Memory, FilteringSelect
        ) {

    return declare([ FilteringSelect], {
        /*constructor:function()
        {
            var store=new Memory();
            this.set('store',store);
            this.inherited(arguments);
        }*/
        queryExpr: "*",
    });

});