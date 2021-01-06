define([
    'dojo/_base/declare',   
    'dstore/Trackable',
    'dgrid/Grid',
    'dgrid/Keyboard', 
    'dgrid/OnDemandGrid',
    'dgrid/extensions/Pagination',  
    'dgrid/extensions/DijitRegistry',    
        
], function (declare, Trackable, Grid, Keyboard,OnDemandGrid, Pagination,DijitRegistry,Store) {
    return declare([Grid, Keyboard, DijitRegistry,Pagination],{        
    }); 
});