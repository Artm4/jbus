define([
    "dojo/_base/declare",   
    "dojo/json",
    "dojo/text!./user/user-list.json"
], function(declare,json,data) {	
    return declare([],{
    	dataUser: [],
    	constructor: function()
        {
    		this.dataUser=json.parse(data,true);
        },
        
    });

});