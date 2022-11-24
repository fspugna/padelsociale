function initMain($) {
    if (viewport == "desktop" || viewport == "large-mobile") {} else if (viewport == "mobile") {}
    jQuery(".hamburger").on("click", function() {
        jQuery(".header__main-menu").addClass("opened");
        blockScroll(true);
    });
    jQuery(".main-menu__close").click(function() {
        jQuery(".header__main-menu").removeClass("opened");
        blockScroll(false);
    });
}

$(window).resize($.debounce(300, initMain));

$(document).ready(function() {
    initMain();
});