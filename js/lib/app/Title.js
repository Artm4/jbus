define([
    "dojo/_base/declare",
    "dojo/Stateful",
    "dijit/_WidgetBase",  
    "dijit/_OnDijitClickMixin",
    "dijit/_TemplatedMixin",
    "dijit/_WidgetsInTemplateMixin",
    "dojox/mvc/at",
    "dijit/form/TextBox"   
], function(declare,Stateful, _WidgetBase, _OnDijitClickMixin       ) {

    return declare([_WidgetBase, _OnDijitClickMixin], {     
        constructor: function(title)
        {
        	this.titleText=title;
        },
        
    });

});