jQuery(document).ready(function ($) {
    const ipaCoursesWidgetMixerContainer = $(".ipa-courses-widget");

    if (ipaCoursesWidgetMixerContainer.length && !ipaCoursesWidgetMixerContainer.hasClass("no-mix")) {
        const mixer = mixitup(ipaCoursesWidgetMixerContainer, {
            multifilter: {
                enable: true,
            },
            callbacks: {
                onMixStart: function (state) {
                    $('.ipa-courses-widget-cell').each(function () {
                        $(this).show();
                    });
                },
                onMixEnd: function (state) {
                    checkTablesVisibility();
                }
            }
        });

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

            const $targets = ipaCoursesWidgetMixerContainer.find('.mix');

            const $show = $targets.filter(function () {
                let course = new Date($(this).attr('data-start-date'));
                const date = new DateTime(course);

                return (date >= startDate) && (date <= endDate);
            });

            mixer.filter($show);

            openAll();
        });

        // Function to check if there are any visible rows in each table
        function checkTablesVisibility() {
            $('.ipa-courses-widget-cell').each(function () {
                const table = $(this).find('table');

                if (table.find('tr:visible').length === 1) {
                    $(this).hide(); // Hide the parent container if no visible rows
                } else {
                    $(this).show(); // Show the parent container if there are visible rows
                }
            });
        }
    }
})