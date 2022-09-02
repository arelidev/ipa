<?php
while (have_rows('presenters')) : the_row();
	$name = get_sub_field('name');

	$presenter = get_sub_field('linked_presenter');

	$users = get_users(array(
		'meta_query' => array(
			array(
				'key' => 'arlo_presenter_profile',
				'value' => $presenter->ID,
			)
		)
	));

	if ($users) :
		foreach ($users as $user) :
			$profileImage = get_field('profile_image', 'user_' . $user->ID);
			?>
            <a href="<?= home_url(); ?>/profile-member/<?= acf_slugify( $name ); ?>">
                <img src="<?= wp_get_attachment_image_url($profileImage); ?>"
                     class="course-card-trainer"
                     alt="<?= $name ?>"
                     data-tooltip tabindex="1"
                     title="<?= $name; ?>">
            </a>
		<?php endforeach; ?>
	<?php else : ?>
        <a href="<?= home_url(); ?>/profile-member/<?= acf_slugify( $name ); ?>">
            <img src="<?= get_placeholder_image(); ?>"
                 class="course-card-trainer"
                 alt="<?= $name ?>"
                 data-tooltip tabindex="1"
                 title="<?= $name; ?>">
        </a>
	<?php endif; ?>
<?php endwhile; ?>
