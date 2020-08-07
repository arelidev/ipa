jQuery(document).ready(function ($) {
    let ipaAccordionWidget = $('.ipa-accordion-widget ');
    let ipaAccordionContent = $('.ipa-accordion-widget  .accordion-content');
    let startDate, endDate = '';

    let courseFilterSelect = $('.course-filter-select');
    let courseFilterRegion = $('.course-filter-region');
    let courseFilterInstructor = $('#course-filter-instructor')
    let courseMixerContainer = '.courses-filter-container';
    if ($(courseMixerContainer).length) {
        let courseMixer = mixitup(courseMixerContainer);

        courseFilterSelect.on('change', function () {
            filterMix()
        });


        courseFilterRegion.on('change', function () {
            filterMix()
        });

        courseFilterInstructor.on('change', function () {
            filterMix()
        })    

        $('.scroll-to').on('change', function () {
            $('html, body').animate({
                scrollTop: $(this.value).offset().top - 100,
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
                startDate = date1;
                endDate = date2;
                filterMix()
            }
        })
    }

    // Clear the mix when the user clears the input
    $(document).on('click', '.reset-button', function () {
        startDate,endDate = '';
        filterMix()
    })


    function filterMix() {
        let courseMixer = mixitup(courseMixerContainer);

        // Open Accordion if not already open 
        ipaAccordionWidget.foundation('down', ipaAccordionContent);

        $('.mix').each(function () {
            $matching = $matching.add(this);

            if (startDate && endDate) {
                let courseDate = new Date($(this).attr('data-start-date'))

                if (!(startDate < courseDate && courseDate < endDate)) {
                    $matching = $matching.not(this);
                }
            }

            if ( courseFilterInstructor.val() && courseFilterInstructor.val() != 'all' ) {
                let instructor = courseFilterInstructor.val().toLowerCase();

                if (!$(this).attr('data-primary-instructor').toLowerCase().match( instructor )) {
                    $matching = $matching.not(this);
                }
            }

            if ( courseFilterSelect.val() && courseFilterSelect.val() != 'all' ) {
                if (!$(this).attr('data-state').match( courseFilterSelect.val() )) {
                    $matching = $matching.not(this);
                }
            }

            if ( courseFilterRegion.val() && courseFilterRegion.val() != 'all' ) {
                if (!$(this).attr('data-region').match( courseFilterRegion.val() )) {
                    $matching = $matching.not(this);
                }
            }
        });

        courseMixer.filter($matching);

    }

    $('#expand').on('click', function (e) {
        // e.preventDefault();
        ipaAccordionWidget.foundation('down', ipaAccordionContent);
    })

    $('#collapse').on('click', function () {
        // e.preventDefault();
        ipaAccordionWidget.foundation('up', ipaAccordionContent);
    })
});
