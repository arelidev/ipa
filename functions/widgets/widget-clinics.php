<?php
/**
 * IPA Clinics Shortcode
 *
 * @param $atts
 *
 * @return false|string
 */
function ipa_clinics_widget( $atts ) {
	shortcode_atts( array(), $atts );

	ob_start();

	$args = array( 'post_type' => 'ipa_clinics', 'posts_per_page' => - 1 );
	$loop = new WP_Query( $args );

	$clinics               = get_clinics();

	?>
    <div class="ipa-clinics-widget">

        <div class="search-bar styled-container">
            <div class="grid-x grid-padding-x grid-padding-y align-middle">
                <div class="cell auto">
                    <b>Filter by:</b>
                </div>
                <div class="cell shrink">
                    <a class="button small clear-button" style="margin-bottom: 0px">
                        Clear
                    </a>
                </div>
                <div class="cell auto">
                    <label><span class="show-for-sr">Select Menu</span>
                        <select id="clinics-filter-select">
                            <option value="">Type</option>
                            <option value="">All</option>
                            <option value="faculty primary">Primary Faculty</option>
                            <option value="faculty">All Faculty</option>
                            <option value="fellow faculty">FMT Fellows</option>
                            <option value="clinic">IPA Clinics</option>
                        </select>
                    </label>
                </div>
                <div class="cell auto">
                    <label><span class="show-for-sr">Input Label</span>
                        <select class="clinics-filter-state" id="clinics-filter-state">
                            <option value="all">State</option>
                            <option value="all">All</option>
                            <option value="AL">Alabama</option>
                            <option value="AK">Alaska</option>
                            <option value="AZ">Arizona</option>
                            <option value="AR">Arkansas</option>
                            <option value="CA">California</option>
                            <option value="CO">Colorado</option>
                            <option value="CT">Connecticut</option>
                            <option value="DE">Delaware</option>
                            <option value="DC">District Of Columbia</option>
                            <option value="FL">Florida</option>
                            <option value="GA">Georgia</option>
                            <option value="HI">Hawaii</option>
                            <option value="ID">Idaho</option>
                            <option value="IL">Illinois</option>
                            <option value="IN">Indiana</option>
                            <option value="IA">Iowa</option>
                            <option value="KS">Kansas</option>
                            <option value="KY">Kentucky</option>
                            <option value="LA">Louisiana</option>
                            <option value="ME">Maine</option>
                            <option value="MD">Maryland</option>
                            <option value="MA">Massachusetts</option>
                            <option value="MI">Michigan</option>
                            <option value="MN">Minnesota</option>
                            <option value="MS">Mississippi</option>
                            <option value="MO">Missouri</option>
                            <option value="MT">Montana</option>
                            <option value="NE">Nebraska</option>
                            <option value="NV">Nevada</option>
                            <option value="NH">New Hampshire</option>
                            <option value="NJ">New Jersey</option>
                            <option value="NM">New Mexico</option>
                            <option value="NY">New York</option>
                            <option value="NC">North Carolina</option>
                            <option value="ND">North Dakota</option>
                            <option value="OH">Ohio</option>
                            <option value="OK">Oklahoma</option>
                            <option value="OR">Oregon</option>
                            <option value="PA">Pennsylvania</option>
                            <option value="RI">Rhode Island</option>
                            <option value="SC">South Carolina</option>
                            <option value="SD">South Dakota</option>
                            <option value="TN">Tennessee</option>
                            <option value="TX">Texas</option>
                            <option value="UT">Utah</option>
                            <option value="VT">Vermont</option>
                            <option value="VA">Virginia</option>
                            <option value="WA">Washington</option>
                            <option value="WV">West Virginia</option>
                            <option value="WI">Wisconsin</option>
                            <option value="WY">Wyoming</option>
                        </select>
                    </label>
                </div>
          
                <div class="cell auto">
                    <label><span class="show-for-sr">Input Label</span>
                        <input type="text" class="clinics-filter-certification" id="clinics-filter-instructor" placeholder="Name">
                    </label>
                </div>

                <div class="cell shrink">
                    <label>or</label>
                </div>

                <div class="cell auto">
                    <label><span class="show-for-sr">Input Label</span>
                        <input type="text" placeholder="Zip Code" id="clinics-filter-zip">
                    </label>
                </div>
            </div>
        </div>

        <div class="grid-x">
            <div class="small-12 medium-6 large-6 cell small-order-2 large-order-1 ipa-clinic-card-wrapper"
                 id="ipa-clinic-card-wrapper">
                <div class="grid-x grid-margin-x grid-container">
					<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
						<?php $address = get_field( 'clinic_address' ); ?>
                        <div class="small-12 medium-6 large-6 cell single-clinic featured-clinic styled-container mix"
                             data-type="clinic"
                             data-entity-id="<?php the_ID() ?>"
                             data-address="<?= $address['address']; ?>"
                             data-zip="<?= $address['post_code'] ?>"
                             data-state="<?= $address['state_short'] ?>"
                             data-title="<?php the_title(); ?>"
                             data-lat="<?= esc_attr( $address['lat'] ); ?>"
                             data-lng="<?= esc_attr( $address['lng'] ); ?>">
                            <div class="single-clinic-inner accordion" data-accordion data-allow-all-closed="true">
                                <div class="grid-x accordion-title">
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
                                <div class="accordion-content">
									<?php if ( ! empty( $phone = get_field( 'clinic_phone' ) ) ) : ?>
                                        <div class="grid-x">
                                            <div class="cell small-2">
                                                <i class="far fa-mobile fa-lg"></i>
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
                                                <i class="far fa-map-marker-alt fa-lg"></i>
                                            </div>
                                            <div class="cell auto">
                                                <p class="single-clinic-address"><?= $address['address']; ?></p>
                                            </div>
                                        </div>
									<?php endif; ?>
									<?php if ( ! empty( $website = get_field( 'clinic_website' ) ) ) : ?>
                                        <div class="grid-x">
                                            <div class="cell small-2">
                                                <i class="far fa-external-link-alt fa-lg"></i>
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

					<?php foreach ( $clinics as $clinic ) : ?>
						<?php $address = build_address( $clinic ); ?>
                        <div class="small-12 medium-6 large-6 cell single-clinic dyno-clinic styled-container mix <?= $clinic['current_fellow'] == 1 ? 'fellow' : 'cfmt' ?>"
                             data-type="<?= $clinic['current_fellow'] == 1 ? 'fellow' : 'cfmt' ?> faculty <?= $clinic['instructor_status'] == 1 ? 'primary' : '' ?>"
                             data-address="<?= $address['address']; ?>"
                             data-country="<?= $clinic['work_country']; ?>"
                             data-entity-id="<?= $clinic['entity_id']; ?>"
                             data-zip="<?= $clinic['work_zip'] ?>"
                             data-state="<?= $clinic['work_state'] ?>"
                             data-title="<?= $clinic['name']; ?>"
                             data-certification="<?= $clinic['credentials'] ?>"
                             data-lat="<?= $clinic['work_latitude']; ?>"
                             data-lng="<?= $clinic['work_longitude']; ?>"
                             data-fellow="<?= $clinic['current_fellow']; ?>"
                             data-cfmt="<?= $clinic['cfmt'] ?>">
                            <div class="single-clinic-inner accordion" data-accordion data-allow-all-closed="true">
                                <div class="grid-x accordion-title">
                                    <div class="shrink cell">
                                        <img src="<?= get_instructor_image( $clinic['image'] ); ?>" class="ipa-faculty-member-image" alt="<?= $clinic['name']; ?>">
                                    </div>
                                    <div class="auto cell">
                                        <h5 class="single-clinic-title"><b><?= $clinic['name']; ?></b></h5>
										<?php if ( ! empty( $credentials = $clinic['credentials'] ) ) : ?>
                                            <p class="single-clinic-subtitle text-color-medium-gray"><?= $credentials; ?></p>
										<?php endif; ?>
										<?php if ( ! empty( $business_name = $clinic['business_name'] ) ) : ?>
                                            <p class="single-clinic-business-name"><?= $business_name; ?></p>
										<?php endif; ?>
                                    </div>
                                </div>
                                <div class="accordion-content">
	                                <?php if ( $clinic['cfmt_honors'] == 1 ) : ?>
                                        <div class="grid-x">
                                            <div class="cell small-2">
                                                <i class="fal fa-award fa-lg"></i>
                                            </div>
                                            <div class="cell auto">
                                                <p>
                                                    CFMT with Honors
                                                </p>
                                            </div>
                                        </div>
	                                <?php endif; ?>
	                                <?php if ( $clinic['cfmt_distinction'] == 1 ) : ?>
                                        <div class="grid-x">
                                            <div class="cell small-2">
                                                <i class="fal fa-medal fa-lg"></i>
                                            </div>
                                            <div class="cell auto">
                                                <p>
                                                    CFMT with Distinction
                                                </p>
                                            </div>
                                        </div>
	                                <?php endif; ?>
	                                <?php if ( $clinic['current_fellow'] == 1 ) : ?>
                                        <div class="grid-x">
                                            <div class="cell small-2">
                                                <i class="far fa-user-md fa-lg"></i>
                                            </div>
                                            <div class="cell auto">
                                                <p>
                                                    FMT Fellow
                                                </p>
                                            </div>
                                        </div>
	                                <?php endif; ?>
                                    <?php if ( ! empty( $email = $clinic['work_email'] ) ) : ?>
                                        <div class="grid-x">
                                            <div class="cell small-2">
                                                <i class="far fa-envelope fa-lg"></i>
                                            </div>
                                            <div class="cell auto">
                                                <p class="single-clinic-phone">
                                                    <a href="mailto:<?= $email; ?>"><?= $email; ?></a>
                                                </p>
                                            </div>
                                        </div>
									<?php endif; ?>
									<?php if ( ! empty( $phone = $clinic['work_telephone'] ) ) : ?>
                                        <div class="grid-x">
                                            <div class="cell small-2">
                                                <i class="far fa-mobile fa-lg"></i>
                                            </div>
                                            <div class="cell auto">
                                                <p class="single-clinic-phone">
                                                    <a href="tel:<?= $phone; ?>"><?= $phone; ?></a>
                                                </p>
                                            </div>
                                        </div>
									<?php endif; ?>
									<?php if ( ! empty( $address = $address['formatted'] ) ) : ?>
                                        <div class="grid-x">
                                            <div class="cell small-2">
                                                <i class="far fa-map-marker-alt fa-lg"></i>
                                            </div>
                                            <div class="cell auto">
                                                <p class="single-clinic-address">
													<?= $address; ?>
                                                </p>
                                            </div>
                                        </div>
									<?php endif; ?>
									<?php if ( ! empty( $website = $clinic['customer_website'] ) ) : ?>
                                        <div class="grid-x">
                                            <div class="cell small-2">
                                                <i class="far fa-external-link-alt fa-lg"></i>
                                            </div>
                                            <div class="cell auto">
                                                <p class="single-clinic-website">
                                                    <a href="<?= $website; ?>" target="_blank"><?= $website; ?></a>
                                                </p>
                                            </div>
                                        </div>
									<?php endif; ?>
                                    <a href="<?= home_url(); ?>/faculty/<?= clean( $clinic['name'] ); ?>/<?= $clinic['entity_id']; ?>" class="button small">View Profile</a>
                                </div>
                            </div>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>
            <div class="small-12 medium-6 large-6 cell small-order-1 large-order-2 map-container">
                <div class="grid-x map-overlay-card">
                    <div class="medium-3 text-center">
                        <img width="35" src="<?= get_template_directory_uri(); ?>/assets/images/icon-map-clinic-gold.png"><br>
                        <span>Clinic</span>
                    </div>

                    <div class="medium-3 text-center">
                        <img width="35" src="<?= get_template_directory_uri(); ?>/assets/images/icon-map-fellowship-blue.png"><br>
                        <span>Fellow</span>
                    </div>

                    <div class="medium-3 text-center">
                        <img width="35" src="<?= get_template_directory_uri(); ?>/assets/images/icon-map-cfmt-gray.png"><br>
                        <span>CFMT</span>
                    </div>

                    <div class="medium-3 text-center">
                        <img width="35" src="<?= get_template_directory_uri(); ?>/assets/images/icon-map-multi-white.png"><br>
                        <span>Multi</span>
                    </div>
                </div>
                <div class="acf-map"></div>
            </div>
        </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_API_KEY; ?>"></script>
    <script type="text/javascript">
        var map;
        var init;
        var prevInfoWindow;

        jQuery(document).ready(function ($) {

            function initMap($el) {

                // Find marker elements within map.
                let $markers = $('.single-clinic:visible');

                // Create gerenic map.
                let mapArgs = {
                    zoom: $el.data('zoom') || 16,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    streetViewControlOptions: {
                        position: google.maps.ControlPosition.LEFT_BOTTOM,
                    },
                    zoomControlOptions: {
                        position: google.maps.ControlPosition.LEFT_BOTTOM,
                    },
                };
                map = new google.maps.Map($el[0], mapArgs);

                // Add markers.
                map.markers = [];
                $markers.each(function () {
                    initMarker($(this), map);
                });

                // Add a marker clusterer to manage the markers.
                let markerClusterer = new MarkerClusterer(map, map.markers, {
                    imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m',
                    maxZoom: 10,
                    gridSize: 10,
                    clusterClass: 'custom-clustericon'
                });

                // Center map based on markers.
                centerMap(map);

                // Return map instance.
                return map;
            }

            function initMarker($marker, map) {
                // Get position from marker.
                let address = $marker.data('address')
                let entity = $marker.data('entity-id')
                let type = $marker.data('type')
                let cfmt = $marker.data('cfmt')
                let fellow = $marker.data('fellow')
                let marker = '';
                let geocoder;
                let icon;
                let zIndex = 1;

                let iconBase = '<?= get_template_directory_uri(); ?>/assets/images/icon-map-';
                if (type == 'clinic') {
                    icon = iconBase + 'clinic-gold.png'
                    zIndex = 100;
                } else if (fellow == 1) {
                    icon = iconBase + 'fellowship-blue.png'
                } else {
                    icon = iconBase + 'cfmt-gray.png'
                }
                let multiIcon = iconBase + 'multi-white.png'

                if ($marker.data('lat') !== undefined && $marker.data('lng') !== undefined) {
                    let lat = $marker.data('lat');
                    let lng = $marker.data('lng');

                    let latLng = {
                        lat: parseFloat(lat),
                        lng: parseFloat(lng)
                    };

                    // Create marker instance.
                    marker = new google.maps.Marker({
                        position: latLng,
                        map: map,
                        entity: entity,
                        icon: icon,
                        zIndex: zIndex
                    });

                } else if ($marker.data('address').length > 0 && $marker.data('country') === 'United States') {
                    geocoder = new google.maps.Geocoder();

                    geocoder.geocode({'address': $marker.data('address')}, function (results, status) {
                        if (status === 'OK') {
                            // map.setCenter(results[0].geometry.location);
                            marker = new google.maps.Marker({
                                position: results[0].geometry.location,
                                map: map
                            });
                        } else {
                            console.log('Geocode was not successful for the following reason: ' + status);
                        }
                    });
                } else {
                    console.log('No address set for: ' + entity)
                }

                // Append to reference for later use.
                if (marker !== '') {

                    // If marker contains HTML, add it to an infoWindow.
                    if ($marker.html()) {
                        let infowindows = [];

                        let existing = map.markers.filter(mark => mark.getPosition().lat() == marker.getPosition().lat() && mark.getPosition().lng() == marker.getPosition().lng())
                        // Map cards, map markers, infowindows. All separate
                        let windowContent = '';

                        if (existing.length > 0) {
                            let $mark = existing[0]
                            existing = [];
                            $('.single-clinic[data-lat="' + $mark.getPosition().lat() + '"][data-lng="' + $mark.getPosition().lng() + '"]').each(function () {
                                existing.push($(this))
                                let html = $(this).clone()
                                $(html).find('.accordion-title').removeClass('accordion-title');
                                $(html).find('.accordion-content').removeClass('accordion-content');
                                $(html).removeClass('accordion')
                                windowContent += $(html).html()
                                if ( $mark.icon.indexOf( iconBase + 'clinic-gold.png' ) != -1 ) {
                                    marker.setZIndex(101)
                                    marker.setIcon( iconBase + 'clinic-gold.png' );
                                } else {
                                    marker.setIcon(multiIcon)
                                }
                            })
                        } else {
                            let html = $marker.clone();
                            $(html).find('.accordion-title').removeClass('accordion-title');
                            $(html).find('.accordion-content').removeClass('accordion-content');
                            $(html).removeClass('accordion')
                            windowContent = $(html).html()
                        }

                        map.markers.push(marker);

                        // Create info window.
                        let infowindow = new google.maps.InfoWindow({
                            content: windowContent
                        });

                        infowindows.push(infowindow);

                        // Show info window when marker is clicked.
                        google.maps.event.addListener(marker, 'click', function () {
                            // close all
                            for (let i = 0; i < infowindows.length; i++) {
                                infowindows[i].close(map);
                                infowindow.close()
                            }

                            // Close previously opened info window
                            if (prevInfoWindow) {
                                prevInfoWindow.close()
                            }

                            infowindow.open(map, marker);

                            // Set new prev window
                            prevInfoWindow = infowindow;
                        });

                        google.maps.event.addListener(map, 'click', function () {
                            infowindow.close();
                        });

                        google.maps.event.addListener(map, 'click', function () {
                            infowindow.close();
                        });

                        // Move map to marker position on click
                        if (existing.length > 1) {
                            existing.forEach(function ($mark) {
                                // $mark.unbind()
                                bindClickEvent($mark, map, infowindow)
                            })
                        } else {
                            bindClickEvent($marker, map, infowindow)
                        }

                        function bindClickEvent($marker, map, infowindow) {
                            $marker.on('click', function () {
                                map.setCenter(marker.getPosition());
                                map.setZoom(11)

                                if (prevInfoWindow) {
                                    prevInfoWindow.close()
                                }

                                infowindow.open(map, marker);
                                prevInfoWindow = infowindow;
                            })
                        }
                    }
                }
            }

            function centerMap(map) {
                // Create map boundaries from all map markers.
                let bounds = new google.maps.LatLngBounds();
                map.markers.forEach(function (marker) {
                    bounds.extend({
                        lat: marker.position.lat(),
                        lng: marker.position.lng()
                    });
                });

                // Case: Single marker.
                if (map.markers.length === 1) {
                    map.setCenter(bounds.getCenter());

                    // Case: Multiple markers.
                } else {
                    map.fitBounds(bounds);
                }
            }

            $('.acf-map').each(function () {
                let map = initMap($(this));
            });

            init = initMap

            // Delay function
            let delay = (function () {
                let timer = 0;
                return function (callback, ms) {
                    clearTimeout(timer);
                    timer = setTimeout(callback, ms);
                };
            })();
        })
    </script>
	<?php
	return ob_get_clean();
}

add_shortcode( 'ipa_clinics', 'ipa_clinics_widget' );

// Integrate with Visual Composer
function ipa_clinics_integrateWithVC() {
	try {
		vc_map( array(
			"name"                    => __( "Clinics Map", "ipa" ),
			"base"                    => "ipa_clinics",
			"class"                   => "",
			"category"                => __( "Custom", "ipa" ),
			"params"                  => array(),
			"show_settings_on_create" => false
		) );
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_clinics_integrateWithVC' );
