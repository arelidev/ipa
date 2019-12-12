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

	?>
    <div class="ipa-clinics-widget">
        <div class="grid-x">
            <div class="small-12 medium-6 large-6 cell small-order-2 large-order-1 ipa-clinic-card-wrapper">
                <div class="grid-x grid-margin-x grid-container">
					<?php
					while ( $loop->have_posts() ) :
						$loop->the_post();
						$address = get_field( 'clinic_address' ) ?>
                        <div class="small-12 medium-6 large-6 cell single-clinic featured-clinic styled-container"
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
            function initMap($el) {

                // Find marker elements within map.
                let $markers = $('.single-clinic');

                // Create gerenic map.
                let mapArgs = {
                    zoom: $el.data('zoom') || 16,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                let map = new google.maps.Map($el[0], mapArgs);

                // Add markers.
                map.markers = [];
                $markers.each(function () {
                    initMarker($(this), map);
                });

                // Center map based on markers.
                centerMap(map);

                // Return map instance.
                return map;
            }

            function initMarker($marker, map) {
                // Get position from marker.
                let lat = $marker.data('lat');
                let lng = $marker.data('lng');
                let latLng = {
                    lat: parseFloat(lat),
                    lng: parseFloat(lng)
                };

                let iconBase = '<?= get_template_directory_uri(); ?>/assets/images/';

                // Create marker instance.
                let marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                    // icon: iconBase + 'map-marker.png'
                });

                // Append to reference for later use.
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
                            infowindows[i].close();
                        }

                        infowindow.open(map, marker);
                    });

                    google.maps.event.addListener(map, 'click', function() {
                        infowindow.close();
                    });
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