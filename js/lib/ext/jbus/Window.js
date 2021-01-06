define([
    "dojo/_base/declare",  
    "dojox/layout/FloatingPane",
], function(declare,FloatingPane) {

    return declare([FloatingPane], {
        
        parseOnLoad: false,
        close: function()
        {
            this.hide();
        },
    });

});