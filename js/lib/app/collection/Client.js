define([
    "dojo/_base/declare",   
    "dojo/json",
    "dojo/text!./client/client-list.json",
    "dstore/Memory"
], function(declare,json,data,Memory) {    
    return declare([Memory],{
    	idProperty: 'id',    	
        constructor: function()
        {
        	var dataPased=json.parse(data,true);        	
        	declare.safeMixin(this, {data:dataPased});           
        },        
    });
});