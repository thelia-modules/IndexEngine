/**
 * @class SearchEngine
 * @author Benjamin Perche <benjamin@thelia.net>
 * @param apiUrl The api url to call
 * @param defaults The client defaults
 *
 * This class is framework-agnostic, you can use it without jQuery <3
 */
var SearchEngine = function (apiUrl, defaults) {
    /**
     * The API url to call
     */
    this.apiUrl = this.__filterUrl(apiUrl);

    this.defaults = {
        limit: 10,
        offset: 0
    }.concat(defaults);

    // Public methods

    /**
     * Call the API and return the table
     *
     * @param configurationCode
     * @param params
     * @param options
     */
    var call = function(configurationCode, params, options) {
        var limit = options.limit || this.defaults.limit;
        var offset = options.offset || this.defaults.offset;

        var query = params.concat({limit: limit, offset: offset});

        var url = __formatUrl(parent.apiUrl + "/" + configurationCode, query);
    };

    // Class' inner methods

    var __formatUrl = function(url, query, anchor, keySuffix) {
        if (anchor && anchor[0] != "#") {
            anchor = "#"+anchor;
        }

        // Picked from http://stackoverflow.com/a/1714899
        // Thanks !
        var queryTable = [];

        for(var p in query) {
            if (query.hasOwnProperty(p)) {
                queryTable.push(encodeURIComponent(p) + "=" + encodeURIComponent(query[p]));
            }
        }

        return url + "?" + queryTable.join("&") + anchor;
    };

    /**
     * @param  url Raw URL
     * @return The filtered URL
     */
    var __filterUrl = function(url) {
        return url.replace(/\?.*/g, "");
    };

    /**
     * Execute an ajax call
     *
     * @param options
     * @private
     */
    var __ajax = function(options) {
        var client = null;

        var url = options.url || null;
        var method = options.method || "GET";
        var async = options.async || true;
        var successCallback = options.success || function() {};
        var failCallback = options.fail || function() {};
        var alwaysCallback = options.always || function() {};

        if (window.XMLHttpRequest) {
            client = new XMLHttpRequest();
        } else {
            client = new ActiveXObject("Microsoft.XMLHTTP");
        }

        client.onreadystatechange = function() {
            if (client.readyState == XmlHttpRequest.DONE) {
                if(client.status >= 200 && client.status < 300) {
                    // 2XX success
                    successCallback(client);
                    alwaysCallback(client);
                } else {
                    // 4XX client error
                    // 5XX server error
                    failCallback(client);
                    alwaysCallback(client);
                }
            }
        };

        xmlhttp.open(method, url, async);
        xmlhttp.send();
    }
};