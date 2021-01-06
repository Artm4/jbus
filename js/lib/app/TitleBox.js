define([
    "dojo/_base/declare",
    "dijit/_WidgetBase",
    "dijit/_OnDijitClickMixin",
    "dijit/_TemplatedMixin",
    "dojo/text!./TitleBox/template/title-box.html"
], function(declare, _WidgetBase, _OnDijitClickMixin, _TemplatedMixin,
        template) {

    return declare([_WidgetBase, _OnDijitClickMixin, _TemplatedMixin], {
        templateString: template,
        constructor: function(title)
        {
        	this.titleText=title
        },
        postCreate: function()
        {
            
        },
        _setTitleTextAttr: function(value)
        {
        	this.titleNode.innerHTML=value;
        	console.log(value);
        },
        _setBodyAttr: function(value)
        {
        	this.containerNode.innerHTML=value;
        	console.log(value);
        }
        //    any custom code goes here
    });

});