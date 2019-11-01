jQuery(document).ready(function ($) {
    $('.testimonials-widget').slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        nextArrow: '.slick-next-custom',
        prevArrow: '.slick-prev-custom',
        dots: true,
        adaptiveHeight: true
    });
});