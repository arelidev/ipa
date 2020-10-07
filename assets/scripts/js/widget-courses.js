jQuery(document).ready(function ($) {
    let ipaAccordionWidget = $('.ipa-accordion-widget ');
    let ipaAccordionContent = $('.ipa-accordion-widget  .accordion-content');

    let courseFilterSelect = $('.course-filter-select');
    let courseFilterRegion = $('.course-filter-region');
    let courseFilterType = $('.course-filter-type')
    let courseFilterInstructor = $('#course-filter-instructor')
    let courseFilterDate = $('#course-filter-date')

    let courseMixerContainer = '.courses-filter-container';
    let courseParentMixerContainer = '.courses-filter-parent'
    if ($(courseMixerContainer).length) {
        var courseMixer = mixitup(courseMixerContainer);
        var parentMixer = mixitup(courseParentMixerContainer, {selectors: {
            target: '.mix-parent'
        }})

        courseFilterType.on('change', function() {
            filterMix()
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

        courseFilterDate.on('change', function() {
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

    function filterMix() {
        // Open Accordion if not already open 
        ipaAccordionWidget.foundation('down', ipaAccordionContent);

        $('.mix-parent').each( function() {
            $parentMatching = $parentMatching.add(this);
            let $subMatching = $();

            $(this).find('.mix').each(function () {
                $matching = $matching.add(this);
                $subMatching = $subMatching.add(this)

                if (courseFilterDate.val() && courseFilterDate.val() != 'all' ) {
                    let vals = $(this).attr('data-start-date').split('-')
                    let courseDate = new Date('20'+vals[2], vals[0] -1, vals[1], 0,0,0);
                    
                    let date = new Date();
                    let startDate = new Date() 
                    let endDate = new Date();
        
                    switch( courseFilterDate.val() ) {
                        case '1': // This Week
                            startDate.setDate(date.getDate() - date.getDay());
                            endDate.setDate(date.getDate() - (date.getDay() - 6));
                            break;
                        case '2': // This Month
                            startDate = new Date(date.getFullYear(), date.getMonth(), 1);
                            endDate = new Date(date.getFullYear(), date.getMonth() + 1, 0);
                            break;
                        case '3': // 60 Days
                            startDate = new Date()
                            endDate.setDate(date.getDate() + 60);
                            break;
                        case '4': // 90 Days
                            startDate = new Date()
                            endDate.setDate(date.getDate() + 90);
                            break;
                    }    

                    if (!(startDate < courseDate && courseDate < endDate)) {
                        $matching = $matching.not(this);
                        $subMatching = $subMatching.not(this);
                    }
                }

                if (courseFilterType.val() && courseFilterType.val() != 'all') {
                    let course = courseFilterType.val().toLowerCase();

                    if (!$(this).attr('data-course-type').toLowerCase().match( course )) {
                        $matching = $matching.not(this);
                        $subMatching = $subMatching.not(this);
                    }
                }

                if ( courseFilterInstructor.val() && courseFilterInstructor.val() != 'all' ) {
                    let instructor = courseFilterInstructor.val().toLowerCase();

                    if (!$(this).attr('data-primary-instructor').toLowerCase().match( instructor )) {
                        $matching = $matching.not(this);
                        $subMatching = $subMatching.not(this);
                    }
                }

                if ( courseFilterSelect.val() && courseFilterSelect.val() != 'all' ) {
                    if (!$(this).attr('data-state').match( courseFilterSelect.val() )) {
                        $matching = $matching.not(this);
                        $subMatching = $subMatching.not(this);
                    }
                }

                if ( courseFilterRegion.val() && courseFilterRegion.val() != 'all' ) {
                    if (!$(this).attr('data-region').match( courseFilterRegion.val() )) {
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

    $('#expand').on('click', function (e) {
        // Reset all Filters
        courseFilterSelect.val('all')
        courseFilterRegion.val('all')
        courseFilterInstructor.val('all')
        courseFilterDate.val('all')
        courseFilterType.val('all')

        courseMixer.filter('all')
        parentMixer.filter('all')

        ipaAccordionWidget.foundation('down', ipaAccordionContent);
    })

    $('#collapse').on('click', function () {
        ipaAccordionWidget.foundation('up', ipaAccordionContent);
    })
});
