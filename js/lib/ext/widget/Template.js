define([
    "dojo/_base/declare",
    "dojo/Stateful",
    "dijit/_WidgetBase",  
    "dijit/_OnDijitClickMixin",
    "dijit/_TemplatedMixin",
    "dijit/_WidgetsInTemplateMixin"
    
], function(declare,Stateful, _WidgetBase, _OnDijitClickMixin, _TemplatedMixin,_WidgetsInTemplateMixin
        ) {

    return declare([_WidgetBase, _OnDijitClickMixin, _TemplatedMixin,_WidgetsInTemplateMixin], {     
    	templateString:'',    	
    });

});