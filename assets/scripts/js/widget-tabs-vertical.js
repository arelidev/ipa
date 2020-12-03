jQuery(document).ready(function ($) {
    if ( Foundation.MediaQuery.atLeast('medium') ) {
        // This is initiated on two files:
        // 1. /functions/widgets/widget-tabs-vertical-real.php
        // 2. /functions/widgets/widget-tabs-vertical.php
        const verticalTabs = '#vertical-tabs-real, #vertical-tabs-widget';
        const verticalTabsTitle = verticalTabs + ' .tabs-title';

        // todo: I'm not sure if this needs to necessarily be re-initiated
        // const elem = new Foundation.ResponsiveAccordionTabs(verticalTabs);

        $(verticalTabsTitle).on("mouseover", function () {

            // Mimic behavior of click on hover
            $(this).trigger('click')

            // This removes the hash (#) from the href ID, not sure if
            // that is needed
            const panelId = $(this).find("a").attr("href").substring(1);

            // todo: open the selected tab by the href ID above
            // $(verticalTabs).foundation('open', panelId);
        });
    }
})
