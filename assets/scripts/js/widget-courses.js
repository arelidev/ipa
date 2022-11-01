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

    if (courseMixerContainer.length && !courseMixerContainer.hasClass("no-mix")) {
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

            if (this.value === 'all' || this.value === '') {
                closeAll();
            }

            mixer.filter(this.value);

            coursesParentContent.each(function () {
                if ($(this).children(coursesChild).height() !== 0) {
                    console.log("Nothing")
                }
            })
        });

        $('.expand').on('click', function () {
            openAll();
        })

        $('.collapse').on('click', function () {
            closeAll();
        })

        courseFilterType.on('change', function (event) {
            const loc = "#" + event.target.value;
            Foundation.SmoothScroll.scrollToLoc(loc);
        })

        const picker = new easepick.create({
            element: document.getElementById('course-filter-date'),
            css: [
                'https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.0/dist/index.css',
                '/wp-content/themes/ipa/assets/styles/easepick.css'
            ],
            format: "MM/DD/YYYY",
            readonly: false,
            calendars: 2,
            grid: 2,
            zIndex: 100,
            plugins: [
                "RangePlugin",
            ]
        });

        picker.on('select', (e) => {
            const DateTime = easepick.DateTime;

            let startDate =  picker.getStartDate();
            let endDate =  picker.getEndDate();

            const $targets = courseMixerContainer.find('.mix');

            const $show = $targets.filter(function () {
                let course = new Date($(this).attr('data-start-date'));
                const date = new DateTime(course);

                return (date >= startDate) && (date <= endDate);
            });

            mixer.filter($show);

            openAll();
        });

        function openAll() {
            coursesParent.foundation('down', coursesParentContent);
        }

        function closeAll() {
            coursesParent.foundation('up', coursesParentContent);
        }

        function reset() {
            // courseFilterSelect.val('all')
            // courseFilterRegion.val('all')
            // courseFilterInstructor.val('all')
            // courseFilterDate.val('all')

            mixer.filter('all');

            closeAll();
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