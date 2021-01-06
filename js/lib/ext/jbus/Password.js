define([
    "dojo/_base/declare",
    "./Component",   
    "dijit/_TemplatedMixin",
    
    "dijit/form/ValidationTextBox",
    "dojo/dom-style"
], function(declare,Component,TemplatedMixin,domStyle,TextBox,ValidationTextBox) {

    return declare([Component], {   
        //templateString: '<input/>',
        constructor: function()
        {
            
        },
        _setValueAttr: function(value)
        {
            this.domNode.value=value;            
        },
        _getValueAttr: function(value)
        {
            return this.domNode.value;            
        },        
        
    });
    

});