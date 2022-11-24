var TrendsSlider = new Swiper(".trends-section__container", {
    loop: false,
    direction: "horizontal",
    slidesPerView: "auto",
    spaceBetween: 10,
    freeMode: true,
    grabCursor: true,
    navigation: {
        nextEl: ".trendsSlider__next",
        prevEl: ".trendsSlider__prev"
    }
});