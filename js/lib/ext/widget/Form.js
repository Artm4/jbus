define([
    "dojo/_base/declare",
    "dojo/Stateful",
    "dijit/_WidgetBase",  
    "dijit/_OnDijitClickMixin",
    "dijit/_TemplatedMixin",
    "dijit/_WidgetsInTemplateMixin",
    "dojox/mvc/at",
    "dijit/form/TextBox",
    "dijit/form/FilteringSelect",
    "dijit/form/ValidationTextBox",
    "dijit/form/Select",
    "dijit/form/NumberSpinner",
    "dijit/layout/TabContainer",
    "dijit/layout/TabController",
    "clipart/Plus",
    "dijit/form/Textarea",
    "dojox/form/uploader/FileList"
    
], function(declare,Stateful, _WidgetBase, _OnDijitClickMixin, _TemplatedMixin,_WidgetsInTemplateMixin,at,TextBox,Plus,Textarea,FileList
        ) {

    return declare([_WidgetBase, _OnDijitClickMixin, _TemplatedMixin,_WidgetsInTemplateMixin,at], {     
    	templateString:'',
    	model:{},
    	at:at,        
        getModel:function()
        {
        	return this.model;
        },
        setModel:function(model)
        {
        	this.model=model;
        },
        setId:function(id)
        {
            this.model.id=id;           
            this.init();
        },
        init:function()
        {
            
        }
    });

});