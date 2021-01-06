define([
    "dojo/_base/declare",
    "dojo/Stateful",
    "dijit/_WidgetBase",  
    "dijit/_OnDijitClickMixin",
    "dijit/_TemplatedMixin",
    "dijit/_WidgetsInTemplateMixin",
    "dojox/mvc/at",
    "dijit/form/TextBox",
    "app/Title",
    "dojo/text!./User/template.html"
], function(declare,Stateful, _WidgetBase, _OnDijitClickMixin, _TemplatedMixin,_WidgetsInTemplateMixin,at,TextBox,Title,
        template) {

    return declare([_WidgetBase, _OnDijitClickMixin, _TemplatedMixin,_WidgetsInTemplateMixin,at], {
        templateString: template,
        constructor: function(title)
        {        	
        	this.at=at;
        	this.prop={};
        	this.prop.val="'some'";
        	this.titleText=title;
        	this.model=new Stateful({
        		name: '',
        		value: '',
        		position: '',
        	})
        	
        	this.model.watch("name", function(name, oldValue, value){
        		console.log('watch1');
        	    console.log(name+ oldValue +value);
        	  });
        	
        	this.model.watch("name", function(name, oldValue, value){
        		console.log('watch2');
        	    console.log(name+ oldValue +value);
        	  });
        	
        	this.model.watch("position", function(name, oldValue, value){
        	    console.log(name+ oldValue +value);
        	  });
        	         	
        	
        },
        postCreate: function()
        {	
        	var that=this;
        	this.position.set('value',at(this.model,'position'));
        	setTimeout(function(){ that.model.set("email", "new mail"); }, 2000);
        	setTimeout(function(){ that.model.set("name", "Bar"); }, 2000);
        	setTimeout(function(){ that.model.set("position", "PosNew"); }, 2100);
        	
        },        
        _onClick:function()
        {
        	console.log(123);
        }
        //    any custom code goes here
    });

});