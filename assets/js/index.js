new Swiper(".recent-keys", {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,

    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    breakpoints: {
        630: {
            slidesPerView: 2,
        },
        930: {
            slidesPerView: 3,
        }
    }
});