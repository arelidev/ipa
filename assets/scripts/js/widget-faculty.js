jQuery(document).ready(function ($) {
    // Mixitup filter
    let filterSelect = $('.filter-select');
    let filterInput = $('#FilterInput');
    let mixButton = $('.mixitup-control')
    let mixerContainer = '.faculty-filter-container';
    if ($(mixerContainer).length) {
        let mixer = mixitup(mixerContainer, {
            controls: {
                toggleLogic: 'and'
            }
        });

        filterSelect.on('change', function () {
            filterMix()
        });

        filterInput.on('change', function() {
            filterMix()
        })

        function filterMix() {
            let condition = ''
            if ( filterSelect.val() !== 'all' ) {
                condition += filterSelect.val()
            }
            if ( filterInput.val() !== 'all' ) {
                condition += filterInput.val()
            }

            if (condition !== '') {
                mixer.filter(condition);
            } else {
                mixer.filter('all')
            }
        }
    }

    $('.mixitup-control[data-filter=".primary-faculty"]').trigger('click')

    mixButton.on('click', function() {
        filterSelect.val('all');
        filterInput.val('')
    })

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

    // Text filter | Legacy
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
