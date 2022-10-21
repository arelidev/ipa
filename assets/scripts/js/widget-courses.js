jQuery(document).ready(function ($) {
    let ipaAccordionWidget = $('.ipa-accordion-widget ');
    let ipaAccordionContent = $('.ipa-accordion-widget  .accordion-content');

    let courseFilterSelect = $('.course-filter-select');
    let courseFilterRegion = $('.course-filter-region');
    let courseFilterType = $('.course-filter-type')
    let courseFilterInstructor = $('#course-filter-instructor')
    let courseFilterDate = $('#course-filter-date')
    let startDate, endDate = ' ';

    let courseMixerContainer = '.courses-filter-container';
    let courseParentMixerContainer = '.courses-filter-parent'
    if ($(courseMixerContainer).length) {
        var courseMixer = mixitup(courseMixerContainer, {
            selectors: {
                control: '[data-mixitup-control]'
            }
        });
        var parentMixer = mixitup(courseParentMixerContainer, {
            selectors: {
                target: '.mix-parent',
                control: '[data-mixitup-control]'
            }
        })

        courseFilterSelect.on('change', function () {
            filterMix()
        });

        courseFilterRegion.on('change', function () {
            filterMix()
        });

        courseFilterInstructor.on('change', function () {
            filterMix()
        })

        courseFilterDate.on('change', function () {
            filterMix()
        })

        let begin = new Date();
        let end = new Date();
        let ranges = {
            'This Week': thisWeek(begin),
            'This Month': thisMonth(begin),
            'Next 60 Days': [begin, addDays(end, 60)],
            'Next 90 Days': [begin, addDays(end, 90)],
        };

        // Initialize Date Range Picker
        if (typeof Litepicker !== 'undefined') {
            new Litepicker({
                element: document.getElementById('course-filter-date'),
                singleMode: false,
                firstDay: 0,
                numberOfColumns: 2,
                numberOfMonths: 2,
                moveByOneMonth: true,
                moduleRanges: {position: 'left', ranges},
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
            startDate, endDate = '';
            filterMix()
        })


        $('.scroll-to').on('change', function () {
            ipaAccordionWidget.foundation('down', $(this.value).find('.accordion-content'));
            $('html, body').animate({
                    scrollTop: $(this.value).offset().top - 200,
                }, 500, 'linear'
            )
        })
    }

    let inputText;
    let $matching = $();
    let $parentMatching = $();

    let delay = (function () {
        let timer = 0;
        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

    courseFilterType.on('change', function (event) {
        const loc = "#" + event.target.value;
        Foundation.SmoothScroll.scrollToLoc(loc);
    })

    function filterMix() {
        // Open Accordion if not already open
        ipaAccordionWidget.foundation('down', ipaAccordionContent);

        $('.mix-parent').each(function () {
            $parentMatching = $parentMatching.add(this);
            let $subMatching = $();

            $(this).find('.mix').each(function () {
                $matching = $matching.add(this);
                $subMatching = $subMatching.add(this)

                if (startDate && endDate) {
                    let courseDate = new Date($(this).attr('data-start-date'))
                    if (!(startDate <= courseDate && courseDate <= endDate)) {
                        $matching = $matching.not(this);
                        $subMatching = $subMatching.not(this);
                    }
                }

                if (courseFilterInstructor.val() && courseFilterInstructor.val() != 'all') {
                    let instructor = courseFilterInstructor.val().toLowerCase();

                    if (!$(this).attr('data-primary-instructor').toLowerCase().match(instructor)) {
                        $matching = $matching.not(this);
                        $subMatching = $subMatching.not(this);
                    }
                }

                if (courseFilterSelect.val() && courseFilterSelect.val() != 'all') {
                    if (!$(this).attr('data-state').match(courseFilterSelect.val())) {
                        $matching = $matching.not(this);
                        $subMatching = $subMatching.not(this);
                    }
                }

                if (courseFilterRegion.val() && courseFilterRegion.val() != 'all') {
                    if (!$(this).attr('data-region').match(courseFilterRegion.val())) {
                        $matching = $matching.not(this);
                        $subMatching = $subMatching.not(this);
                    }
                }
            });

            if ($subMatching.length == 0) {
                $parentMatching = $parentMatching.not(this);
            }
        })
        courseMixer.filter($matching);
        parentMixer.filter($parentMatching);
    }

    function addDays(date, num) {
        const d = new Date(date);
        d.setDate(d.getDate() + num);

        return d;
    };

    function thisMonth(date) {
        const d1 = new Date(date);
        d1.setDate(1);
        const d2 = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        return [d1, d2];
    };

    function thisWeek(date) {
        const d1 = new Date(date);
        d1.setDate(date.getDate() - date.getDay());
        const d2 = new Date(date)
        d2.setDate(date.getDate() - (date.getDay() - 6));
        return [d1, d2];
    }

    $('.expand').on('click', function (e) {
        // Reset all Filters
        courseFilterSelect.val('all')
        courseFilterRegion.val('all')
        courseFilterInstructor.val('all')
        courseFilterDate.val('all')

        courseMixer.filter('all')
        parentMixer.filter('all')

        ipaAccordionWidget.foundation('down', ipaAccordionContent);
    })

    $('.collapse').on('click', function () {
        ipaAccordionWidget.foundation('up', ipaAccordionContent);
    })
});
