jQuery(document).ready(function ($) {
    // todo: I'm not sure this is the best way to validate this
    if ( jQuery('body').hasClass('page-template-template-course') ) {
        const courseMagellan = new Foundation.Magellan($('#course-magellan'), {
            offset: 80,
            threshold: 100
        });
    }
})
