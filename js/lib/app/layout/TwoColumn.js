define([
    "dojo/_base/declare",
    "dijit/_WidgetBase",
    "dijit/_OnDijitClickMixin",
    "dijit/_TemplatedMixin",
    "dojo/text!./TwoColumn/template/two-column.html"
], function(declare, _WidgetBase, _OnDijitClickMixin, _TemplatedMixin,
        template) {

    return declare([_WidgetBase, _OnDijitClickMixin, _TemplatedMixin], {
        templateString: template,
        constructor: function(title)
        {
        	this.baseClass="some-class";
        	this.style="width: 400px";
        	this.styleLeft="float: left";
        	this.styleRight="float: right";
        },
        postCreate: function()
        {
        	
        },
        _setLeftAttr: function(leftWidget)
        {
        	leftWidget.placeAt(this.leftNode,'first');
        },
        _setRightAttr: function(rightWidget)
        {
        	rightWidget.placeAt(this.rightNode,'first');        	
        }
        //    any custom code goes here
    });
});