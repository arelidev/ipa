jQuery(document).ready(function ($) {

    let tabsWrapper = ".widget-full-content-tabs-wrapper",
        tabsContainer = ".widget-full-content-tab-container",
        tabsNav = "#widget-full-content-tabs";

    if ($(tabsWrapper).length) {
        $(tabsContainer).each(function () {
            $(tabsNav).append($(this).find($(".ipa-single-card-widget")));
        });

        let tabsTab = new Foundation.ResponsiveAccordionTabs($(tabsNav), {
            // todo: initialize this here (accordion large-tabs)
        });

        $(tabsNav).on('change.zf.tabs', function () {
            if (testimonialsSlider.length) {
                testimonialsSlider.slick('setPosition');
            }
        });
    }
});
