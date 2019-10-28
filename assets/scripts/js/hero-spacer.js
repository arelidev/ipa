jQuery(document).ready(function () {
    adjust_top_bar_spacer();

});

jQuery(window).resize(function () {
    adjust_top_bar_spacer();
});

function adjust_top_bar_spacer() {
    let hero = jQuery('.hero');
    hero.css('padding-top', jQuery('.header').outerHeight() + 'px');
}