define([
    "dojo/_base/declare",    
], function(declare
        ) {

    return declare([], {
        data:{},
        errorList:[],
        messageList:[],   
        indexError:0,
        indexMessage:0,
        constructor:function(data)
        {            
            if(typeof data!='undefined')
            {
                this.data=data; 
                if(typeof data['messages']!='undefined')
                {
                    this.messageList=data['messages'];
                }
                if(typeof data['errors']!='undefined')
                {
                    this.errorList=data['errors'];
                }
            }             
        },
        hasError:function()
        {
            if(typeof this.data['errors']=='undefined')
            {                                       
                return false;
            }
            
            if(typeof this.data['errors']['length']=='undefined')
            {
                return false;
            }           
            this.errorList=this.data['errors'];
            return !this._isEmptyError();
        },
        hasMessage:function()
        {
            if(typeof this.data['messages']=='undefined')
            {                                       
                return false;
            }            
            if(typeof this.data['messages']['length']=='undefined')
            {
                return false;
            }        
            this.messageList=this.data['messages'];
            return !this._isEmptyMessage();
        },
        getNextError:function()
        {          
            var errorObj=this.errorList[this.indexError];
            this.moveNextError();
            return errorObj.error;
        },
        getNextErrorObj:function()
        {   
            var errorObj=this.errorList[this.indexError];
            this.moveNextError();
            return errorObj;
        },
        getNextMessage:function()
        {
            var message=this.messageList[this.indexMessage];
            this.moveNextMessage();
            return message;
        },
        getBody:function()
        {
            if(typeof this.data['body']=='undefined')
            {
                return null;
            }
            return this.data.body;
        },
        getData:function()
        {
            return this.data;
        },
        moveNextError:function()
        {
            this.indexError++;
        },
        moveNextMessage:function()
        {
            this.indexMessage++;
        },
        _isEmptyError:function()
        {
            return this.indexError>=this.errorList.length
        },
        _isEmptyMessage:function()
        {
            return this.indexMessage>=this.messageList.length
        }
    });

});