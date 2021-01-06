define([
    "dojo/_base/declare",
    "dstore/Rest"
], function(declare,Rest) {	
    return declare([Rest],{    	
    	constructor: function()
        {
    		declare.safeMixin(this, {target:'rest/account-set.php'});   
        },
        
    });

});