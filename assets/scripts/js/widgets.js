let testimonialsSlider = jQuery('.testimonials-slider');

// Testimonials Widget
jQuery(document).ready(function ($) {
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
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    adaptiveHeight: false,
                }
            },
            {
                breakpoint: 400,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    adaptiveHeight: false,
                }
            }
        ],
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

        let tabsTab = new Foundation.ResponsiveAccordionTabs($(tabsNav));

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

// Slider Widget
jQuery(document).ready(function ($) {
    $('.slider-widget-slider').slick({
        infinite: true,
        slidesToShow: 4,
        nextArrow: '.slick-next-custom-slider-widget',
        prevArrow: '.slick-prev-custom-slider-widget',
        adaptiveHeight: true,
        dots: false,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 4,
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

// Single Course Gallery Slider
jQuery(document).ready(function ($) {
    $('.course-gallery').slick({
        dots: true,
        nextArrow: '.slick-next-custom-single-course',
        prevArrow: '.slick-prev-custom-single-course',
    })
})

// Full Slider
jQuery(document).ready(function ($) {
    $('.full-slider').slick({
        dots: false,
        arrows: true,
        infinite: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        nextArrow: '.slick-next-custom-full-slider',
        prevArrow: '.slick-prev-custom-full-slider',
    }).on('setPosition', function (event, slick) {
        $('.single-full-slide').css('height', slick.$slideTrack.height() + 'px');
    })
})

jQuery(".slide-navigation-button").on('click', function (e) {
    e.preventDefault();
    const slideno = jQuery(this).data('slick-index');
    jQuery('.full-slider').slick('slickGoTo', slideno);
})

jQuery(document).ready(function ($) {
    $('.page-template-template-course .page-title').each(function() {
        $(this).html($(this).text().replace(/:.*$/, '<span class="after">$&</span>'));
    });
})
