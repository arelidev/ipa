<?php
/**
 * IPA Faculty Shortcode Widget
 *
 * @return false|string
 */
function ipa_faculty_widget() {
	ob_start();

	$args = array(
		'role' => 'profile_member',
		'meta_query' => array(
			array(
				'key' => 'display_on_faculty',
				'value' => '1',
				'compare' => '=='
			)
		)
	);

	$users = get_users( $args );
	?>

    <div class="faculty-filter-container">

        <div data-sticky-container>
            <div class="search-bar styled-container" data-sticky data-margin-top="0" data-anchor="ipa-faculty-widget">
                <div class="grid-container">
                    <div class="grid-x grid-padding-x grid-padding-y align-middle">
                        <div class="cell auto show-for-medium">
                            <b>Filter by:</b>
                        </div>
                        <div class="cell small-12 medium-auto">
                            <label>
                                <span class="hide-for-medium"><?= __( 'Search by State', 'ipa' ); ?></span>
                                <select class="filter-select">
                                    <option value="all">Search by State</option>
                                    <option value="all">All</option>
                                    <option value=".AL">Alabama</option>
                                    <option value=".AK">Alaska</option>
                                    <option value=".AZ">Arizona</option>
                                    <option value=".AR">Arkansas</option>
                                    <option value=".CA">California</option>
                                    <option value=".CO">Colorado</option>
                                    <option value=".CT">Connecticut</option>
                                    <option value=".DE">Delaware</option>
                                    <option value=".DC">District Of Columbia</option>
                                    <option value=".FL">Florida</option>
                                    <option value=".GA">Georgia</option>
                                    <option value=".HI">Hawaii</option>
                                    <option value=".ID">Idaho</option>
                                    <option value=".IL">Illinois</option>
                                    <option value=".IN">Indiana</option>
                                    <option value=".IA">Iowa</option>
                                    <option value=".KS">Kansas</option>
                                    <option value=".KY">Kentucky</option>
                                    <option value=".LA">Louisiana</option>
                                    <option value=".ME">Maine</option>
                                    <option value=".MD">Maryland</option>
                                    <option value=".MA">Massachusetts</option>
                                    <option value=".MI">Michigan</option>
                                    <option value=".MN">Minnesota</option>
                                    <option value=".MS">Mississippi</option>
                                    <option value=".MO">Missouri</option>
                                    <option value=".MT">Montana</option>
                                    <option value=".NE">Nebraska</option>
                                    <option value=".NV">Nevada</option>
                                    <option value=".NH">New Hampshire</option>
                                    <option value=".NJ">New Jersey</option>
                                    <option value=".NM">New Mexico</option>
                                    <option value=".NY">New York</option>
                                    <option value=".NC">North Carolina</option>
                                    <option value=".ND">North Dakota</option>
                                    <option value=".OH">Ohio</option>
                                    <option value=".OK">Oklahoma</option>
                                    <option value=".OR">Oregon</option>
                                    <option value=".PA">Pennsylvania</option>
                                    <option value=".RI">Rhode Island</option>
                                    <option value=".SC">South Carolina</option>
                                    <option value=".SD">South Dakota</option>
                                    <option value=".TN">Tennessee</option>
                                    <option value=".TX">Texas</option>
                                    <option value=".UT">Utah</option>
                                    <option value=".VT">Vermont</option>
                                    <option value=".VA">Virginia</option>
                                    <option value=".WA">Washington</option>
                                    <option value=".WV">West Virginia</option>
                                    <option value=".WI">Wisconsin</option>
                                    <option value=".WY">Wyoming</option>
                                </select>
                            </label>
                        </div>
                        <div class="cell small-12 medium-auto">
                            <label>
                                <span class="hide-for-medium"><?= __( 'Search by instructor', 'ipa' ); ?></span>
                                <input type="text" placeholder="Search by Instructor" id="FilterInput">
                            </label>
                        </div>
                        <div class="cell small-12 hide-for-medium">
                            <label>
                                <span class="hide-for-medium"><?= __( 'Instructor Status', 'ipa' ); ?></span>
                                <select class="filter-select">
                                    <option value="all">All</option>
                                    <option value=".primary-faculty"><?= __( 'Primary Instructor', 'ipa' ); ?></option>
                                    <option value=".associate-faculty"><?= __( 'Associate Instructor', 'ipa' ); ?></option>
                                </select>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ipa-faculty-statuses ipa-filter-bar grid-container show-for-medium">
            <div class="grid-x grid-padding-x grid-padding-y grid-margin-x grid-margin-y">
                <button type="button" data-filter="all" class="mixitup-control">All</button>
                <button type="button" data-filter=".primary-faculty" class="mixitup-control"><?= __( 'Primary Instructor', 'ipa' ); ?></button>
                <button type="button" data-filter=".associate-faculty" class="mixitup-control"><?= __( 'Associate Instructor', 'ipa' ); ?></button>
            </div>
        </div>

        <div class="ipa-faculty-widget grid-container" id="ipa-faculty-widget">
            <div class="grid-x grid-padding-x grid-padding-y grid-margin-x grid-margin-y">
				<?php foreach ( $users as $user ) : ?>
                    <?php
					$usermeta = array_map(function ($a) {
						return $a[0];
					}, get_user_meta($user->ID));

					$full_name = $usermeta['first_name'] . " " . $usermeta['last_name'];

					// ACF Fields
					$acf_user = 'user_' . $user->ID;

					$bio = get_field('bio', $acf_user);
					$profile_image = get_field('profile_image', $acf_user);

					$credentials = get_field('credentials', $acf_user);
                    $faculty_status = get_field('faculty_status', $acf_user);

                    $city = "";
					$state = "";
                    $state_short = "";
					if (have_rows('offices', $acf_user)) :
						while (have_rows('offices', $acf_user)) : the_row();
							$location = get_sub_field('address');
							$city = $location['city'];
							$state = $location['state'];
							$state_short = $location['state_short'];
						endwhile;
					endif;

					$faculty_classes = array(
						'ipa-faculty-member',
						'small-12',
						'medium-4',
						'large-3',
						'cell',
						'text-center',
						'styled-container',
						'mix',
						$state_short,
						acf_slugify($faculty_status),
                        acf_slugify( $full_name )
					);
					?>
                    <div class="<?= implode( " ", $faculty_classes ) ?>"
                         data-title="<?= acf_slugify( $full_name ); ?>"
                        <?= $faculty_status === "Not Faculty" || $faculty_status === "inactive" ? 'style="display: none"' : '' ?>
                    >
                        <div class="ipa-faulty-member-info">
                            <?php
	                        if (!empty($profile_image)) :
		                        echo get_profile_image($profile_image, 'ipa-faculty-member-image');;
	                        endif;
	                        ?>

                            <h5 class="ipa-faulty-member-name"><b><?= $full_name; ?></b></h5>

	                        <?php if (!empty($credentials)) : ?>
                                <p class="ipa-faulty-member-credentials text-color-medium-gray"><?= $credentials; ?></p>
	                        <?php endif; ?>

	                        <?php if (!empty($city)) : ?>
                                <p class="ipa-faulty-member-city"><?= $city . ", " . $state; ?></p>
	                        <?php endif; ?>
                        </div>
                        <div class="ipa-faculty-member-bio text-center flex-container flex-dir-column align-center-middle">
                            <h5 class="ipa-faulty-member-name text-color-white"><b><?= $full_name; ?></b></h5>
                            <p class="ipa-faculty-member-bio-copy text-color-white">
                                <small><?= wp_trim_words( $bio, 20 ); ?></small>
                            </p>
                            <a href="<?= home_url(); ?>/profile-member/<?= acf_slugify( $full_name ); ?>" class="button hollow white">
                                <?= __('View Profile', 'ipa'); ?>
                            </a>
                        </div>
                    </div>
				<?php endforeach; ?>
            </div>
        </div>

    </div>

	<?php
	return ob_get_clean();
}

add_shortcode( 'ipa_faculty', 'ipa_faculty_widget' );

/**
 * Integrate with Visual Composer
 *
 * @return void
 */
function ipa_faculty_integrateWithVC() {
	try {
		vc_map( array(
			"name"                    => __( "IPA Faculty Grid", "ipa" ),
			"base"                    => "ipa_faculty",
			"class"                   => "",
			"category"                => __( "Custom", "ipa" ),
			"params"                  => array(),
			"show_settings_on_create" => false
		) );
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_faculty_integrateWithVC' );
