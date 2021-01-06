define([
        'dojo/_base/declare',
        "dijit/TooltipDialog",
        "dijit/form/DropDownButton",
        'ext/store/QueueMemoryDecorator',
        'dgrid/Grid',
        'dgrid/OnDemandList',
        'dgrid/extensions/DijitRegistry',
        'dstore/Memory',
        "dstore/Trackable",
], function(declare, TooltipDialog, DropDownButton, QueueMemoryDecorator,Grid,
        OnDemandList,DijitRegistry,Memory,Trackable
        ) {

    return declare([DropDownButton], {        
        dialog:null,
        grid:null,
        store:null,
        label:'',
        queue:null,
        constructor: function(label,size)
        {
            this.dialog = new TooltipDialog({                
            });
            this.label=label;
            declare.safeMixin(this, {label:label,dropDown: this.dialog});
            
            var store=new (declare([ Memory, Trackable]))({data:[]});
            this.queue=new QueueMemoryDecorator(store,size);
            
            this.grid = new (declare([ Grid,DijitRegistry,OnDemandList]))({
                collection: this.queue.getStore(),
                className: 'gridContainer',
                columns: {
                    date: 'Date',
                    msg: 'Message', 
                    id:'Id'
                }                     
            });
            
            this.dialog.addChild(this.grid);
        },
        add:function(message,date)
        {
            this.queue.add({'msg':message,'date':date});            
        },
        postCreate: function()
        {
            return this.inherited(arguments);  
        },        
        //    any custom code goes here
    });

});