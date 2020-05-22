<?php
/**
 * Job Posts Shortcode Widget
 *
 * @param $atts
 * @param null $content
 *
 * @return false|string
 */
function ipa_jobs_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(), $atts );

	ob_start();

	$args = array(
		'post_type'      => 'ipa_jobs',
		'posts_per_page' => - 1
	);

	$loop = new WP_Query( $args );
	?>
    <div class="job-posts-widget">
        <table class="styled-container hover"> <!-- .datatable -->
            <thead>
            <tr>
                <th><?= __( 'Location', 'ipa' ); ?></th>
                <th><?= __( 'Hiring organization', 'ipa' ); ?></th>
                <th><?= __( 'Job description and link to more information', 'ipa' ); ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
			<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                <tr>
                    <td><?= get_field( 'job_city__state' ); ?></td>
                    <td><?= get_field( 'job_organization' ); ?></td>
                    <td>
                        <a href="<?= get_field( 'job_file' ); ?>"><?php the_title(); ?>
                    </td>
                </tr>
			<?php endwhile; ?>
            </tbody>
        </table>
    </div>
	<?php
	wp_reset_postdata();

	return ob_get_clean();
}

add_shortcode( 'ipa_jobs', 'ipa_jobs_widget' );

// Integrate with Visual Composer
function ipa_jobs_integrateWithVC() {
	try {
		vc_map( array(
			"name"                    => __( "Job Posts", "ipa" ),
			"base"                    => "ipa_jobs",
			"class"                   => "",
			"category"                => __( "Custom", "ipa" ),
			"params"                  => array(),
			"show_settings_on_create" => false
		) );
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_jobs_integrateWithVC' );
