!function(A, e, t) {
    function n(A, e) {
        return typeof A === e;
    }
    function o() {
        var A, e, t, o, i, r, a;
        for (var l in w) if (w.hasOwnProperty(l)) {
            if (A = [], e = w[l], e.name && (A.push(e.name.toLowerCase()), e.options && e.options.aliases && e.options.aliases.length)) for (t = 0; t < e.options.aliases.length; t++) A.push(e.options.aliases[t].toLowerCase());
            for (o = n(e.fn, "function") ? e.fn() : e.fn, i = 0; i < A.length; i++) r = A[i], 
            a = r.split("."), 1 === a.length ? Modernizr[a[0]] = o : (!Modernizr[a[0]] || Modernizr[a[0]] instanceof Boolean || (Modernizr[a[0]] = new Boolean(Modernizr[a[0]])), 
            Modernizr[a[0]][a[1]] = o), T.push((o ? "" : "no-") + a.join("-"));
        }
    }
    function i(A) {
        var e = R.className, t = Modernizr._config.classPrefix || "";
        if (B && (e = e.baseVal), Modernizr._config.enableJSClass) {
            var n = new RegExp("(^|\\s)" + t + "no-js(\\s|$)");
            e = e.replace(n, "$1" + t + "js$2");
        }
        Modernizr._config.enableClasses && (e += " " + t + A.join(" " + t), B ? R.className.baseVal = e : R.className = e);
    }
    function r() {
        return "function" != typeof e.createElement ? e.createElement(arguments[0]) : B ? e.createElementNS.call(e, "http://www.w3.org/2000/svg", arguments[0]) : e.createElement.apply(e, arguments);
    }
    function a(A, e) {
        if ("object" == typeof A) for (var t in A) G(A, t) && a(t, A[t]); else {
            A = A.toLowerCase();
            var n = A.split("."), o = Modernizr[n[0]];
            if (2 == n.length && (o = o[n[1]]), "undefined" != typeof o) return Modernizr;
            e = "function" == typeof e ? e() : e, 1 == n.length ? Modernizr[n[0]] = e : (!Modernizr[n[0]] || Modernizr[n[0]] instanceof Boolean || (Modernizr[n[0]] = new Boolean(Modernizr[n[0]])), 
            Modernizr[n[0]][n[1]] = e), i([ (e && 0 != e ? "" : "no-") + n.join("-") ]), Modernizr._trigger(A, e);
        }
        return Modernizr;
    }
    function l() {
        var A = e.body;
        return A || (A = r(B ? "svg" : "body"), A.fake = !0), A;
    }
    function s(A, t, n, o) {
        var i, a, s, c, d = "modernizr", u = r("div"), p = l();
        if (parseInt(n, 10)) for (;n--; ) s = r("div"), s.id = o ? o[n] : d + (n + 1), u.appendChild(s);
        return i = r("style"), i.type = "text/css", i.id = "s" + d, (p.fake ? p : u).appendChild(i), 
        p.appendChild(u), i.styleSheet ? i.styleSheet.cssText = A : i.appendChild(e.createTextNode(A)), 
        u.id = d, p.fake && (p.style.background = "", p.style.overflow = "hidden", c = R.style.overflow, 
        R.style.overflow = "hidden", R.appendChild(p)), a = t(u, A), p.fake ? (p.parentNode.removeChild(p), 
        R.style.overflow = c, R.offsetHeight) : u.parentNode.removeChild(u), !!a;
    }
    function c(A, e) {
        return !!~("" + A).indexOf(e);
    }
    function d(A) {
        return A.replace(/([a-z])-([a-z])/g, function(A, e, t) {
            return e + t.toUpperCase();
        }).replace(/^-/, "");
    }
    function u(A, e) {
        return function() {
            return A.apply(e, arguments);
        };
    }
    function p(A, e, t) {
        var o;
        for (var i in A) if (A[i] in e) return t === !1 ? A[i] : (o = e[A[i]], n(o, "function") ? u(o, t || e) : o);
        return !1;
    }
    function f(A) {
        return A.replace(/([A-Z])/g, function(A, e) {
            return "-" + e.toLowerCase();
        }).replace(/^ms-/, "-ms-");
    }
    function h(e, t, n) {
        var o;
        if ("getComputedStyle" in A) {
            o = getComputedStyle.call(A, e, t);
            var i = A.console;
            if (null !== o) n && (o = o.getPropertyValue(n)); else if (i) {
                var r = i.error ? "error" : "log";
                i[r].call(i, "getComputedStyle returning null, its possible modernizr test results are inaccurate");
            }
        } else o = !t && e.currentStyle && e.currentStyle[n];
        return o;
    }
    function m(e, n) {
        var o = e.length;
        if ("CSS" in A && "supports" in A.CSS) {
            for (;o--; ) if (A.CSS.supports(f(e[o]), n)) return !0;
            return !1;
        }
        if ("CSSSupportsRule" in A) {
            for (var i = []; o--; ) i.push("(" + f(e[o]) + ":" + n + ")");
            return i = i.join(" or "), s("@supports (" + i + ") { #modernizr { position: absolute; } }", function(A) {
                return "absolute" == h(A, null, "position");
            });
        }
        return t;
    }
    function v(A, e, o, i) {
        function a() {
            s && (delete b.style, delete b.modElem);
        }
        if (i = n(i, "undefined") ? !1 : i, !n(o, "undefined")) {
            var l = m(A, o);
            if (!n(l, "undefined")) return l;
        }
        for (var s, u, p, f, h, v = [ "modernizr", "tspan", "samp" ]; !b.style && v.length; ) s = !0, 
        b.modElem = r(v.shift()), b.style = b.modElem.style;
        for (p = A.length, u = 0; p > u; u++) if (f = A[u], h = b.style[f], c(f, "-") && (f = d(f)), 
        b.style[f] !== t) {
            if (i || n(o, "undefined")) return a(), "pfx" == e ? f : !0;
            try {
                b.style[f] = o;
            } catch (g) {}
            if (b.style[f] != h) return a(), "pfx" == e ? f : !0;
        }
        return a(), !1;
    }
    function g(A, e, t, o, i) {
        var r = A.charAt(0).toUpperCase() + A.slice(1), a = (A + " " + P.join(r + " ") + r).split(" ");
        return n(e, "string") || n(e, "undefined") ? v(a, e, o, i) : (a = (A + " " + Q.join(r + " ") + r).split(" "), 
        p(a, e, t));
    }
    function y(A, e, n) {
        return g(A, t, t, e, n);
    }
    var T = [], w = [], E = {
        _version: "3.5.0",
        _config: {
            classPrefix: "",
            enableClasses: !0,
            enableJSClass: !0,
            usePrefixes: !0
        },
        _q: [],
        on: function(A, e) {
            var t = this;
            setTimeout(function() {
                e(t[A]);
            }, 0);
        },
        addTest: function(A, e, t) {
            w.push({
                name: A,
                fn: e,
                options: t
            });
        },
        addAsyncTest: function(A) {
            w.push({
                name: null,
                fn: A
            });
        }
    }, Modernizr = function() {};
    Modernizr.prototype = E, Modernizr = new Modernizr(), Modernizr.addTest("applicationcache", "applicationCache" in A), 
    Modernizr.addTest("cookies", function() {
        try {
            e.cookie = "cookietest=1";
            var A = -1 != e.cookie.indexOf("cookietest=");
            return e.cookie = "cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT", A;
        } catch (t) {
            return !1;
        }
    }), Modernizr.addTest("cors", "XMLHttpRequest" in A && "withCredentials" in new XMLHttpRequest()), 
    Modernizr.addTest("eventlistener", "addEventListener" in A), Modernizr.addTest("json", "JSON" in A && "parse" in JSON && "stringify" in JSON), 
    Modernizr.addTest("devicemotion", "DeviceMotionEvent" in A), Modernizr.addTest("deviceorientation", "DeviceOrientationEvent" in A), 
    Modernizr.addTest("localstorage", function() {
        var A = "modernizr";
        try {
            return localStorage.setItem(A, A), localStorage.removeItem(A), !0;
        } catch (e) {
            return !1;
        }
    }), Modernizr.addTest("sessionstorage", function() {
        var A = "modernizr";
        try {
            return sessionStorage.setItem(A, A), sessionStorage.removeItem(A), !0;
        } catch (e) {
            return !1;
        }
    });
    var R = e.documentElement, B = "svg" === R.nodeName.toLowerCase();
    Modernizr.addTest("audio", function() {
        var A = r("audio"), e = !1;
        try {
            e = !!A.canPlayType, e && (e = new Boolean(e), e.ogg = A.canPlayType('audio/ogg; codecs="vorbis"').replace(/^no$/, ""), 
            e.mp3 = A.canPlayType('audio/mpeg; codecs="mp3"').replace(/^no$/, ""), e.opus = A.canPlayType('audio/ogg; codecs="opus"') || A.canPlayType('audio/webm; codecs="opus"').replace(/^no$/, ""), 
            e.wav = A.canPlayType('audio/wav; codecs="1"').replace(/^no$/, ""), e.m4a = (A.canPlayType("audio/x-m4a;") || A.canPlayType("audio/aac;")).replace(/^no$/, ""));
        } catch (t) {}
        return e;
    }), Modernizr.addTest("canvas", function() {
        var A = r("canvas");
        return !(!A.getContext || !A.getContext("2d"));
    }), Modernizr.addTest("video", function() {
        var A = r("video"), e = !1;
        try {
            e = !!A.canPlayType, e && (e = new Boolean(e), e.ogg = A.canPlayType('video/ogg; codecs="theora"').replace(/^no$/, ""), 
            e.h264 = A.canPlayType('video/mp4; codecs="avc1.42E01E"').replace(/^no$/, ""), e.webm = A.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/^no$/, ""), 
            e.vp9 = A.canPlayType('video/webm; codecs="vp9"').replace(/^no$/, ""), e.hls = A.canPlayType('application/x-mpegURL; codecs="avc1.42E01E"').replace(/^no$/, ""));
        } catch (t) {}
        return e;
    }), Modernizr.addTest("fileinput", function() {
        if (navigator.userAgent.match(/(Android (1.0|1.1|1.5|1.6|2.0|2.1))|(Windows Phone (OS 7|8.0))|(XBLWP)|(ZuneWP)|(w(eb)?OSBrowser)|(webOS)|(Kindle\/(1.0|2.0|2.5|3.0))/)) return !1;
        var A = r("input");
        return A.type = "file", !A.disabled;
    }), Modernizr.addTest("videoloop", "loop" in r("video"));
    var F = E._config.usePrefixes ? " -webkit- -moz- -o- -ms- ".split(" ") : [ "", "" ];
    E._prefixes = F;
    var G;
    !function() {
        var A = {}.hasOwnProperty;
        G = n(A, "undefined") || n(A.call, "undefined") ? function(A, e) {
            return e in A && n(A.constructor.prototype[e], "undefined");
        } : function(e, t) {
            return A.call(e, t);
        };
    }(), E._l = {}, E.on = function(A, e) {
        this._l[A] || (this._l[A] = []), this._l[A].push(e), Modernizr.hasOwnProperty(A) && setTimeout(function() {
            Modernizr._trigger(A, Modernizr[A]);
        }, 0);
    }, E._trigger = function(A, e) {
        if (this._l[A]) {
            var t = this._l[A];
            setTimeout(function() {
                var A, n;
                for (A = 0; A < t.length; A++) (n = t[A])(e);
            }, 0), delete this._l[A];
        }
    }, Modernizr._q.push(function() {
        E.addTest = a;
    }), a("htmlimports", "import" in r("link")), Modernizr.addAsyncTest(function() {
        function A(r) {
            o++, clearTimeout(e);
            var l = r && "playing" === r.type || 0 !== i.currentTime;
            return !l && n > o ? void (e = setTimeout(A, t)) : (i.removeEventListener("playing", A, !1), 
            a("videoautoplay", l), void (i.parentNode && i.parentNode.removeChild(i)));
        }
        var e, t = 200, n = 5, o = 0, i = r("video"), l = i.style;
        if (!(Modernizr.video && "autoplay" in i)) return void a("videoautoplay", !1);
        l.position = "absolute", l.height = 0, l.width = 0;
        try {
            if (Modernizr.video.ogg) i.src = "data:video/ogg;base64,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"; else {
                if (!Modernizr.video.h264) return void a("videoautoplay", !1);
                i.src = "data:video/mp4;base64,AAAAIGZ0eXBpc29tAAACAGlzb21pc28yYXZjMW1wNDEAAAAIZnJlZQAAAs1tZGF0AAACrgYF//+q3EXpvebZSLeWLNgg2SPu73gyNjQgLSBjb3JlIDE0OCByMjYwMSBhMGNkN2QzIC0gSC4yNjQvTVBFRy00IEFWQyBjb2RlYyAtIENvcHlsZWZ0IDIwMDMtMjAxNSAtIGh0dHA6Ly93d3cudmlkZW9sYW4ub3JnL3gyNjQuaHRtbCAtIG9wdGlvbnM6IGNhYmFjPTEgcmVmPTMgZGVibG9jaz0xOjA6MCBhbmFseXNlPTB4MzoweDExMyBtZT1oZXggc3VibWU9NyBwc3k9MSBwc3lfcmQ9MS4wMDowLjAwIG1peGVkX3JlZj0xIG1lX3JhbmdlPTE2IGNocm9tYV9tZT0xIHRyZWxsaXM9MSA4eDhkY3Q9MSBjcW09MCBkZWFkem9uZT0yMSwxMSBmYXN0X3Bza2lwPTEgY2hyb21hX3FwX29mZnNldD0tMiB0aHJlYWRzPTEgbG9va2FoZWFkX3RocmVhZHM9MSBzbGljZWRfdGhyZWFkcz0wIG5yPTAgZGVjaW1hdGU9MSBpbnRlcmxhY2VkPTAgYmx1cmF5X2NvbXBhdD0wIGNvbnN0cmFpbmVkX2ludHJhPTAgYmZyYW1lcz0zIGJfcHlyYW1pZD0yIGJfYWRhcHQ9MSBiX2JpYXM9MCBkaXJlY3Q9MSB3ZWlnaHRiPTEgb3Blbl9nb3A9MCB3ZWlnaHRwPTIga2V5aW50PTI1MCBrZXlpbnRfbWluPTEwIHNjZW5lY3V0PTQwIGludHJhX3JlZnJlc2g9MCByY19sb29rYWhlYWQ9NDAgcmM9Y3JmIG1idHJlZT0xIGNyZj0yMy4wIHFjb21wPTAuNjAgcXBtaW49MCBxcG1heD02OSBxcHN0ZXA9NCBpcF9yYXRpbz0xLjQwIGFxPTE6MS4wMACAAAAAD2WIhAA3//728P4FNjuZQQAAAu5tb292AAAAbG12aGQAAAAAAAAAAAAAAAAAAAPoAAAAZAABAAABAAAAAAAAAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACAAACGHRyYWsAAABcdGtoZAAAAAMAAAAAAAAAAAAAAAEAAAAAAAAAZAAAAAAAAAAAAAAAAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAAAAAEAAAAAAAgAAAAIAAAAAACRlZHRzAAAAHGVsc3QAAAAAAAAAAQAAAGQAAAAAAAEAAAAAAZBtZGlhAAAAIG1kaGQAAAAAAAAAAAAAAAAAACgAAAAEAFXEAAAAAAAtaGRscgAAAAAAAAAAdmlkZQAAAAAAAAAAAAAAAFZpZGVvSGFuZGxlcgAAAAE7bWluZgAAABR2bWhkAAAAAQAAAAAAAAAAAAAAJGRpbmYAAAAcZHJlZgAAAAAAAAABAAAADHVybCAAAAABAAAA+3N0YmwAAACXc3RzZAAAAAAAAAABAAAAh2F2YzEAAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAAAAgACAEgAAABIAAAAAAAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAY//8AAAAxYXZjQwFkAAr/4QAYZ2QACqzZX4iIhAAAAwAEAAADAFA8SJZYAQAGaOvjyyLAAAAAGHN0dHMAAAAAAAAAAQAAAAEAAAQAAAAAHHN0c2MAAAAAAAAAAQAAAAEAAAABAAAAAQAAABRzdHN6AAAAAAAAAsUAAAABAAAAFHN0Y28AAAAAAAAAAQAAADAAAABidWR0YQAAAFptZXRhAAAAAAAAACFoZGxyAAAAAAAAAABtZGlyYXBwbAAAAAAAAAAAAAAAAC1pbHN0AAAAJal0b28AAAAdZGF0YQAAAAEAAAAATGF2ZjU2LjQwLjEwMQ==";
            }
        } catch (s) {
            return void a("videoautoplay", !1);
        }
        i.setAttribute("autoplay", ""), i.style.cssText = "display:none", R.appendChild(i), 
        setTimeout(function() {
            i.addEventListener("playing", A, !1), e = setTimeout(A, t);
        }, 0);
    });
    var C = E.testStyles = s;
    Modernizr.addTest("touchevents", function() {
        var t;
        if ("ontouchstart" in A || A.DocumentTouch && e instanceof DocumentTouch) t = !0; else {
            var n = [ "@media (", F.join("touch-enabled),("), "heartz", ")", "{#modernizr{top:9px;position:absolute}}" ].join("");
            C(n, function(A) {
                t = 9 === A.offsetTop;
            });
        }
        return t;
    }), Modernizr.addTest("formvalidation", function() {
        var e = r("form");
        if (!("checkValidity" in e && "addEventListener" in e)) return !1;
        if ("reportValidity" in e) return !0;
        var t, n = !1;
        return Modernizr.formvalidationapi = !0, e.addEventListener("submit", function(e) {
            (!A.opera || A.operamini) && e.preventDefault(), e.stopPropagation();
        }, !1), e.innerHTML = '<input name="modTest" required="required" /><button></button>', 
        C("#modernizr form{position:absolute;top:-99999em}", function(A) {
            A.appendChild(e), t = e.getElementsByTagName("input")[0], t.addEventListener("invalid", function(A) {
                n = !0, A.preventDefault(), A.stopPropagation();
            }, !1), Modernizr.formvalidationmessage = !!t.validationMessage, e.getElementsByTagName("button")[0].click();
        }), n;
    });
    var x = "Moz O ms Webkit", P = E._config.usePrefixes ? x.split(" ") : [];
    E._cssomPrefixes = P;
    var Q = E._config.usePrefixes ? x.toLowerCase().split(" ") : [];
    E._domPrefixes = Q;
    var Z = {
        elem: r("modernizr")
    };
    Modernizr._q.push(function() {
        delete Z.elem;
    });
    var b = {
        style: Z.elem.style
    };
    Modernizr._q.unshift(function() {
        delete b.style;
    }), E.testAllProps = g, E.testAllProps = y, Modernizr.addTest("flexbox", y("flexBasis", "1px", !0));
    var S = function(e) {
        var n, o = F.length, i = A.CSSRule;
        if ("undefined" == typeof i) return t;
        if (!e) return !1;
        if (e = e.replace(/^@/, ""), n = e.replace(/-/g, "_").toUpperCase() + "_RULE", n in i) return "@" + e;
        for (var r = 0; o > r; r++) {
            var a = F[r], l = a.toUpperCase() + "_" + n;
            if (l in i) return "@-" + a.toLowerCase() + "-" + e;
        }
        return !1;
    };
    E.atRule = S;
    var M = E.prefixed = function(A, e, t) {
        return 0 === A.indexOf("@") ? S(A) : (-1 != A.indexOf("-") && (A = d(A)), e ? g(A, e, t) : g(A, "pfx"));
    };
    Modernizr.addTest("fullscreen", !(!M("exitFullscreen", e, !1) && !M("cancelFullScreen", e, !1))), 
    o(), i(T), delete E.addTest, delete E.addAsyncTest;
    for (var Y = 0; Y < Modernizr._q.length; Y++) Modernizr._q[Y]();
    A.Modernizr = Modernizr;
}(window, document);