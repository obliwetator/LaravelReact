function parseURL(url) {
    // http://james.padolsey.com/javascript/parsing-urls-with-the-dom/
    var a = document.createElement("a");
    a.href = url;
    return {
        source: url,
        protocol: a.protocol.replace(":", ""),
        host: a.hostname,
        port: a.port,
        query: a.search,
        params: (function() {
            var ret = {},
                seg = a.search.replace(/^\?/, "").split("&"),
                len = seg.length,
                i = 0,
                s;
            for (; i < len; i++) {
                if (!seg[i]) {
                    continue;
                }
                s = seg[i].split("=");
                ret[s[0]] = s[1];
            }
            return ret;
        })(),
        file: (a.pathname.match(/\/([^/?#]+)$/i) || [, ""])[1],
        hash: a.hash.replace("#", ""),
        path: a.pathname.replace(/^([^/])/, "/$1"),
        relative: (a.href.match(/tps?:\/\/[^/]+(.+)/) || [, ""])[1],
        segments: a.pathname.replace(/^\//, "").split("/")
    };
}

$.test = {
    ajax: {
        prebuildOptions: function(settings, additionalParameters) {
            var targetUrl = parseURL(settings.url);
            var pathQuery = targetUrl.path + targetUrl.query;
        },

        formSubmit: function(form, type, callback) {},

        getJSON: function(options) {},

		getHTML: function(options) {}
    }
};

$.ajaxSetup({
	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
	}
});
