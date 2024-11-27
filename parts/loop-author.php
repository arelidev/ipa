<?php
/**
 * Template part for displaying author content in author.php
 */

$queried  = get_queried_object();
$usermeta = array_map( function ( $a ) {
	return $a[0];
}, get_user_meta( $queried->ID ) );

$full_name = $usermeta['first_name'] . " " . $usermeta['last_name'];

// ACF Fields
$acf_user = 'user_' . $queried->ID;

$bio                 = get_field( 'bio', $acf_user );
$profile_image       = get_field( 'profile_image', $acf_user );
$work_details        = get_field( 'work_information', $acf_user );
$legacy_ids          = get_field( 'legacy_ids', $acf_user );
$number_of_locations = get_field( 'number_of_locations', $acf_user );
$offices             = get_field( 'offices', $acf_user );
$social              = get_field( 'social_profiles', $acf_user );
$credentials         = get_field( 'credentials', $acf_user );
$fellowship          = get_field( 'fellowship_status', $acf_user );
$cfmt                = get_field( 'cfmt_rankings', $acf_user );
$cafmt                = get_field( 'cafmt_rankings', $acf_user );
$faculty_status      = get_field( 'faculty_status', $acf_user );
$presenter_id        = get_field( 'arlo_presenter_profile', $acf_user ) ?? "NO-RESULTS";
?>

