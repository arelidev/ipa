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
        let input = $(this).val();
        let field = 'data-type';
        filterByText(input, field)
    });

    stateFilter.on('change', function () {
        let input = $(this).val().replace(/\s+/g, '-').toLowerCase();
        let field = 'data-state'
        filterByText(input, field)
    });

    zipFilter.keyup(function () {
        let input = $(this).val().replace(/\s+/g, '-').toLowerCase();
        let field = 'data-zip'
        filterByText(input, field)
    });

    certFilter.on('change', function () {
        let input = $(this).val().toLowerCase();
        let field = 'data-certification'
        filterByText(input, field)
    });

    instructorFilter.keyup(function () {
        let input = $(this).val().replace(/\s+/g, '-').toLowerCase();
        let field = 'data-title'
        filterByText(input, field)
    });

    // Text Filter Function
    function filterByText(input, field) {
        // Delay function invoked to make sure user stopped typing
        delay(function () {
            let mixer = mixitup(mixerContainer);

            // Check to see if input field is empty
            if ((input.length) > 0) {
                $('.mix').each(function () {
                    // add item to be filtered out if input text matches items inside the title
                    if ($(this).attr(field) && $(this).attr(field).toLowerCase().match(input)) {
                        $matching = $matching.add(this);
                    } else {
                        // removes any previously matched item
                        $matching = $matching.not(this);
                    }
                });

                // $(mixerContainer).mixItUp('filter', $matching);
                mixer.filter($matching);
            } else {
                // resets the filter to show all item if input is empty
                // $(mixerContainer).mixItUp('filter', 'all');
                mixer.filter('all');
            }
        }, 200);
    }
});
