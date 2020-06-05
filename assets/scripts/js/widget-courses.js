jQuery(document).ready(function ($) {
    let ipaAccordionWidget = $('.ipa-accordion-widget ');
    let ipaAccordionContent = $('.ipa-accordion-widget  .accordion-content');

    let courseFilterSelect = $('.course-filter-select');
    let courseMixerContainer = '.courses-filter-container';
    if ($(courseMixerContainer).length) {
        let courseMixer = mixitup(courseMixerContainer);

        courseFilterSelect.on('change', function () {
            courseMixer.filter(this.value);

            ipaAccordionWidget.foundation('down', ipaAccordionContent);
        });

        $('.scroll-to').on('change', function () {
            $('html, body').animate({
                scrollTop: $(this.value).offset().top,
            }, 500, 'linear'
            )
        })
    }

    let inputText;
    let $matching = $();

    let delay = (function () {
        let timer = 0;
        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

    // Initialize Date Range Picker
    if (typeof Litepicker !== 'undefined') {
        new Litepicker({
            element: document.getElementById('course-filter-date'),
            singleMode: false,
            numberOfColumns: 2,
            numberOfMonths: 2,
            moveByOneMonth: true,
            moduleRanges: true,
            useResetBtn: true,
            onSelect: function (date1, date2) {
                filterByDate(date1, date2)
            }
        })
    }

    // Clear the mix when the user clears the input
    $(document).on('click', '.reset-button', function () {
        filterByDate()
    })

    function filterByDate(startDate, endDate) {
        let courseMixer = mixitup(courseMixerContainer);

        // Open Accordion if not already open 
        ipaAccordionWidget.foundation('down', ipaAccordionContent);

        // Check to see if input field is empty
        if (startDate && endDate) {
            $('.mix').each(function () {
                // add item to be filtered out if input text matches items inside the title
                let courseDate = new Date($(this).attr('data-start-date'))

                // Check if course start date is between the selected date range
                if (startDate < courseDate && courseDate < endDate) {
                    $matching = $matching.add(this);
                } else {
                    // removes any previously matched item
                    $matching = $matching.not(this);
                }
            });

            // $(courseMixerContainer).mixItUp('filter', $matching);
            courseMixer.filter($matching);
        } else {
            // resets the filter to show all item if input is empty
            // $(courseMixerContainer).mixItUp('filter', 'all');
            courseMixer.filter('all');
        }
    }

    $("#course-filter-instructor").keyup(function () {
        // Delay function invoked to make sure user stopped typing
        delay(function () {
            inputText = $("#course-filter-instructor").val().replace(/\s+/g, '-').toLowerCase();
            let courseMixer = mixitup(courseMixerContainer);

            // Open Accordion if not already open 
            ipaAccordionWidget.foundation('down', ipaAccordionContent);

            // Check to see if input field is empty
            if ((inputText.length) > 0) {
                $('.mix').each(function () {
                    // add item to be filtered out if input text matches items inside the title
                    if ($(this).attr('data-primary-instructor').toLowerCase().match(inputText)) {
                        $matching = $matching.add(this);
                    } else {
                        // removes any previously matched item
                        $matching = $matching.not(this);
                    }
                });

                // $(courseMixerContainer).mixItUp('filter', $matching);
                courseMixer.filter($matching);
            } else {
                // resets the filter to show all item if input is empty
                // $(courseMixerContainer).mixItUp('filter', 'all');
                courseMixer.filter('all');
            }
        }, 200);
    });

    $('#expand').on('click', function (e) {
        // e.preventDefault();
        ipaAccordionWidget.foundation('down', ipaAccordionContent);
    })

    $('#collapse').on('click', function () {
        // e.preventDefault();
        ipaAccordionWidget.foundation('up', ipaAccordionContent);
    })
});
