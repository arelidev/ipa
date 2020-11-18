jQuery(document).ready(function ($) {
    $('.full-slider').slick({
        dots: false,
        arrows: true,
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 5000,
        nextArrow: '.slick-next-custom-full-slider',
        prevArrow: '.slick-prev-custom-full-slider',
    }).on('setPosition', function (event, slick) {
        $('.single-full-slide').css('height', slick.$slideTrack.height() + 'px');
    }).on('afterChange', function (slick, currentSlide) {
        const dataId = $('.slick-current').attr("data-slick-index");
        const slideNavButton = '.slide-navigation-button';
        jQuery(slideNavButton).removeClass('is-active');
        jQuery(slideNavButton + '[data-slick-index="' + dataId + '"]').addClass('is-active')
    })
})

jQuery(".slide-navigation-button").on('click', function (e) {
    e.preventDefault();
    const slideno = jQuery(this).data('slick-index');
    jQuery('.full-slider').slick('slickGoTo', slideno);
})
