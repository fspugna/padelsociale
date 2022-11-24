window.rogio = window.rogio || {
    log: function(message, severity, force) {
        if (!message) {
            return false;
        }
        var defaults = [ "display: block", "text-align: center", "padding: 5px", "margin: 3px" ];
        switch (severity) {
          case "require":
            Array.prototype.push.apply(defaults, [ "background: #29A4E5", "color: black" ]);
            break;

          case "success":
            Array.prototype.push.apply(defaults, [ "background: #00d445", "color: white" ]);
            break;

          case "warning":
            Array.prototype.push.apply(defaults, [ "background: #ffb500", "color: black" ]);
            break;

          case "failure":
            Array.prototype.push.apply(defaults, [ "background: red", "color: white" ]);
            break;

          case "timer":
            Array.prototype.push.apply(defaults, [ "background: #e033d8", "color: white" ]);
            break;
        }
        var logStyles = defaults.join(";");
        if (/[?&]eDebug/.test(location.search) || force) {
            console.log("%c" + message, logStyles);
            return true;
        }
        return false;
    }
};

rogio.log("Functions.js caricato", "success");

(function(b, c) {
    var $ = b.jQuery || b.Cowboy || (b.Cowboy = {}), a;
    $.throttle = a = function(e, f, j, i) {
        var h, d = 0;
        if (typeof f !== "boolean") {
            i = j;
            j = f;
            f = c;
        }
        function g() {
            var o = this, m = +new Date() - d, n = arguments;
            function l() {
                d = +new Date();
                j.apply(o, n);
            }
            function k() {
                h = c;
            }
            if (i && !h) {
                l();
            }
            h && clearTimeout(h);
            if (i === c && m > e) {
                l();
            } else {
                if (f !== true) {
                    h = setTimeout(i ? k : l, i === c ? e - m : e);
                }
            }
        }
        if ($.guid) {
            g.guid = j.guid = j.guid || $.guid++;
        }
        return g;
    };
    $.debounce = function(d, e, f) {
        return f === c ? a(d, e, false) : a(d, f, e !== false);
    };
})(this);

var breakpoint = "736";

var appInit = true;

var launchScript = true;

var viewport = "unknown";

var viewportChanged = "unknown";

var dataMediaParams = "";

var tribooScriptModules = window.tribooScriptModules || undefined;

$(window).resize($.debounce(300, checkSizes));

$(document).ready(function() {
    checkSizes();
});

function checkSizes() {
    if ($(window).width() <= breakpoint && bowser.mobile) {
        viewport = "mobile";
    } else if ($(window).width() > breakpoint && (bowser.mobile || bowser.tablet)) {
        viewport = "large-mobile";
    } else if ($(window).width() > breakpoint) {
        viewport = "desktop";
    }
    rogio.log("VIEWPORT: " + viewport, "require");
    $("body").addClass(viewport);
    var libraryElements = $("*").filter(function() {
        return $(this).data("library") !== undefined;
    });
    libraryElements.each(function() {
        var str = $(this).data("media-query");
        dataMediaParams = str.split(",");
        if (dataMediaParams.indexOf(viewport) >= 0 || $(this).data("media-query") == undefined) {
            $(this).addClass($(this).data("library"));
            rogio.log('aggiunta classe "' + $(this).data("library") + "\" all'elemento #" + $(this).attr("id"));
        } else {
            rogio.log('la classe "' + $(this).data("library") + "\" non è stata aggiunta all'elemento #" + $(this).attr("id") + " perché non richiesta dalla viewport");
        }
    });
    modulesLoader();
}

String.prototype.allReplace = function(obj) {
    var retStr = this;
    for (var x in obj) {
        retStr = retStr.replace(new RegExp(x, "g"), obj[x]);
    }
    return retStr;
};

$.fn.hitTest = function(x, y) {
    var bounds = this.offset();
    bounds.right = bounds.left + this.outerWidth();
    bounds.bottom = bounds.top + this.outerHeight();
    if (x >= bounds.left) {
        if (x <= bounds.right) {
            if (y >= bounds.top) {
                if (y <= bounds.bottom) {
                    return true;
                }
            }
        }
    }
    return false;
};

