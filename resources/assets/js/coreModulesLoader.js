Modules = [ {
    Swiper: {
        exists: document.getElementsByClassName("swiper").length,
        params: {
            url: "/resources/assets/js/libs/swiper.min.js",
            key: "swiper",
            unique: "1565191008",
            skipCache: skipCache()
        }
    }
}, {
    Sticky_kit: {
        exists: document.getElementsByClassName("sticky-kit").length,
        params: {
            url: "/resources/assets/js/libs/jquery.sticky-kit.min.js",
            key: "sticky",
            skipCache: skipCache()
        }
    }
}, {
    Chosen: {
        exists: document.getElementsByClassName("chosen-js").length,
        params: {
            url: "/resources/assets/js/libs/chosen.jquery.min.js",
            key: "chosen",
            unique: "1565191008",
            skipCache: skipCache()
        }
    }
} ];

function modulesLoader() {
    var moduleMount = 0;
    if (tribooScriptModules != undefined) {
        tribooScriptModules.forEach(function(item, index) {
            for (var key in item) {
                var lib = item[key];
                if ("skipCache" in lib.params) {
                    rogio.log(key + ": ha chiave SkipCache", "success");
                    $.extend(lib.params, {
                        unique: "1565191008",
                        skipCache: lib.params.skipCache,
                        execute: true
                    });
                } else {
                    $.extend(lib.params, {
                        unique: "1565191008",
                        skipCache: false,
                        execute: true
                    });
                }
                if ("require" in lib) {
                    lib.require.map(function(item, index) {
                        var obj = {};
                        obj[item.name] = {
                            exists: lib.exists,
                            params: {
                                url: item.url,
                                key: item.name + "-js",
                                unique: "1565191008",
                                skipCache: skipCache(),
                                execute: true
                            }
                        };
                        Modules.push(obj);
                    });
                }
                Modules.push(item);
                moduleMount++;
                if (moduleMount >= tribooScriptModules.length) {
                    modulesParser();
                }
            }
        });
    } else {
        rogio.log("TribooScript non rilevato", warning);
    }
}

var urlsArray = [];

function modulesParser() {
    var moduleProcessed = 0;
    Modules.forEach(function(item, index, array) {
        for (var key in item) {
            moduleProcessed++;
            var lib = item[key];
            if (lib.exists >= 1) {
                rogio.log("Richiesta libreria " + key, "require");
                urlsArray.push(lib.params);
            } else {
                rogio.log('libreria "' + key + '" non richiesta', "warning");
            }
            if (index === array.length - 1) {
                urlsArray.push({
                    url: "/resources/assets/js/main.js",
                    key: "mainJS",
                    unique: "1565191008",
                    skipCache: skipCache()
                });
                basket.require.apply(basket, urlsArray);
            }
        }
    });
}

