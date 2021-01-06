        
define([
       "dstore/Rest", 
        "dojo/_base/declare",'dstore/legacy/DstoreAdapter' 
    ], function(Rest,declare,DstoreAdapter) {

        return declare([Rest], {
            storeAdapter:function()
            {
            	return new DstoreAdapter(this);
            }
        });

    });