define([
    "dojo/_base/declare",
    "./Component",   
    "dijit/_TemplatedMixin",
    "dojo/dom-style"
], function(declare,Component,TemplatedMixin,domStyle) {

    return declare([Component], {   
        //templateString: '<span></span>',
        constructor: function()
        {
            
        },
        _setValueAttr: function(value)
        {
            this.domNode.innerText=value;            
        },
        _getValueAttr: function(value)
        {
            return this.domNode.innerText;            
        },
        _setTextColorAttr: function(value)
        {  
            domStyle.set(this.domNode, "color", value);
        },
        _getTextColorAttr: function(value)
        {  
            return domStyle.get(this.domNode, "color");
        },
        _setBackgroundColorAttr: function(value)
        {
            domStyle.set(this.domNode, "background-color", value);      
        },
        _getBackgroundColorAttr: function(value)
        {
            return domStyle.get(this.domNode, "background-color");      
        },
        
    });

});