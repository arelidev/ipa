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

	$clinics = get_clinics();
	?>
    <div class="ipa-clinics-widget">

        <div class="search-bar styled-container">
            <div class="grid-x grid-padding-x grid-padding-y align-middle">
                <div class="cell auto">
                    <b>Filter by:</b>
                </div>
                <div class="cell auto">
                    <label><span class="show-for-sr">Select Menu</span>
                        <select id="clinics-filter-select">
                            <option value="">All</option>
                            <option value="faculty">Faculty</option>
                            <option value="clinic">Clinic</option>
                        </select>
                    </label>
                </div>
                <div class="cell auto">
                    <label><span class="show-for-sr">Input Label</span>
                        <input type="text" placeholder="State" id="clinics-filter-state">
                    </label>
                </div>
                <div class="cell auto">
                    <label><span class="show-for-sr">Input Label</span>
                        <input type="text" placeholder="Zip Code" id="clinics-filter-zip">
                    </label>
                </div>
                <div class="cell auto">
                    <label><span class="show-for-sr">Input Label</span>
                        <input type="text" placeholder="Certification" id="clinics-filter-certification">
                    </label>
                </div>
                <div class="cell auto">
                    <label><span class="show-for-sr">Input Label</span>
                        <input type="text" placeholder="Search by instructor" id="clinics-filter-instructor">
                    </label>
                </div>
            </div>
        </div>

        <div class="grid-x">
            <div class="small-12 medium-6 large-6 cell small-order-2 large-order-1 ipa-clinic-card-wrapper" id="ipa-clinic-card-wrapper">
                <div class="grid-x grid-margin-x grid-container">
					<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
						<?php $address = get_field( 'clinic_address' ); ?>
                        <div class="small-12 medium-6 large-6 cell single-clinic featured-clinic styled-container mix"
                             data-type="clinic"
                             data-address="<?= $address['address']; ?>"
                             data-title="<?php the_title(); ?>"
                             data-lat="<?= esc_attr( $address['lat'] ); ?>"
                             data-lng="<?= esc_attr( $address['lng'] ); ?>">
                            <div class="single-clinic-inner">
                                <div class="grid-x">
                                    <div class="auto cell">
                                        <h5 class="single-clinic-title"><b><?php the_title(); ?></b></h5>
										<?php if ( ! empty( $subtitle = get_field( 'clinic_subtitle' ) ) ) : ?>
                                            <p class="single-clinic-subtitle text-color-medium-gray"><?= $subtitle; ?></p>
										<?php endif; ?>
                                    </div>
                                    <div class="shrink cell">
                                        <i class="fas fa-star fa-lg"></i>
                                    </div>
                                </div>
                                <hr>
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
					<?php endwhile; ?>

                    <?php foreach ( $clinics as $clinic ) : ?>
                    <?php $address = build_address( $clinic ); ?>
                        <div class="small-12 medium-6 large-6 cell single-clinic dyno-clinic styled-container mix"
                             data-type="faculty"
                             data-address="<?= $address['address']; ?>"
                             data-country="<?= $clinic['work_country']; ?>"
                             data-entity-id="<?= $clinic['entity_id']; ?>"
                             data-title="<?= $clinic['name'];; ?>"
                             data-certification="<?= $clinic['credentials'] ?>"
                             data-lat="<?= $clinic['work_latitude']; ?>"
                             data-lng="<?= $clinic['work_longitude']; ?>">
                            <div class="single-clinic-inner">
                                <div class="grid-x">
                                    <div class="shrink cell">
	                                    <?php
	                                    if ( ! empty( $image = $clinic['image'] ) ) :
		                                    $image_url = FACULTY_MEMBER_IMAGE_URL . $image;
	                                    else :
		                                    $image_url = "https://api.adorable.io/avatars/500/{$clinic['name']}.png";
	                                    endif;
	                                    ?>
                                        <img src="<?= $image_url; ?>" class="ipa-faculty-member-image" alt="<?= $clinic['name']; ?>">
                                    </div>
                                    <div class="auto cell">
                                        <h5 class="single-clinic-title"><b><?= $clinic['name']; ?></b></h5>
					                    <?php if ( ! empty( $credentials =  $clinic['credentials'] ) ) : ?>
                                            <p class="single-clinic-subtitle text-color-medium-gray"><?= $credentials; ?></p>
					                    <?php endif; ?>
	                                    <?php if ( ! empty( $business_name =  $clinic['business_name'] ) ) : ?>
                                            <p class="single-clinic-business-name"><?= $business_name; ?></p>
	                                    <?php endif; ?>
                                    </div>
                                </div>
                                <hr>
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
			                    <?php if ( ! empty( $address ) ) : ?>
                                    <div class="grid-x">
                                        <div class="cell small-2">
                                            <i class="far fa-map-marker-alt fa-lg"></i>
                                        </div>
                                        <div class="cell auto">
                                            <p class="single-clinic-address">
	                                            <?= $address['formatted']; ?>
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
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="small-12 medium-6 large-6 cell small-order-1 large-order-2">
                <div class="acf-map"></div>
            </div>
        </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_API_KEY; ?>"></script>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            var map;
            var prevInfoWindow;

            function initMap($el) {

                // Find marker elements within map.
                let $markers = $('.single-clinic:visible');

                // Create gerenic map.
                let mapArgs = {
                    zoom: $el.data('zoom') || 16,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
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
                let address = $marker.data('address');
                let entity = $marker.data('entity-id')
                let marker = '';
                let geocoder;

                let iconBase = '<?= get_template_directory_uri(); ?>/assets/images/';

                if ( $marker.data('lat') !== undefined && $marker.data('lng') !== undefined ) {
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
                        // icon: iconBase + 'map-marker.png'
                    });
                    
                } else if ( $marker.data('address').length > 0 && $marker.data('country') === 'United States' ) {
                    // console.log( $marker.data('address') );
                    geocoder = new google.maps.Geocoder();

                    geocoder.geocode({'address': $marker.data('address')}, function(results, status) {
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
                if ( marker !== '' ) {
                    map.markers.push(marker);

                    // If marker contains HTML, add it to an infoWindow.
                    if ($marker.html()) {
                        let infowindows = [];

                        // Create info window.
                        let infowindow = new google.maps.InfoWindow({
                            content: $marker.html()
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
                        $marker.on('click', function() {
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

            // Detect filter changes and reset map w/ markers
            $(document).on('change', 'select', function() {
                delay( function() {
                    initMap( $('.acf-map') )
                }, 2200)
            })

            // Detect changes to text filters and reset map w/ markers
            $(document).on('keyup', 'input', function() {
                delay( function() {
                    initMap( $('.acf-map') )
                }, 2200)
            })

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
