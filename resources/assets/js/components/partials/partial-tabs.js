<<<<<<< HEAD
$(".tabs__nav a, .card__thumbnail").click(function(e) {
    e.preventDefault();
    localStorage.setItem("scrollTop", $(".base > .wrapper").scrollTop());
    localStorage.setItem("scrollLeftZone", $(".tabs-scroller-zones").scrollLeft());
    localStorage.setItem("scrollLeftCategoryType", $(".tabs-scroller-categoryType").scrollLeft());
    localStorage.setItem("scrollLeftCategory", $(".tabs-scroller-category").scrollLeft());
    localStorage.setItem("scrollLeftGroups", $(".tabs-scroller-groups").scrollLeft());
    self.location.href = $(this).attr("href");
});

$(".base > .wrapper").animate({
    scrollTop: localStorage.getItem("scrollTop") - 20
}, {
    easing: "swing",
    duration: 0
});

if ($(".tabs-scroller-zones").length) {
    $(".tabs-scroller-zones").animate({
        scrollLeft: localStorage.getItem("scrollLeftZone")
    }, {
        easing: "swing",
        duration: 0
    });
}

if ($(".tabs-scroller-categoryType").length) {
    $(".tabs-scroller-categoryType").animate({
        scrollLeft: localStorage.getItem("scrollLeftCategoryType")
    }, {
        easing: "swing",
        duration: 0
    });
}

if ($(".tabs-scroller-category").length) {
    $(".tabs-scroller-category").animate({
        scrollLeft: localStorage.getItem("scrollLeftCategory")
    }, {
        easing: "swing",
        duration: 0
    });
}

if ($(".tabs-scroller-groups").length) {
    $(".tabs-scroller-groups").animate({
        scrollLeft: localStorage.getItem("scrollLeftGroups")
    }, {
        easing: "swing",
        duration: 0
    });
}
=======
$(".tabs-container__content").outerHeight($(".content__item.active").height());

$(".tabs-container__nav .nav__item").click(function() {
    var panel = $(this).data("tab");
    $(".tabs-container__content").outerHeight($(".tabs-container__content .content__item[data-tab-panel=" + panel + "]").outerHeight());
    $(this).addClass("active").siblings(".tabs-container__nav .nav__item").removeClass("active");
    $(".tabs-container__content .content__item[data-tab-panel=" + panel + "]").addClass("active").siblings(".tabs-container__content .content__item").removeClass("active");
});

>>>>>>> 87dfc5a012ba0f116f6147c0a422529ce6f44622
