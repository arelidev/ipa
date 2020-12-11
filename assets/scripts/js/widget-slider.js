jQuery(document).ready(function ($) {
    $('.slider-widget-slider').slick({
        infinite: true,
        slidesToShow: 5,
        nextArrow: '.slick-next-custom-slider-widget',
        prevArrow: '.slick-prev-custom-slider-widget',
        adaptiveHeight: true,
        dots: false,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 5,
                    adaptiveHeight: true,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    adaptiveHeight: false,
                }
            },
            {
                breakpoint: 300,
                settings: {
                    slidesToShow: 1,
                    adaptiveHeight: false,
                }
            }
        ],
    });
})
