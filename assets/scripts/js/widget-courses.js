jQuery(document).ready(function ($) {
    const courseMixerContainer = $(".ipa-courses-table-widget");

    const coursesParent = $('.courses-parent');
    const coursesParentItem = $('.courses-parent .courses-parent-item');
    const coursesParentContent = $('.courses-parent .courses-parent-content');

    const coursesChild = $('.courses-child');
    const coursesChildItem = $('.courses-child .courses-child-item');
    const coursesChildContent = $('.courses-child .courses-child-content');

    const courseFilterSelect = $('.course-select-filter');
    const courseFilterRegion = $('.course-filter-region');
    const courseFilterType = $('.course-filter-type')
    const courseFilterInstructor = $('#course-filter-instructor')
    const courseFilterDate = $('#course-filter-date')
    let startDate, endDate = ' ';

    const begin = new Date();
    const end = new Date();
    const ranges = {
        'This Week': thisWeek(begin),
        'This Month': thisMonth(begin),
        'Next 60 Days': [begin, addDays(end, 60)],
        'Next 90 Days': [begin, addDays(end, 90)],
    };

    if (courseMixerContainer.length) {
        const mixer = mixitup(courseMixerContainer, {
            multifilter: {
                enable: true // enable the multifilter extension for the mixer
            },
            callbacks: {
                onMixStart: function (state, futureState) {
                }
            }
        });

        courseFilterSelect.on("change", function () {
            coursesParent.foundation('down', coursesParentContent);

            mixer.filter(this.value);

            coursesParentContent.each(function () {
                if ($(this).children(coursesChild).height() !== 0) {
                    console.log("Nothing")
                }
            })
        });

        $('.expand').on('click', function () {
            coursesParent.foundation('down', coursesParentContent);
        })

        $('.collapse').on('click', function () {
            coursesParent.foundation('up', coursesParentContent);
        })

        courseFilterType.on('change', function (event) {
            const loc = "#" + event.target.value;
            Foundation.SmoothScroll.scrollToLoc(loc);
        })

        if (typeof Litepicker !== 'undefined') {
            new Litepicker({
                element: courseFilterDate,
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
                }
            })
        }

        function reset() {
            // courseFilterSelect.val('all')
            // courseFilterRegion.val('all')
            // courseFilterInstructor.val('all')
            // courseFilterDate.val('all')

            mixer.filter('all')
        }
    }
});

/**
 *
 * @param date
 * @param num
 * @returns {Date}
 */
function addDays(date, num) {
    const d = new Date(date);
    d.setDate(d.getDate() + num);
    return d;
};

/**
 *
 * @param date
 * @returns {Date[]}
 */
function thisMonth(date) {
    const d1 = new Date(date);
    d1.setDate(1);
    const d2 = new Date(date.getFullYear(), date.getMonth() + 1, 0);
    return [d1, d2];
};

/**
 *
 * @param date
 * @returns {Date[]}
 */
function thisWeek(date) {
    const d1 = new Date(date);
    d1.setDate(date.getDate() - date.getDay());
    const d2 = new Date(date)
    d2.setDate(date.getDate() - (date.getDay() - 6));
    return [d1, d2];
}