<?php
/**
 * IPA Clinics Shortcode Widget
 *
 * @param $atts
 *
 * @return false|string
 */
function ipa_clinics_widget( $atts ): false|string {
	$atts = shortcode_atts( [
		'filters'  => true,
		'el_class' => ''
	], $atts );

	ob_start();

	$clinics = new WP_Query( [
		'post_type'      => 'ipa_clinics',
		'posts_per_page' => - 1
	] );

	$users = get_users( [
		'role'       => 'profile_member',
		'meta_query' => [
			[
				'key'     => 'display_on_clincs',
				'value'   => '1',
				'compare' => '=='
			]
		]
	] );
	?>
    <div class="ipa-clinics-widget <?= $atts['el_class']; ?>">

		<?php
		if ( $atts['filters'] !== "0" ) :
			get_template_part( 'parts/content-filters', 'clinic' );
		endif;
		?>

        <div class="grid-x">
            <div class="small-12 medium-6 large-6 cell small-order-2 large-order-1 ipa-clinic-card-wrapper" id="ipa-clinic-card-wrapper">
                <div class="grid-x grid-margin-x grid-container">
	                <?php while ( $clinics->have_posts() ) : $clinics->the_post(); ?>
		                <?php $address = get_field( 'clinic_address' ); ?>
                                                     <div class="small-12 medium-6 large-6 cell single-clinic featured-clinic styled-container mix"
                             data-type="clinic"
                             data-entity-id="<?php the_ID() ?>"
                             data-title="<?php the_title(); ?>"
                             data-addresses='<?= json_encode( [ $address ] ); ?>'
                             data-state="<?= $address['state'] ?? ''; ?>"
                             data-post-code="<?= $address['post_code'] ?? ''; ?>">
                            <div class="single-clinic-inner">
                                <div class="grid-x">
                                    <div class="auto cell">
                                        <h5 class="single-clinic-title"><b><?php the_title(); ?></b></h5>
						                <?php if ( ! empty( $subtitle = get_field( 'clinic_subtitle' ) ) ) : ?>
                                            <p class="single-clinic-subtitle text-color-medium-gray"><?= $subtitle; ?></p>
						                <?php endif; ?>
                                    </div>
                                    <div class="shrink cell">
						                <?php if ( has_post_thumbnail() ) : ?>
							                <?php the_post_thumbnail( 'medium', array( 'class' => 'map-icon-circle' ) ); ?>
						                <?php else : ?>
                                            <img src="<?= get_instructor_image(); ?>" class="map-icon-circle" alt="<?php the_title(); ?>">
						                <?php endif; ?>
                                    </div>
                                </div>
                                <div class="single-clinic-inner-info">
					                <?php if ( ! empty( $phone = get_field( 'clinic_phone' ) ) ) : ?>
                                        <div class="grid-x">
                                            <div class="cell small-2">
                                                <i class="fa-solid fa-mobile fa-lg"></i>
                                            </div>
                                            <div class="cell auto">
                                                <p class="single-clinic-phone">
                                                    <a href="tel:<?= $phone; ?>"><?= $phone; ?></a>
                                                </p>
                                            </div>
                                        </div>
					                <?php endif; ?>
					                <?php if ( ! empty( $address ) ) : ?>
                                        <div class="grid-x">
                                            <div class="cell small-2">
                                                <i class="fa-solid fa-map-marker-alt fa-lg"></i>
                                            </div>
                                            <div class="cell auto">
                                                <p class="single-clinic-address"><?= $address['address']; ?></p>
                                            </div>
                                        </div>
					                <?php endif; ?>
					                <?php if ( ! empty( $website = get_field( 'clinic_website' ) ) ) : ?>
                                        <div class="grid-x">
                                            <div class="cell small-2">
                                                <i class="fa-solid fa-external-link-alt fa-lg"></i>
                                            </div>
                                            <div class="cell auto">
                                                <p class="single-clinic-website">
                                                    <a href="<?= $website; ?>" target="_blank"><?= $website; ?></a>
                                                </p>
                                            </div>
                                        </div>
					                <?php endif; ?>
                                </div>
                            </div>
                        </div>
	                <?php endwhile; ?>
					<?php foreach ( $users as $user ) : ?>
						<?php
						$usermeta = array_map( function ( $a ) {
							return $a[0];
						}, get_user_meta( $user->ID ) );

						$full_name = $usermeta['first_name'] . " " . $usermeta['last_name'];

						// ACF Fields
						$acf_user = 'user_' . $user->ID;

						$profile_image     = get_field( 'profile_image', $acf_user );
						$work_information  = get_field( 'work_information', $acf_user );
						$credentials       = get_field( 'credentials', $acf_user );
						$cfmt_rankings     = get_field( 'cfmt_rankings', $acf_user );
						$cafmt_rankings    = get_field( 'cafmt_rankings', $acf_user );
						$fellowship_status = get_field( 'fellowship_status', $acf_user );
						$faculty_status    = get_field( 'faculty_status', $acf_user );
						$offices           = get_field( 'offices', $acf_user );

						$addresses     = [];
						$business_name = $work_information['business_name'] ?? false;

						if ( $offices ) :
							foreach ( $offices as $office ) :
                                $addresses[] = $office['address'];
                            endforeach;
						endif;

                        // Skip if there is no address set
						if ( empty( $addresses ) ) :
							continue;
						endif;

						$faculty_classes = [
							'small-12',
							'medium-6',
							'large-6',
							'cell',
							'single-clinic',
							'dyno-clinic',
							'styled-container',
							acf_slugify( $cfmt_rankings ),
							acf_slugify( $cafmt_rankings ),
							acf_slugify( $fellowship_status ),
							acf_slugify( $faculty_status ),
							acf_slugify( $full_name )
						];

						$type_classes = [
							( acf_slugify( $fellowship_status ) === 'fmt-fellow' ) ? 'fmt-fellow' : 'cfmt',
							acf_slugify( $cafmt_rankings ),
							acf_slugify( $faculty_status )
						];

						if ( $faculty_status === "Primary Faculty" || $faculty_status === "Associate Faculty" ) :
							$faculty_classes[] = $type_classes[] = "faculty";
						endif;
						?>
                        <div class="<?= implode( " ", $faculty_classes ) ?>"
                             data-entity-id="<?= $user->ID; ?>"
                             data-title="<?= $full_name; ?>"
                             data-addresses='<?= json_encode( $addresses ); ?>'
                             data-type="<?= implode( " ", $type_classes ) ?>"
                             data-certification="<?= $credentials; ?>"
                             data-fellow="<?= ( ! empty( $fellowship_status ) && $fellowship_status === 'FMT Fellow' ) ? 1 : 0; ?>"
                             data-cfmt="<?= ( ! empty( $cfmt_rankings ) && $cfmt_rankings !== "None" ) ? 1 : 0; ?>"
                             data-cafmt="<?= ( ! empty( $cafmt_rankings ) && $cafmt_rankings !== "None" ) ? 1 : 0; ?>"
                             data-state="<?= !empty($addresses[0]['state']) ? $addresses[0]['state'] : ''; ?>"
                        >
                            <div class="single-clinic-inner">
                                <div class="grid-x grid-padding-x">
                                    <div class="shrink cell">
										<?= get_profile_image( $profile_image, 'ipa-faculty-member-image' ); ?>
                                        <p class="text-center">
                                            <small>
                                                <a href="<?= home_url(); ?>/profile-member/<?= acf_slugify( $full_name ); ?>">
                                                    <b><?= __( 'View Profile', 'ipa' ); ?></b>
                                                </a>
                                            </small>
                                        </p>
                                    </div>
                                    <div class="auto cell">
                                        <h5 class="single-clinic-title"><b><?= $full_name; ?></b></h5>

	                                    <?php if ( ! empty( $credentials ) ) : ?>
                                            <p class="single-clinic-subtitle text-color-medium-gray"><?= $credentials; ?></p>
	                                    <?php endif; ?>

	                                    <?php if ( $business_name ) : ?>
                                            <p class="single-clinic-business-name"><?= $business_name; ?></p>
	                                    <?php endif; ?>

	                                    <?php if ( $cafmt_rankings === "CAFMT with Honors" ) : ?>
                                            <div class="grid-x align-center" style="margin-top: 10px;">
                                                <div class="cell shrink" style="margin-right: 5px;">
                                                    <i class="fa fa-medal" style="color: silver;"></i>
                                                </div>
                                                <div class="cell auto">
                                                    <small>CAFMT with Honors</small>
                                                </div>
                                            </div>
	                                    <?php endif; ?>

	                                    <?php if ( $cafmt_rankings === "CAFMT with Distinction" ) : ?>
                                            <div class="grid-x align-center" style="margin-top: 10px;">
                                                <div class="cell shrink" style="margin-right: 5px;">
                                                    <i class="fa fa-medal" style="color: gold;"></i>
                                                </div>
                                                <div class="cell auto">
                                                    <small>CAFMT with Distinction</small>
                                                </div>
                                            </div>
	                                    <?php endif; ?>

	                                    <?php if ( $cfmt_rankings === "CFMT with Honors" ) : ?>
                                            <div class="grid-x align-center" style="margin-top: 10px;">
                                                <div class="cell shrink" style="margin-right: 5px;">
                                                    <i class="fa fa-medal" style="color: silver;"></i>
                                                </div>
                                                <div class="cell auto">
                                                    <small>CFMT with Honors</small>
                                                </div>
                                            </div>
	                                    <?php endif; ?>

	                                    <?php if ( $cfmt_rankings === "CFMT with Distinction" ) : ?>
                                            <div class="grid-x align-center" style="margin-top: 10px;">
                                                <div class="cell shrink" style="margin-right: 5px;">
                                                    <i class="fa fa-medal" style="color: gold;"></i>
                                                </div>
                                                <div class="cell auto">
                                                    <small>CFMT with Distinction</small>
                                                </div>
                                            </div>
	                                    <?php endif; ?>
                                    </div>
                                </div>
                                <div class="single-clinic-inner-info">
									<?php if ( ! empty( $work_information['work_email'] ) ) : $email = $work_information['work_email']; ?>
                                        <div class="grid-x align-center">
                                            <div class="cell shrink">
                                                <i class="fa-solid fa-envelope"></i>
                                            </div>
                                            <div class="cell auto">
                                                <p class="single-clinic-inner-info-phone">
                                                    <a href="mailto:<?= $email; ?>"><?= $email; ?></a>
                                                </p>
                                            </div>
                                        </div>
									<?php endif; ?>

									<?php if ( ! empty( $work_information['work_telephone'] ) ) : $phone = $work_information['work_telephone']; ?>
                                        <div class="grid-x align-center">
                                            <div class="cell shrink">
                                                <i class="fa-solid fa-phone"></i>
                                            </div>
                                            <div class="cell auto">
                                                <p class="single-clinic-inner-info-phone">
                                                    <a href="tel:<?= $phone; ?>"><?= $phone; ?></a>
                                                </p>
                                            </div>
                                        </div>
									<?php endif; ?>

                                    <?php if ( ! empty( $offices ) ) : ?>
										<?php foreach ( $offices as $office ) : $address = $office['address']['address']; ?>
                                            <div class="grid-x align-center">
                                                <div class="cell shrink">
                                                    <i class="fa-solid fa-map-marker-alt"></i>
                                                </div>
                                                <div class="cell auto">
                                                    <p class="single-clinic-inner-info-address">
                                                        <?= $address; ?>
                                                    </p>
                                                </div>
                                            </div>
										<?php endforeach; ?>
									<?php endif; ?>

                                    <?php if ( ! empty( $work_information['work_website'] ) ) : $website = $work_information['work_website']; ?>
                                        <div class="grid-x align-center">
                                            <div class="cell shrink">
                                                <i class="fa-solid fa-external-link-alt"></i>
                                            </div>
                                            <div class="cell auto">
                                                <p class="single-clinic-inner-info-website">
                                                    <a href="<?= $website; ?>" target="_blank"><?= $website; ?></a>
                                                </p>
                                            </div>
                                        </div>
									<?php endif; ?>
                                </div>
                            </div>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>
            <div class="small-12 medium-6 large-6 cell small-order-1 large-order-2 map-container">
                <div class="map-overlay-card">
                    <div class="grid-x grid-padding-x grid-padding-y">
                        <div class="shrink cell text-center">
                            <img width="35"
                                 src="<?= get_template_directory_uri(); ?>/assets/images/icon-map-clinic-gold.png"
                                 aria-hidden="false" alt="">
                            <br>
                            <span><?= __( 'Clinic', 'ipa' ); ?></span>
                        </div>
                        <div class="shrink cell text-center">
                            <img width="35"
                                 src="<?= get_template_directory_uri(); ?>/assets/images/icon-map-fellowship-blue.png"
                                 aria-hidden="false" alt="">
                            <br>
                            <span><?= __( 'Fellow', 'ipa' ); ?></span>
                        </div>
                        <div class="shrink cell text-center">
                            <img width="35"
                                 src="<?= get_template_directory_uri(); ?>/assets/images/icon-map-cfmt-gray.png"
                                 aria-hidden="false" alt="">
                            <br>
                            <span><?= __( 'CFMT', 'ipa' ); ?></span>
                        </div>
                        <div class="shrink cell text-center hidden">
                            <img width="35"
                                 src="<?= get_template_directory_uri(); ?>/assets/images/icon-map-member.png"
                                 aria-hidden="false" alt="">
                            <br>
                            <span><?= __( 'CAFMT', 'ipa' ); ?></span>
                        </div>
                        <div class="shrink cell text-center">
                            <img width="35"
                                 src="<?= get_template_directory_uri(); ?>/assets/images/icon-map-multi-white.png"
                                 aria-hidden="false" alt="">
                            <br>
                            <span><?= __( 'Multi', 'ipa' ); ?></span>
                        </div>
                    </div>
                </div>
                <div class="acf-map"></div>
            </div>
        </div>
    </div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'ipa_clinics', 'ipa_clinics_widget' );

/**
 * Integrate with Visual Composer
 *
 * @return void
 */
function ipa_clinics_integrateWithVC(): void {
	try {
		vc_map( [
			"name"     => __( "Clinics Map", "ipa" ),
			"base"     => "ipa_clinics",
			"class"    => "",
			"category" => __( "Custom", "ipa" ),
			"params"   => [
				[
					"type"       => "textfield",
					"heading"    => __( "Hide Filters", "ipa" ),
					"param_name" => "hide_filters",
				],
				[
					"type"        => "textfield",
					"heading"     => __( "Extra class name", "tailpress" ),
					"param_name"  => "el_class",
					"description" => __( "", "tailpress" )
				]
			]
		] );
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_clinics_integrateWithVC' );
