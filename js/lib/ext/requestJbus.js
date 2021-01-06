define([       
        "dojo/_base/declare",
        "dojo/request",      
        "dojo/json",
        "dojo/Deferred",
        'dojo/date',
        "dojo/date/locale",
    ], function(declare,request,json,Deferred,date,locale) {

        return {            
            post:function(options)
            {
                //console.log(options);return;
                url=jbus.config.getTargetUrl();
                options=options || {};
                if(null!=this.data&&typeof options['data']==='undefined')
                {
                    options['data']=this.data;
                }
                if(typeof options['handleAs']==='undefined')
                {
                    options['handleAs']='json';
                }              
                var deferred=request.post(url,options);
                var that=this;
                var promise=deferred.then(function(response){
                    that.handleResponseResult(response);
                });
                return promise;
            },
            postJbus:function(id,eventCallback)
            {
                var codeContainer=new jbus.CodeContainer();
                var deferred = new Deferred();
                deferred.myid=id;
                codeContainer.setDeferred(deferred);
                var that=this;
                codeContainer.setCodeCallback(
                function(){
                    var options=that.postGetOptions(id,eventCallback);
                    return that.post(options);                     
                });
                jbus.CodeExecutor.getInstance().postBuildTreeExecute(codeContainer);
                return deferred;
            },
            postGetOptions:function(id,eventCallback)
            {
                var result=[];
                jbus.tool.buildStateTree(id,result);
                var options={};
                var data={};                
                data['tree']=result;
                data['target']=id;
                data['event']=eventCallback;
                options.data={};
                //console.log(result);
                options.data['jbus']=json.stringify(data);
                return options;
            },
            handleResponseResult: function(response)
            {
                var r=new jbus.ResponseData(response);

                var dateObj=new Date();
                var dateString=locale.format( dateObj, {datePattern:'dd/mm/yyyy',timePattern:'HH:MM:ss' } );
                while(r.hasMessage())
                {
                    dojo.publish('/app/info',{
                                    message: dateString+': '+r.getNextMessage(),
                                    type: "info"
                                } );                           
                }
                while(r.hasError())
                {
                        dojo.publish('/app/error',{
                                    message: dateString+': '+r.getNextError(),
                                    type: "error"
                                    
                                }  );                   
                }           
                
                var codeContainer=new jbus.CodeContainer();
                codeContainer.setRequireList(r.getRequireList());
                codeContainer.setCodeCallback(
                    function(){
                        eval(r.getCode());
                    }
                );
                var codeExecutor=jbus.CodeExecutor.getInstance();                    
                codeExecutor.execute(codeContainer);
                
                return r;
            }
        };

    });