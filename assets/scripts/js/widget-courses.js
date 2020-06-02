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
                    scrollTop: $( this.value ).offset().top,
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

    $("#course-filter-instructor").keyup(function () {
        // Delay function invoked to make sure user stopped typing
        delay(function () {
            inputText = $("#course-filter-instructor").val().replace(/\s+/g, '-').toLowerCase();
            let courseMixer = mixitup(courseMixerContainer);

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
