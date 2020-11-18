jQuery(document).ready(function ($) {

    // Break course titles from DB string
    $('.page-template-template-course .page-title').each(function () {
        $(this).html($(this).text().replace(/:.*$/, '<span class="after">$&</span>'));
    });
})
