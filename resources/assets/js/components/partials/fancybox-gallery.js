$(document).ready(function() {
console.log("FANCIBOX GALLERY LOADED");

$(".fotogallery").fancybox({
    'zoomSpeedIn': 300,
    'zoomSpeedOut': 300,
    'overlayShow': false
  });     
    
    /*
    var galleryArray = window.galleryArray;
    var myArray;
    $(".fotogallery").each(function(index) {
        console.log(index);
        var match = $(this).data('match');
        var trovato = false;
        galleryArray.forEach(function(obj, event) {
            console.log(obj["match"], match);
            if (obj["match"] == match) {
                trovato = true;
            }
        });
        console.log(trovato);
        if (!trovato) $(this).remove();
    });
   $(".fotogallery").on('click', function() {
    var match = $(this).data('match');
    myArray = [];
    galleryArray.forEach(function(obj, event) {
        if (obj["match"] == match) {
            myArray.push(obj);
        }
    });

   console.log(index);
        $.fancybox.open(
            myArray, {
                loop: true,
                thumbs: {
                    autoStart: true
                }
            });

    });
    */


    $(".tabs__nav a, .lazyloaded").click(function(e) {
        e.preventDefault();
        localStorage.setItem("scrollTop", $(".base > .wrapper").scrollTop());
        localStorage.setItem("scrollLeftZone", $(".tabs-scroller-zones").scrollLeft());
        localStorage.setItem("scrollLeftCategoryType", $(".tabs-scroller-categoryType").scrollLeft());
        localStorage.setItem("scrollLeftCategory", $(".tabs-scroller-category").scrollLeft());
        localStorage.setItem("scrollLeftGroups", $(".tabs-scroller-groups").scrollLeft());
        var obj = this;
        setTimeout(function() {
            if (typeof($(obj).attr("href")) != "undefined"){
                self.location.href = $(obj).attr("href");
            }
        }, 100);
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
});