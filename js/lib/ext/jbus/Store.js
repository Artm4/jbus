define([
    "dojo/_base/declare",  
    "dstore/Memory", 
    'dstore/Trackable',
    "ext/requestJbus",    
    'dojo/_base/lang',
    'dojo/_base/array',
    'dojo/json',
    'dstore/QueryResults',  
    'dojo/Deferred',
    'dojo/when',
], function(declare, Memory,Trackable,requestJbus,lang, arrayUtil, JSON, QueryResults, Deferred, when
        ) {
    var push = [].push;
    return declare([ Memory,Trackable], {
        constructor:function(options)
        {
            this.setProp('id',options.id);
            this.inherited(arguments);            
        },  
        ascendingPrefix: '+',

        // descendingPrefix: String
        //      The prefix to apply to sort property names that are ascending
        descendingPrefix: '-',

        // accepts: String
        //      Defines the Accept header to use on HTTP requests
        accepts: 'application/json',
        __data:{},       
        getProp: function(key)
        {
            return this.__data[key];
        },
        setProp: function(key,value)
        {
            this.__data[key]=value;
        },        
        jbData: {},
        setIdentity: function(identity)
        {
            this.idProperty=identity;
        },
        jbSetData: function (data) {            
            this.jbData=data;
        },      
        
        fetchRange: function (kwArgs) {            
            var deferred = new Deferred();
                   
            var that=this;
            var jbResponse=this._jbRequest(kwArgs);
            
            jbResponse.then(function(response){
                var data=[];
                var total=0;
                var jbData=jbus.OP.gI().get(that.id)['collection']['jbData'];
                if(typeof jbData['items']!='undefined')
                {
                    data=jbData['items'];
                    total=jbData['total'];  
                }
                that.setData(data);
                var dataClear = that.fetchSync();
                var queryResults = new QueryResults(dataClear,{'totalLength':total});    
                deferred.resolve(queryResults);
            });
            
            var queryResults = new QueryResults(deferred.promise);
            queryResults.totalLength = when(queryResults.totalLength);
            return queryResults;
        },
        
        _jbRequest: function(kwArgs)
        {
            requestArgs = {};
            var start = kwArgs.start;
            var end = kwArgs.end;
            requestArgs.queryParams = this._renderRangeParams(start, end); 
            var queryParams=this._jbRenderQueryParams(requestArgs.queryParams);
            this.setProp('jbQueryParams',queryParams);
            if(typeof jbus.OP.gI().get(this.id)['onClientRequest']!='undefined')
            {
                jbus.OP.gI().get(this.id)['onClientRequest']();
            }
            var response = requestJbus.postJbus(this.id,'query');
            return response;
        },
        _jbRenderQueryParams: function (requestParams) {
            // summary:
            //      Constructs the URL used to fetch the data.
            // returns: String
            //      The URL of the data            
            var queryParams = this._renderQueryParams();
            
            if (requestParams) {
                push.apply(queryParams, requestParams);
            }
            
            return queryParams.join(';');
        },
        _renderFilterParams: function (filter) {
            // summary:
            //      Constructs filter-related params to be inserted into the query string
            // returns: String
            //      Filter-related params to be inserted in the query string
            var type = filter.type;
            var args = filter.args;
            if (!type) {
                return [''];
            }
            if (type === 'string') {
                return [args[0]];
            }
            if (type === 'and' || type === 'or') {
                return [arrayUtil.map(filter.args, function (arg) {
                    // render each of the arguments to and or or, then combine by the right operator
                    var renderedArg = this._renderFilterParams(arg);
                    return ((arg.type === 'and' || arg.type === 'or') && arg.type !== type) ?
                        // need to observe precedence in the case of changing combination operators
                        '(' + renderedArg + ')' : renderedArg;
                }, this).join(type === 'and' ? '&' : '|')];
            }
            var target = args[1];
            if (target) {
                if(target._renderUrl) {
                    // detected nested query, and render the url inside as an argument
                    target = '(' + target._renderUrl() + ')';
                } else if (target instanceof Array) {
                    target = '(' + target + ')';
                }
            }
            return [encodeURIComponent(args[0]) + '=' + (type === 'eq' ? '' : type + '=') + encodeURIComponent(target)];
        },
        _renderSortParams: function (sort) {
            // summary:
            //      Constructs sort-related params to be inserted in the query string
            // returns: String
            //      Sort-related params to be inserted in the query string

            var sortString = arrayUtil.map(sort, function (sortOption) {
                var prefix = sortOption.descending ? this.descendingPrefix : this.ascendingPrefix;
                return prefix + encodeURIComponent(sortOption.property);
            }, this);

            var params = [];
            if (sortString) {
                params.push(this.sortParam
                    ? encodeURIComponent(this.sortParam) + '=' + sortString
                    : 'sort(' + sortString + ')'
                );
            }
            return params;
        },
        _renderRangeParams: function (start, end) {
            // summary:
            //      Constructs range-related params to be inserted in the query string
            // returns: String
            //      Range-related params to be inserted in the query string
            var params = [];
            if (this.rangeStartParam) {
                params.push(
                    this.rangeStartParam + '=' + start,
                    this.rangeCountParam + '=' + (end - start)
                );
            } else {
                params.push('limit(' + (end - start) + (start ? (',' + start) : '') + ')');
            }
            return params;
        },

        _renderSelectParams: function (properties) {
            // summary:
            //      Constructs range-related params to be inserted in the query string
            // returns: String
            //      Range-related params to be inserted in the query string
            var params = [];
            if (this.selectParam) {
                params.push(this.selectParam + '=' + properties);
            } else {
                params.push('select(' + properties + ')');
            }
            return params;
        },

        _renderQueryParams: function () {
            var queryParams = [];            
            arrayUtil.forEach(this.queryLog, function (entry) {
                var type = entry.type,
                    renderMethod = '_render' + type[0].toUpperCase() + type.substr(1) + 'Params';

                if (this[renderMethod]) {
                    push.apply(queryParams, this[renderMethod].apply(this, entry.normalizedArguments));
                } else {
                    console.warn('Unable to render query params for "' + type + '" query', entry);
                }
            }, this);

            return queryParams;
        },

        _renderUrl: function (requestParams) {
            // summary:
            //      Constructs the URL used to fetch the data.
            // returns: String
            //      The URL of the data

            var queryParams = this._renderQueryParams(),
                requestUrl = this.target;

            if (requestParams) {
                push.apply(queryParams, requestParams);
            }

            if (queryParams.length > 0) {
                requestUrl += (this._targetContainsQueryString ? '&' : '?') + queryParams.join('&');
            }
            return requestUrl;
        },

        _renderRangeHeaders: function (start, end) {
            // summary:
            //      Applies a Range header if this collection incorporates a range query
            // headers: Object
            //      The headers to which a Range property is added

            var value = 'items=' + start + '-' + (end - 1);
            return {
                'Range': value,
                'X-Range': value //set X-Range for Opera since it blocks "Range" header
            };
        }
        
    });

});