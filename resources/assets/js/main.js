function onViewportChange() {
    if (viewport == "desktop") {
        initChosenSelects();
        if ($(".sticky-element").length) {
            $(".sticky-element").each(function(index) {
                $(this).after('<div class="sticky-content-spacer-' + index + '"></div>');
                $(this).stick_in_parent({
                    offset_top: $(this).closest(".sticky-offset-element").data("sticky-offset-top"),
                    inner_scrolling: false,
                    parent: $(this).closest(".sticky-parent"),
                    spacer: ".sticky-content-spacer-" + index
                }).on("sticky_kit:bottom", function(e) {
                    $(this).addClass("at_bottom");
                }).on("sticky_kit:unbottom", function(e) {
                    $(this).removeClass("at_bottom");
                });
            });
        }
    }
    if (viewport == "large-mobile") {}
    if (viewport == "mobile") {}
}

function initChosenSelects() {
    if ($(".chosen-select").length) {
        $(".chosen-select").chosen("destroy");
        $(".chosen-select").each(function() {
            $(this).next(".chosen-container").remove();
            $(this).chosen({
                width: "150px",
                disable_search_threshold: 10
            });
            $(this).find("option[value=" + $(this).data("tabindex") + "]").attr("selected", true);
            $(".chosen-select").trigger("chosen:updated");
        });
    }
}

$(window).resize($.debounce(300, onViewportChange));

$(document).ready(function() {
    
    onViewportChange();
    
});

$(".back-to-top-btn").click(function(e) {
    $("html,body").animate({
        scrollTop: 0
    }, {
        easing: "swing",
        duration: 600
    });
    e.preventDefault();
});

jQuery(document).on("click", "[data-link]", function(e) {
    var link = jQuery(e.currentTarget).data("link");
    var target = jQuery(e.currentTarget).data("target");
    if (target != undefined) {
        window.open(link, target);
    } else {
        window.location.href = link;
    }
});

var goToElements = jQuery("*").filter(function() {
    return jQuery(this).data("anchor") !== undefined;
});

goToElements.click(function(e) {
    var selector = jQuery(this).data("anchor");
    var $target = jQuery(selector).offset().top;
    $("html,body").animate({
        scrollTop: $target - 20
    }, {
        easing: "swing",
        duration: 600
    });
    e.preventDefault();
});

jQuery('button[type="submit"]').click(function() {
    var stickyHeaderHeight = 80;
    var elements = jQuery(this).closest("form").find("input, select");
    elements.each(function() {
        if ($(this).is(":invalid")) {
            var $target = $(this).offset().top;
            $("html,body").animate({
                scrollTop: $target - stickyHeaderHeight - 10
            }, {
                easing: "swing",
                duration: 600
            });
            return false;
        }
    });
});