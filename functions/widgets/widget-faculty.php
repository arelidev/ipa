<?php
/**
 * IPA Faculty Shortcode Widget
 *
 * @param $atts
 * @param null $content
 *
 * @return false|string
 */
function ipa_faculty_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(), $atts );

	ob_start();

	$faculty = get_faculty();
	?>

    <div class="faculty-filter-container">

        <div class="search-bar styled-container">
            <div class="grid-container">
                <div class="grid-x grid-padding-x grid-padding-y align-middle">
                    <div class="cell auto">
                        <b>Filter by:</b>
                    </div>
                    <div class="cell auto">
                        <label>
                            <span class="show-for-sr"><?= __( 'Select Faculty', 'ipa' ); ?></span>
                            <select id="FilterSelect">
                                <option value="all">State</option>
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

                    <div class="cell auto">
                        <label>
                            <span class="show-for-sr"><?= __( 'Search by instructor', 'ipa' ); ?></span>
                            <input type="text" placeholder="Search by instructor" id="FilterInput">
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="ipa-faculty-statuses ipa-filter-bar grid-container">
            <div class="grid-x grid-padding-x grid-padding-y grid-margin-x grid-margin-y">
                <button type="button" data-filter="all">All</button>
                <button type="button" data-filter=".instructor-status-1"><?= __( 'Primary Instructor', 'ipa' ); ?></button>
                <button type="button" data-filter=".instructor-status-2"><?= __( 'Associate Instructor', 'ipa' ); ?></button>
            </div>
        </div>

        <div class="ipa-faculty-widget grid-container">
            <div class="grid-x grid-padding-x grid-padding-y grid-margin-x grid-margin-y">
				<?php foreach ( $faculty as $item => $value ) : ?>
					<?php
					$faculty_classes = array(
						'ipa-faculty-member',
						'small-12',
						'medium-4',
						'large-3',
						'cell',
						'text-center',
						'styled-container',
						'mix',
						'instructor-status-' . $value['instructor_status'],
                        $value['work_state']
					);

					$full_name = $value['firstname'] . " " . $value['lastname'];
					?>
                    <div class="<?= implode( " ", $faculty_classes ) ?>" data-title="<?= acf_slugify( $full_name ); ?>">
                        <div class="ipa-faulty-member-info">
							<?php
							if ( ! empty( $image = $value['image'] ) ) :
								$image_url = FACULTY_MEMBER_IMAGE_URL . $image;
							else :
								$image_url = "https://api.adorable.io/avatars/500/{$value['name']}.png";
							endif; ?>
                            <img src="<?= $image_url; ?>" class="ipa-faculty-member-image" alt="Image for <?= $value['name']; ?>">
                            <h5 class="ipa-faulty-member-name"><b><?= $value['name']; ?></b></h5>
                            <p class="ipa-faulty-member-credentials text-color-medium-gray"><?= $value['credentials']; ?></p>
                            <p class="ipa-faulty-member-city"><?= $value['work_city'] . ", " . $value['work_state']; ?></p>
                        </div>
                        <div class="ipa-faculty-member-bio text-center flex-container flex-dir-column align-center-middle">
                            <h5 class="ipa-faulty-member-name text-color-white"><b><?= $value['name']; ?></b></h5>
                            <p class="ipa-faculty-member-bio-copy text-color-white">
                                <small><?= wp_trim_words( $value['bio'], 20 ); ?></small>
                            </p>
                            <a href="<?= home_url(); ?>/faculty/<?= clean( $value['name'] ); ?>/<?= $value['entity_id']; ?>" class="button hollow white">View Profile</a>
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

// Integrate with Visual Composer
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
