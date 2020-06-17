jQuery(document).ready(function ($) {
    // Mixitup filter
    let filterSelect = $('#clinics-filter-select');
    let stateFilter = $('#clinics-filter-state');
    let zipFilter = $('#clinics-filter-zip')
    let certFilter = $('#clinics-filter-certification')
    let instructorFilter = $('#clinics-filter-instructor')
    let mixerContainer = '.ipa-clinic-card-wrapper';

    if ($(mixerContainer).length) {
        let mixer = mixitup(mixerContainer);
    }

    let inputText;
    let $matching = $();

    // Delay function
    let delay = (function () {
        let timer = 0;
        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

    // Filter Bindings
    filterSelect.on('change', function () {
        filterMix()
    });

    stateFilter.on('change', function () {
        filterMix()
    });

    zipFilter.keyup(function () {
        filterMix()
    });

    certFilter.on('change', function () {
        filterMix()
    });

    instructorFilter.on('change', function () {
        filterMix()
    });

    // Filter function
    function filterMix(input, field) {

        // Delay function invoked to make sure user stopped typing
        delay(function () {
            let mixer = mixitup(mixerContainer);

            $('.mix').each(function () {
                $matching = $matching.add(this);

                if ( filterSelect.val() && filterSelect.val() != 'all' ) {
                    if (!$(this).attr('data-type') || !$(this).attr('data-type').match( filterSelect.val().toLowerCase() )) {
                        $matching = $matching.not(this);
                    }
                }

                if ( stateFilter.val() && stateFilter.val() != 'all' ) {
                    if (!$(this).attr('data-state') || !$(this).attr('data-state').match( stateFilter.val() )) {
                        $matching = $matching.not(this);
                    }
                }

                if ( zipFilter.val() && zipFilter.val() != '' ) {
                    if (!$(this).attr('data-zip') || !$(this).attr('data-zip').match( zipFilter.val().replace(/\s+/g, '-').toLowerCase() )) {
                        $matching = $matching.not(this);
                    }
                }

                if ( certFilter.val() && certFilter.val() != 'all' ) {
                    if (!$(this).attr('data-certification') || !$(this).attr('data-certification').match( certFilter.val() )) {
                        $matching = $matching.not(this);
                    }
                }

                if ( instructorFilter.val() && instructorFilter.val() != 'all' ) {
                    if (!$(this).attr('data-title') || !$(this).attr('data-title').match( instructorFilter.val() )) {
                        $matching = $matching.not(this);
                    }
                }
            });
            mixer.filter($matching);

        }, 200);
    }
});
