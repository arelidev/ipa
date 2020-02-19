let testimonialsSlider = jQuery('.testimonials-widget');

// Testimonials Widget
jQuery(document).ready(function ($) {
    testimonialsSlider.slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        nextArrow: '.slick-next-custom',
        prevArrow: '.slick-prev-custom',
        dots: true,
        adaptiveHeight: true
    });

    let testimonialEqualizer = new Foundation.Equalizer(testimonialsSlider);
});

// Full Content Tabs Widget
jQuery(document).ready(function ($) {

    let tabsWrapper = ".widget-full-content-tabs-wrapper",
        tabsContainer = ".widget-full-content-tab-container",
        tabsNav = "#widget-full-content-tabs";

    if ($(tabsWrapper).length) {
        $(tabsContainer).each(function () {
            $(tabsNav).append($(this).find($(".ipa-single-card-widget")));
        });

        let tabsTab = new Foundation.Tabs($(tabsNav));

        $(tabsNav).on('change.zf.tabs', function () {
            if (testimonialsSlider.length) {
                testimonialsSlider.slick('setPosition');
            }
        });
    }
});

// Get Started CTA
jQuery(document).ready(function ($) {
    $('#footer_cta_submit').on('click', function () {
        window.location.href = $('input[name=cta_link]:checked').attr('data-link');
    })
});
