jQuery(document).ready(function ($) {
    // Mixitup filter
    const targetSelector = '.mix';
    let filterSelect = $('.filter-select');
    let filterInput = $('#FilterInput');
    let mixButton = $('.mixitup-control')
    let mixerContainer = '.faculty-filter-container';

    if ($(mixerContainer).length) {
        let mixer = mixitup(mixerContainer, {
            selectors: {
                target: targetSelector
            },
            load: {
                filter: getSelectorFromHash() // Ensure that the mixer's initial filter matches the URL on startup
            },
            callbacks: {
                onMixEnd: setHash // Call the setHash() method at the end of each operation
            },
            controls: {
                toggleLogic: 'and'
            }
        });

        filterSelect.on('change', function () {
            filterMix()
        });

        filterInput.on('change', function () {
            filterMix()
        })

        // Add an "onhashchange" handler to keep the mixer in sync as the user goes
        // back and forward through their history.

        // NB: This may or may not be the desired behavior for your project. If you don't
        // want MixItUp operations to count as individual history items, simply use
        // 'replaceState()' instead of 'pushState()' within the 'setHash()' function above.
        // In which case this handler would no longer be necessary.
        window.onhashchange = function () {
            const selector = getSelectorFromHash();

            if (selector === mixer.getState().activeFilter.selector) return; // no change

            mixer.filter(selector);
        };

        function filterMix() {
            let condition = ''
            if (filterSelect.val() !== 'all') {
                condition += filterSelect.val()
            }
            if (filterInput.val() !== 'all') {
                condition += filterInput.val()
            }

            if (condition !== '') {
                mixer.filter(condition);
            } else {
                mixer.filter('all')
            }
        }
    }

    /**
     * Reads a hash from the URL (if present), and converts it into a class
     * selector string. E.g "#green" -> ".green". Otherwise, defaults
     * to the targetSelector, equivalent to "all"
     *
     * @return {string}
     */
    function getSelectorFromHash() {
        const hash = window.location.hash.replace(/^#/g, '');

        return hash ? '.' + hash : targetSelector;
    }

    /**
     * Updates the URL whenever the current filter changes.
     *
     * @param   {mixitup.State} state
     * @return  {void}
     */
    function setHash(state) {
        const selector = state.activeFilter.selector;
        const newHash = '#' + selector.replace(/^\./g, '');

        if (selector === targetSelector && window.location.hash) {
            // Equivalent to filter "all", remove the hash

            history.pushState(null, document.title, window.location.pathname); // or history.replaceState()
        } else if (newHash !== window.location.hash && selector !== targetSelector) {
            // Change the hash

            history.pushState(null, document.title, window.location.pathname + newHash); // or history.replaceState()
        }
    }

    // $('.mixitup-control[data-filter=".primary-faculty"]').trigger('click')

    mixButton.on('click', function () {
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
