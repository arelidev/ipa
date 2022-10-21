<?php
$post = isset($args['post']) ? (int)$args['post'] : get_the_ID();

$locations = get_sub_field('location', $post);
?>
<?php if ($locations) : ?>
	<?php foreach ($locations as $location) : ?>
        <span data-tooltip data-allow-html="true" tabindex="1" title="
                <?= (!empty($location['venuename'])) ? $location['venuename'] . "</br>" : ""; ?>
                <?= (!empty($location['streetline1'])) ? $location['streetline1'] . "</br>" : ""; ?>
                <?= (!empty($location['city'])) ? $location['city'] : ""; ?>
                <?= (!empty($location['state'])) ? ", " . $location['state'] : ""; ?>
                <?= (!empty($location['postcode'])) ? $location['postcode'] . "</br>" : ""; ?>
                <?= (!empty($location['country'])) ? $location['country'] : ""; ?>
            ">
            <?= (!empty($location['venuename'])) ? $location['venuename'] . "</br>" : ""; ?>
            <?= (!empty($location['city'])) ? $location['city'] : ""; ?>
            <?= (!empty($location['state'])) ? ", " . $location['state'] : ""; ?>
        </span>
	<?php endforeach; ?>
<?php endif;