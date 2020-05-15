jQuery(document).ready(function ($) {
    // Mixitup filter
    let filterSelect = $('#FilterSelect');
    let mixerContainer = '.faculty-filter-container';
    if ($(mixerContainer).length) {
        let mixer = mixitup(mixerContainer);

        filterSelect.on('change', function () {
            mixer.filter();
        });
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

    // Text filter
    $("#FilterInput").keyup(function () {
        // Delay function invoked to make sure user stopped typing
        delay(function () {
            inputText = $("#FilterInput").val().replace(/\s+/g, '-').toLowerCase();
            let mixer = mixitup(mixerContainer);

            // Check to see if input field is empty
            if ((inputText.length) > 0) {
                $('.mix').each(function () {
                    // add item to be filtered out if input text matches items inside the title
                    if ($(this).attr('data-title').toLowerCase().match(inputText)) {
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
    });
});
