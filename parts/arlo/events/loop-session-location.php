<?php
// Usage:
// while (have_rows('sessions')) : the_row();

$location = get_sub_field('location');
?>
<span data-tooltip data-allow-html="true" tabindex="1" title="
    <?= $location['venuename']; ?>,
    <?= $location['streetline1']; ?>
    <?= $location['city']; ?>, <?= $location['state']; ?> <?= $location['postcode']; ?>,
    <?= $location['country']; ?>"
>
    <?= $location['venuename']; ?>, <?= $location['city']; ?>, <?= $location['state']; ?>
</span>