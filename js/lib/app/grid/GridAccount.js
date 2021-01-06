define([
	'dojo/_base/declare',
	'dstore/Memory',
	'dstore/Trackable',
	'dgrid/Grid',
	'dgrid/Keyboard',
	'dgrid/CellSelection',
	'dgrid/OnDemandGrid',
	'dgrid/extensions/Pagination',
	"app/collection/Account",
	'dgrid/extensions/DijitRegistry'
], function (declare, Memory, Trackable, Grid, Keyboard, CellSelection,OnDemandGrid, Pagination,Account,DijitRegistry) {
	return declare([Grid, Keyboard, CellSelection, DijitRegistry,Pagination],{    	
    	constructor: function()
        {
    		var store = new Account(); 
    	    //var filterBuilder=new store.Filter();
    	    //create filter with condition
    	    /*var filter=filterBuilder.lt('id',3);  
    	    resultSet=store.filter(filter);
    	    resultSet.forEach(function (object) {
    	        console.log(object)
    	    });
    	    */
    		declare.safeMixin(this, 
			{
    			collection: store,
    			columns: {
    				name: {
    					label: 'Name'
    				},
    				desc: {
    					label: 'Description'
    				},
    				date: {
    					label: 'Date'
    				}
    			}
			});   
        },        
    });	
});