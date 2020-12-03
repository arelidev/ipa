jQuery(document).ready(function ($) {

    let tabsWrapper = ".widget-full-content-tabs-wrapper",
        tabsContainer = ".widget-full-content-tab-container",
        tabsNav = "#widget-full-content-tabs";

    if ($(tabsWrapper).length) {

        $(tabsNav).on('change.zf.tabs', function () {
            if (testimonialsSlider.length) {
                testimonialsSlider.slick('setPosition');
            }
        });
    }
});
