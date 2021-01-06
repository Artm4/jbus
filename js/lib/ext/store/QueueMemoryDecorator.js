define([       
        "dojo/_base/declare" 
    ], function(declare) {

        return declare([], {
            store:null,
            firstId:0,
            lastId:0,
            size:0,
            sizeMax:10,
            constructor:function(store,sizeMax)
            {
                var storeF=store.sort('id','descending');
                this.store=storeF;
                this.sizeMax=sizeMax;
            },
            add:function(item)
            {                
                item['id']=this._getNextId();
                this.store.add(item);                
                this.size++;
                if(this._getSize()>this.sizeMax)
                {
                    this.store.remove(this.firstId);
                    this.firstId++;                    
                }
            },
            getStore:function()
            {
                return this.store;  
            },
            _getSize:function()
            {
                return this.size;
                /*
                 * taking size correctly including rest stores
                 * var def=store.fetch();
                 def.then(function(result)
                         {
                              console.log(result.totalLength);
                         })
                 
                 */
            },
            _getNextId:function()
            {
                this.lastId++;
                return this.lastId;
            }
        });

    });