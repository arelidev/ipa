jQuery(document).ready(function ($) {
    let testimonialsSlider = $('.testimonials-slider');

    testimonialsSlider.slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        nextArrow: '.slick-next-custom-testimonials-widget',
        prevArrow: '.slick-prev-custom-testimonials-widget',
        dots: false,
        adaptiveHeight: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                }
            },
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 400,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            }
        ],
    });

    let testimonialEqualizer = new Foundation.Equalizer(testimonialsSlider);
});
