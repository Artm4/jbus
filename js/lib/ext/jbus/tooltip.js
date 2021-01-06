define([       
        "dojo/_base/declare",
        "dijit/Tooltip",
    ], function(declare,Tooltip) {

        return {
            show: function(innerHTML, aroundNode)
            {               
                setTimeout(function(){
                    Tooltip.hide(aroundNode);
                },4000);
                return Tooltip.show(innerHTML, aroundNode);
            }
        };

    });