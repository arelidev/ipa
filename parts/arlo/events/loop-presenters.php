<?php
$disable_link = $args['disable_link'] ?? false;

while (have_rows('presenters')) : the_row();
	$name = get_sub_field('name');
	$presenter = get_sub_field('linked_presenter');
    $users = false;

    if ($presenter) {
	    $users = get_users(array(
		    'meta_query' => array(
			    array(
				    'key' => 'arlo_presenter_profile',
				    'value' => $presenter,
			    )
		    )
	    ));
    }

	if ($users) :
		foreach ($users as $user) :
			$profileImage = get_field('profile_image', 'user_' . $user->ID);
			?>

			<?php if ($disable_link !== true) : ?>
                <a href="<?= home_url(); ?>/profile-member/<?= acf_slugify($name); ?>">
		    <?php endif; ?>

            <img src="<?= wp_get_attachment_image_url($profileImage); ?>"
                 class="course-card-trainer"
                 alt="<?= $name ?>"
                 data-tooltip tabindex="1"
                 title="<?= $name; ?>">

            <?php if ($disable_link !== true) : ?></a><?php endif; ?>

		<?php endforeach; ?>
	<?php else : ?>

		<?php if ($disable_link !== true) : ?>
            <a href="<?= home_url(); ?>/profile-member/<?= acf_slugify($name); ?>">
		<?php endif; ?>

        <img src="<?= get_placeholder_image(); ?>"
             class="course-card-trainer"
             alt="<?= $name ?>"
             data-tooltip tabindex="1"
             title="<?= $name; ?>">

		<?php if ($disable_link !== true) : ?></a><?php endif; ?>
    
	<?php endif; ?>
<?php endwhile; ?>
