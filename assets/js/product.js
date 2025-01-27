new Swiper('.products-other .swiper-container', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,

    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    breakpoints: {
        450: {
            slidesPerView: 2,
        },
        770: {
            slidesPerView: 4,
        },
        930: {
            slidesPerView: 5,
        }
    }
});