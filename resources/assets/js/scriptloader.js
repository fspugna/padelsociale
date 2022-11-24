function skipCache() {
    var test = "test";
    try {
        localStorage.setItem(test, test);
        localStorage.removeItem(test);
        return false;
    } catch (e) {
        return true;
    }
}

var basketVersion = "1565191008";

var jqueryFile = {
    url: "/resources/assets/js/libs/jquery.min.js",
    key: "jquery",
    unique: basketVersion,
    skipCache: skipCache()
};

var rogioFiles = [ {
    url: "/resources/assets/js/libs/bowser.js",
    key: "bowser",
    unique: basketVersion,
    skipCache: skipCache()
}, {
    url: "/resources/assets/js/libs/modernizr-custom.js",
    key: "modernizr",
    unique: basketVersion,
    skipCache: skipCache()
}, {
    url: "/resources/assets/js/libs/lazysizes.min.js",
    key: "lazysizes",
    unique: basketVersion,
    skipCache: skipCache()
}, {
    url: "/resources/assets/js/libs/jquery.hoverIntent.min.js",
    key: "hoverIntent",
    unique: basketVersion,
    skipCache: skipCache()
}, {
    url: "/resources/assets/js/coreModulesLoader.js",
    key: "coreModules",
    unique: basketVersion,
    skipCache: skipCache()
}, {
    url: "/resources/assets/js/functions.js",
    key: "functions",
    unique: basketVersion,
    skipCache: skipCache()
} ];

if (typeof jQuery === "undefined") {
    rogioFiles.unshift(jqueryFile);
}

basket.require.apply(basket, rogioFiles);