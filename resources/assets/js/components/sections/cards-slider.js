var CardsSlider3D = new Swiper(".cards-slider-3D__container", {
    loop: false,
    direction: "horizontal",
    effect: "coverflow",
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: "auto",
    coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1
    },
    pagination: {
        el: ".swiper-pagination"
    }
});

var CardsSlider = new Swiper(".cards-slider__container", {
    loop: false,
    direction: "horizontal",
    effect: "coverflow",
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: "auto",
    coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: false
    },
    pagination: {
        el: ".swiper-pagination"
    }
});


var CardsSliderAuto = new Swiper(".cards-slider-auto__container", {
    loop: false,
    direction: "horizontal",
    effect: "coverflow",
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: "auto",
    coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: false
    },
    autoplay: {
        delay: 5000,
      },
    pagination: {
        el: ".swiper-pagination"
    }
});

