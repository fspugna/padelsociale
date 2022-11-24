

function initMain($) {
    if (viewport == "desktop" || viewport == "large-mobile") {} else if (viewport == "mobile") {}
    jQuery(".hamburger").on("click", function() {
        console.log("hasClass", jQuery(".hamburger").hasClass('is-active'))
        if( jQuery(".hamburger").hasClass('is-active') ){
            jQuery(".header__main-menu").addClass("opened");
            blockScroll(false);
        }else{
            jQuery(".header__main-menu").removeClass("opened");
            blockScroll(true);
        }

    });
    /*
    jQuery(".main-menu__close").click(function() {
        jQuery(".header__main-menu").removeClass("opened");
        blockScroll(false);
    });
    */
}

$(window).resize($.debounce(300, initMain));

$(document).ready(function() {
    initMain();
});
