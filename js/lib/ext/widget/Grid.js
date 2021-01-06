define([
	'dojo/_base/declare',
	'dstore/Memory',
	'dstore/Trackable',
	'dgrid/Grid',
	'dgrid/Keyboard',
	'dgrid/CellSelection',
	'dgrid/OnDemandGrid',
	'dgrid/extensions/Pagination',	
	'dgrid/extensions/DijitRegistry'
        
], function (declare, Memory, Trackable, Grid, Keyboard, CellSelection,OnDemandGrid, Pagination,DijitRegistry) {
	return declare([Grid, Keyboard, CellSelection, DijitRegistry,Pagination],{    	       
    });	
});