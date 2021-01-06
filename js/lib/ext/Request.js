define([       
        "dojo/_base/declare",
        "dojo/request",
        "ext/model/ResponseData"
    ], function(declare,request,ResponseData) {

        return declare(null, {
            data:null,          
            constructor:function()
            {
                
            },      
            setData:function(data)
            {
                this.data=data;
                return this;
            },
            post:function(url,options)
            {
                options=options || {};
                if(null!=this.data&&typeof options['data']==='undefined')
                {
                    options['data']=this.data;
                }
                if(typeof options['handleAs']==='undefined')
                {
                    options['handleAs']='json';
                }
                //options['handle']=function(response){console.log(123)}
                var deferred=request.post(url,options);
                var promise=deferred.then(function(response){
                    return new ResponseData(response);
                });
                return promise;
            }
        });

    });