<?php if ( get_class( $queried ) === 'WP_User' ) : ?>
    <article id="profile-member-<?= $queried->ID; ?>" role="article" itemscope itemtype="http://schema.org/Person">

        <header class="article-header hero hero-standard">
            <div class="hero-inner grid-container">
                <h1 class="page-title text-center"><b><?= $full_name; ?></b></h1>

				<?php if ( ! empty( $credentials ) ) : ?>
                    <h5 class="ipa-faculty-member-credentials text-center">
						<?= $credentials; ?>
                    </h5>
				<?php endif; ?>
            </div>
        </header> <!-- end article header -->

        <section class="entry-content grid-container" itemprop="text">
            <div class="grid-x grid-padding-x">
                <div class="small-12 medium-4 large-4 cell">
                    <div class="ipa-faculty-member styled-container">
						<?= get_profile_image( $profile_image, 'ipa-faculty-member-image' ); ?>

                        <h3 class="ipa-faculty-member-name text-center"><b><?= $full_name; ?></b></h3>

						<?php if ( ! empty( $credentials ) ) : ?>
                            <p class="ipa-faculty-member-credentials text-center text-color-medium-gray">
								<?= $credentials; ?>
                            </p>
						<?php endif; ?>

                        <div class="ipa-faculty-member-info">
							<?php if ( ! empty( $fellowship ) && $fellowship !== 'None' ) : ?>
                                <div class="grid-x">
                                    <div class="cell small-2 text-center">
                                        <i class="fa-solid fa-file-certificate fa-lg"></i>
                                    </div>
                                    <div class="cell auto">
                                        <p><?= $fellowship; ?></p>
                                    </div>
                                </div>
							<?php endif; ?>

							<?php if ( ! empty( $cfmt ) && $cfmt !== 'None' ) : ?>
                                <div class="grid-x">
                                    <div class="cell small-2 text-center">
										<?php switch ( $cfmt ) {
											case( 'CFMT with Honors' ) :
												echo '<i class="fal fa-award fa-lg"></i>';
												break;
											case( 'CFMT with Distinction' ):
												echo '<i class="fal fa-medal fa-lg"></i>';
												break;
										} ?>
                                    </div>
                                    <div class="cell auto">
                                        <p><?= $cfmt; ?></p>
                                    </div>
                                </div>
							<?php endif; ?>

							<?php if ( $work_details ) : ?>

								<?php if ( $work_details['work_email'] ) : $email = $work_details['work_email']; ?>
                                    <div class="grid-x">
                                        <div class="cell small-2 text-center">
                                            <i class="fa-solid fa-envelope fa-lg"></i>
                                        </div>
                                        <div class="cell auto">
                                            <p class="ipa-faculty-member-email">
                                                <a href="mailto:<?= $email; ?>"><?= $email; ?></a>
                                            </p>
                                        </div>
                                    </div>
								<?php endif; ?>

								<?php if ( $work_details['work_telephone'] ) : $phone = $work_details['work_telephone']; ?>
                                    <div class="grid-x">
                                        <div class="cell small-2 text-center">
                                            <i class="fa-solid fa-phone fa-lg"></i>
                                        </div>
                                        <div class="cell auto">
                                            <p class="ipa-faculty-member-phone"><?= $phone; ?></p>
                                        </div>
                                    </div>
								<?php endif; ?>

								<?php if ( $work_details['work_website'] ) : $website = $work_details['work_website']; ?>
                                    <div class="grid-x">
                                        <div class="cell small-2 text-center">
                                            <i class="fa-solid fa-browser fa-lg"></i>
                                        </div>
                                        <div class="cell auto">
                                            <p class="ipa-faculty-member-website"><?= $website; ?></p>
                                        </div>
                                    </div>
								<?php endif; ?>

							<?php endif; ?>

							<?php if ( have_rows( 'offices', $acf_user ) ) : ?>
								<?php while ( have_rows( 'offices', $acf_user ) ) : the_row();
									$location = get_sub_field( 'address' ); ?>
                                    <div class="grid-x">
                                        <div class="cell small-2 text-center">
                                            <i class="fa-solid fa-map-marker-alt fa-lg"></i>
                                        </div>
                                        <div class="cell auto">
                                            <p class="ipa-faculty-member-address"><?= $location['address']; ?></p>
                                        </div>
                                    </div>
								<?php endwhile; ?>
							<?php endif; ?>

							<?php if ( have_rows( 'social_profiles', $acf_user ) ) : ?>
                                <hr style="margin-top: 0;">
                                <ul class="menu align-center-middle" style="margin-bottom: 0;">
									<?php while ( have_rows( 'social_profiles', $acf_user ) ) : the_row(); ?>
                                        <li>
                                            <a href="<?php the_sub_field( 'profile_link' ); ?>" target="_blank">
                                                <span class="show-for-sr">
                                                    <?php the_sub_field( 'profile_type' ); ?>
                                                </span>
												<?php
												switch ( get_sub_field( 'profile_type' ) ) {
													case( 'facebook' ) :
														$social_class = "fa-square-facebook";
														break;
													case( 'instagram' ) :
														$social_class = "fa-square-instagram";
														break;
													case( 'linkedin' ) :
														$social_class = "fa-linkedin";
														break;
													case( 'twitter' ) :
														$social_class = "fa-square-twitter";
														break;
													case( 'youtube' ) :
														$social_class = "fa-youtube";
														break;
													default:
														$social_class = "fa-square-user";
												}
												?>
                                                <i class="fa-brands <?= $social_class; ?> fa-2x" aria-hidden="true"></i>
                                            </a>
                                        </li>
									<?php endwhile; ?>
                                </ul>
							<?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="small-12 medium-8 large-7 large-offset-1 cell">
                    <h5><b><?= __( 'About', 'ipa' ); ?> <?= $full_name; ?></b></h5>

					<?= apply_filters( 'the_content', $bio ); ?>

                    <h5><b><?= __( 'Work Location & Information', 'ipa' ); ?></b></h5>

					<?php if ( have_rows( 'offices', $acf_user ) ) : ?>
						<?php while ( have_rows( 'offices', $acf_user ) ) : the_row();
							$location    = get_sub_field( 'address' );
							$description = get_sub_field( 'additional_location_information' );
							$hours       = get_sub_field( 'hours' );
							?>
                            <div class="office styled-container" style="margin-bottom: 1rem;">
                                <div class="profile-member-map">
                                    <div class="profile-member-marker"
                                         data-lat="<?= esc_attr( $location['lat'] ); ?>"
                                         data-lng="<?= esc_attr( $location['lng'] ); ?>">
                                        <p class="h6"><b><?= esc_html( $location['address'] ); ?></b></p>
                                        <p><?= apply_filters( 'the_content', $description ); ?></p>
                                    </div>
                                </div>

                                <div class="inner-container">
                                    <address><h6><b><?= $location['address']; ?></b></h6></address>

                                    <ul class="accordion ipa-accordion-widget" data-accordion
                                        data-allow-all-closed="true">
										<?php if ( ! empty( $description ) ): ?>
                                            <li class="accordion-item ipa-accordion-item" data-accordion-item>
                                                <a href="#"
                                                   class="accordion-title ipa-accordion-title text-color-black">
													<?= __( 'Details', 'ipa' ); ?>
                                                </a>
                                                <div class="accordion-content ipa-accordion-content" data-tab-content>
                                                    <p><?= apply_filters( 'the_content', $description ); ?></p>
                                                </div>
                                            </li>
										<?php endif; ?>
                                        <li class="accordion-item ipa-accordion-item" data-accordion-item>
                                            <a href="#" class="accordion-title ipa-accordion-title text-color-black">
												<?= __( 'My Work Hours', 'ipa' ); ?>
                                            </a>

                                            <div class="accordion-content ipa-accordion-content" data-tab-content>
												<?php
												$monday    = $hours['monday'];
												$tuesday   = $hours['tuesday'];
												$wednesday = $hours['wednesday'];
												$thursday  = $hours['thursday'];
												$friday    = $hours['friday'];
												$saturday  = $hours['saturday'];
												$sunday    = $hours['sunday'];
												?>

                                                <table class="stacked">
                                                    <thead>
                                                    <tr>
                                                        <th><?= __( '', 'ipa' ); ?></th>
                                                        <th><?= __( 'Opens', 'ipa' ); ?></th>
                                                        <th><?= __( 'Closes', 'ipa' ); ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td><b><?= __( 'Monday', 'ipa' ); ?></b></td>
                                                        <td><?= $monday['opens'] ?? ""; ?></td>
                                                        <td><?= $monday['closes'] ?? ""; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b><?= __( 'Tuesday', 'ipa' ); ?></b></td>
                                                        <td><?= $tuesday['opens'] ?? ""; ?></td>
                                                        <td><?= $tuesday['closes'] ?? ""; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b><?= __( 'Wednesday', 'ipa' ); ?></b></td>
                                                        <td><?= $wednesday['opens'] ?? ""; ?></td>
                                                        <td><?= $wednesday['closes'] ?? ""; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b><?= __( 'Thursday', 'ipa' ); ?></b></td>
                                                        <td><?= $thursday['opens'] ?? ""; ?></td>
                                                        <td><?= $thursday['closes'] ?? ""; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b><?= __( 'Friday', 'ipa' ); ?></b></td>
                                                        <td><?= $friday['opens'] ?? ""; ?></td>
                                                        <td><?= $friday['closes'] ?? ""; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b><?= __( 'Saturday', 'ipa' ); ?></b></td>
                                                        <td><?= $saturday['opens'] ?? ""; ?></td>
                                                        <td><?= $saturday['Closes'] ?? ""; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b><?= __( 'Sunday', 'ipa' ); ?></b></td>
                                                        <td><?= $sunday['opens'] ?? ""; ?></td>
                                                        <td><?= $sunday['Closes'] ?? ""; ?></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
						<?php endwhile; ?>
					<?php endif; ?>
                </div>
            </div>
        </section> <!-- end article section -->

		<?php if ( $faculty_status !== "Not Faculty" ) : ?>
            <footer class="article-footer grid-container">
                <div class="grid-x grid-padding-x grid-padding-y">
                    <div class="cell">
                        <h3><b><?= $full_name; ?>'s <?= __( "Upcoming Courses", "ipa" ); ?></b></h3>
                    </div>
                    <div class="cell">
						<?= do_shortcode( "[ipa_courses presenter='$presenter_id' filters='0' display='calendar' condensed='1']" ); ?>
                    </div>
                </div>
            </footer> <!-- end article footer -->
		<?php endif; ?>

    </article> <!-- end article -->
<?php endif; ?>