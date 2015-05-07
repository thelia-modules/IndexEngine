/**
 * @class SearchEngine
 * @author Benjamin Perche <benjamin@thelia.net>
 * @param apiUrl The api url to call
 * @param defaults The client defaults
 *
 * This class is framework-agnostic, you can use it without jQuery <3
 */
var SearchEngine = function (apiUrl, defaults) {
    // Constructor

    /**
     * The API url to call
     */
    this.apiUrl = null;
    this.defaults = null;

    this.constructor = function(apiUrl, defaults) {
        this.apiUrl = this.__filterUrl(apiUrl);

        defaults = defaults != undefined ? defaults : {};

        this.defaults = this.__mergeTables({
            limit: 10,
            offset: 0
        }, defaults);
    };

    // Public methods

    /**
     * Call the API and return the table
     *
     * @param configurationCode
     * @param params
     * @param options
     */
    this.find = function(configurationCode, params, options) {
        options = options != undefined ? options : {};
        params = params != undefined ? params : {};

        var limit = options.limit || this.defaults.limit;
        var offset = options.offset || this.defaults.offset;

        var query = this.__mergeTables(params, {limit: limit, offset: offset});

        var url = this.__formatUrl(this.apiUrl + "/" + configurationCode, query);
        var errorHandler = options.fail || function() {};

        var results = {};

        this.__ajax({
            url: url,
            async: false,
            success: function(xhr) {
                results = JSON.parse(xhr.responseText);
            },
            fail: errorHandler
        });

        return results;
    };

    // Class' inner methods

    this.__formatUrl = function(url, query, anchor, keyPrefix) {
        if (anchor && anchor[0] != "#") {
            anchor = "#"+anchor;
        }

        if (anchor == undefined || anchor == null) {
            anchor = "";
        }

        var queryTable = [];

        for(var p in query) {
            if (query.hasOwnProperty(p)) {
                if (undefined != keyPrefix) {
                    key = keyPrefix + "[" + p + "]";
                } else {
                    key = p;
                }

                if (typeof query[p] == "object") {
                    queryTable.push(this.__formatUrl("", query[p], null, key));
                } else {
                    queryTable.push(encodeURIComponent(key) + "=" + encodeURIComponent(query[p]));
                }
            }
        }

        if (url == "") {
            return queryTable.join("&")
        }

        return url + "?" + queryTable.join("&") + anchor;
    };

    /**
     * @param  url Raw URL
     * @return The filtered URL
     */
    this.__filterUrl = function(url) {
        return url.replace(/\?.*/g, "");
    };

    /**
     * Execute an ajax call
     *
     * @param options
     * @private
     */
    this.__ajax = function(options) {
        var client = null;

        var url = options.url != undefined ? options.url : null;
        var method = options.method != undefined ? options.method : "GET";
        var async = options.async != undefined ? options.async : true;
        var successCallback = options.success || function() {};
        var failCallback = options.fail || function() {};
        var alwaysCallback = options.always || function() {};

        if (window.XMLHttpRequest) {
            client = new XMLHttpRequest();
        } else {
            client = new ActiveXObject("Microsoft.XMLHTTP");
        }

        client.onreadystatechange = function() {
            if (client.readyState == client.DONE) {
                if(client.status >= 200 && client.status < 300) {
                    // 2XX success
                    successCallback(client);
                    alwaysCallback(client);
                } else {
                    // 1XX Hang on
                    // 3XX Redirection
                    // 4XX client error
                    // 5XX server error
                    failCallback(client);
                    alwaysCallback(client);
                }
            }
        };

        client.open(method, url, async);
        client.send();
    };

    this.__mergeTables = function(obj1, obj2) {
        for (var attrName in obj2) {
            if (obj2.hasOwnProperty(attrName)) {
                obj1[attrName] = obj2[attrName];
            }
        }

        return obj1;
    };

    this.constructor(apiUrl, defaults);
};