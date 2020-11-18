jQuery(document).ready(function ($) {
    $('#footer_cta_submit').on('click', function () {
        window.location.href = $('input[name=cta_link]:checked').attr('data-link');
    })
});
