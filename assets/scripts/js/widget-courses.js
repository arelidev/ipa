jQuery(document).ready(function ($) {
    const courseMixerContainer = $(".ipa-courses-table-widget");
    const coursesParent = $('.courses-parent');
    const coursesParentContent = $('.courses-parent .courses-parent-content');
    const coursesChild = $('.courses-child');
    const courseFilterSelect = $('.course-filter-state, .course-filter-region, .course-filter-instructor');
    const courseFilterLocation = $('.course-filter-location');
    const courseFilterType = $('.course-filter-type')

    if (courseMixerContainer.length && !courseMixerContainer.hasClass("no-mix")) {
        const mixer = mixitup(courseMixerContainer, {
            multifilter: {
                enable: true,
            }
        });

        courseFilterSelect.on("change", function () {
            coursesParent.foundation('down', coursesParentContent);

            if (this.value === 'all') {
                closeAll();
            }

            mixer.filter(this.value);

            coursesParentContent.each(function () {
                if ($(this).children(coursesChild).height() !== 0) {
                    // TODO: hide empty parents
                }
            })
        });

        courseFilterLocation.on("click", function () {
            coursesParent.foundation('down', coursesParentContent);

            courseFilterLocation.removeClass('active');
            $(this).addClass('active');

            const value = this.value;

            if (value === 'all') {
                closeAll();
            }

            mixer.filter(value);

            coursesParentContent.each(function () {
                if ($(this).children(coursesChild).height() !== 0) {
                    // TODO: hide empty parents
                }
            })
        });

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
        picker.on('select', () => {
            const DateTime = easepick.DateTime;

            let startDate = picker.getStartDate();
            let endDate = picker.getEndDate();

            const $targets = courseMixerContainer.find('.mix');

            const $show = $targets.filter(function () {
                let course = new Date($(this).attr('data-start-date'));
                const date = new DateTime(course);

                return (date >= startDate) && (date <= endDate);
            });

            mixer.filter($show);

            openAll();
        });

        $('.expand').on('click', function () {
            openAll();
        })
        $('.collapse').on('click', function () {
            closeAll();
        })

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

				$('.ipa-accordion-title').on('click', function (e) {
					e.preventDefault();
					$(this).parent().toggleClass('is-active');
					$(this).next().toggle().toggleClass('is-open');
				}); 
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
}

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
}

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