jQuery.fn.Mutation = function(attribute, value, Callback, startConditionValue) {
    var tag = this[0].tagName;
    var elementHasClass = startConditionValue;
    var classList = "";
    var classListArray = [];
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            classList = String(jQuery(mutation.target).prop(mutation.attributeName));
            classListArray = classList.split(" ");
            if (mutation.attributeName === attribute && classListArray.indexOf(value) !== -1 && !elementHasClass) {
                rogio.log("Added " + attribute + " " + value + " on " + tag, "warning", true);
                window[Callback]();
                elementHasClass = !startConditionValue;
                observer.disconnect();
            }
        });
    });
    observer.observe(this[0], {
        attributes: true
    });
};

$.fn.isInViewport = function() {
    var elementTop = $(this).offset().top;
    var elementBottom = elementTop + $(this).outerHeight();
    var viewportTop = $(window).scrollTop();
    var viewportBottom = viewportTop + $(window).height();
    return elementBottom < viewportBottom;
};

$("html").attr("data-scroll-position", "0,0");

function blockScroll(value) {
    var html = jQuery("html");
    if (value) {
        if (!html.is(".scrollBlocked")) {
            rogio.log("view scrolling blocked!");
            var scrollPosition = [ self.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft, self.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop ];
            html.attr("data-scroll-position", scrollPosition);
            html.attr("data-previous-overflow", "auto");
            html.css({
                height: "100%",
                overflow: "hidden",
                maxHeight: "100%",
                position: "fixed",
                top: -scrollPosition[1],
                width: "100%"
            });
            html.addClass("scrollBlocked");
        }
    } else {
        rogio.log("view scrolling allowed!");
        var html = jQuery("html");
        var scrollPosition = html.attr("data-scroll-position");
        var top = scrollPosition.split(",");
        html.css("overflow", html.attr("data-previous-overflow"));
        html.css({
            position: "static",
            top: 0,
            height: "auto",
            overflow: html.attr("data-previous-overflow")
        });
        jQuery(window).scrollTop(top[1]);
        html.removeClass("scrollBlocked");
    }
}

function showLoading(loadingTop) {
    $("#screen-overlay").addClass("active");
    $("#loading").attr("style", "top:" + loadingTop + "px");
}

function hideLoading() {
    $("#screen-overlay").removeClass("active");
}

function positionBackgroundMove(el, backPosYStart, factor) {
    scroll = eval(backPosYStart - $(window).scrollTop() / factor);
    el.attr("style", "background-position-y: " + scroll + "px");
}


jQuery(document).ready(function(){
    console.log("functions.js", $("#user-logo").length);

    localStorage.clear();

    if ( $('[type="date"]').prop('type') != 'date' ) {
        document.querySelectorAll('.match_date').type = 'text';
        document.querySelectorAll('.match_hours').type = 'text';
    }

    jQuery("#reset_btn").click(function(){
    var email = $('#email_reset').val();
    var base_url = "https://www.ladisfidadipadel.it";
    jQuery.ajax({
        url: base_url+ '/api/password/create',
        data: {
            email: email
        },
        type: 'POST',
        beforeSend: function(request) {
            request.setRequestHeader("Access-Control-Allow-Headers", '*');
        },
        success: function(data){
            if(data.message !== ''){

                jQuery("#message").html('<br><div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-check"></i> Ok!</h4>'+data.message+'<br><a href="/">Ritorna alla Homepage</a></div>');

            }else{

                jQuery("#message").html('<br><div class="alert alert-error alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-check"></i> Errore</h4>Si è verificato un errore. Riprova più tardi.<br><a href="/">Ritorna alla Homepage</a></div>');

            }

            jQuery("#message").removeClass("hidden");
            jQuery(".reset-form").addClass("hidden");
        }
    })
});
})
