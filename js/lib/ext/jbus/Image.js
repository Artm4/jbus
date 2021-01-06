define([
    "dojo/_base/declare",   
    "./Component",   
    "dijit/_TemplatedMixin",
    "dojo/dom-style"
], function(declare,Component,TemplatedMixin,domStyle) {

    return declare([Component,TemplatedMixin], {
        templateString: '<img />',
        constructor: function()
        {
            
        },
        _setValueAttr: function(value)
        {
            this.set('src',value);            
        },
        _getValueAttr: function()
        {
            return this.get('src');          
        },
        _setSrcAttr: function(value)
        {
            this.domNode.src=value;            
        },
        _getSrcAttr: function(value)
        {
            return this.domNode.src;            
        },
        _setWidthAttr: function(value)
        { 
            var valueClear=this.getClearSize(value);
            domStyle.set(this.domNode, "width", valueClear);
        },
        _setHeightAttr: function(value)
        {
            var valueClear=this.getClearSize(value);
            domStyle.set(this.domNode, "height", valueClear);
        },
        getClearSize: function(value)
        {
            var resultPxSearch=value.match('px');
            var valueClear=value;
            if(!resultPxSearch)
            {
                var valueClear=value+'px';
            }
            return valueClear;
        }
        
    });

});