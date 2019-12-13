<?php
/**
 * Template part for displaying page content in page.php
 */

$faculty_name = get_query_var( 'faculty_name' );
$faculty_id   = get_query_var( 'faculty_id' );
$faculty_data = get_faculty( $faculty_id );
$faculty_data = $faculty_data[0];

$address_1 = "{$faculty_data['billing_street']}";
$address_2 = "{$faculty_data['billing_city']}, {$faculty_data['billing_region']} {$faculty_data['billing_postcode']}";
?>

<article id="faculty-<?= $faculty_id; ?>" role="article" itemscope itemtype="http://schema.org/WebPage">

    <header class="article-header hero hero-standard">
        <div class="hero-inner grid-container">
            <h1 class="page-title"><b><?= $faculty_data['name']; ?></b></h1>
			<?php
			// todo: get link back to faculty page
			if ( function_exists( 'yoast_breadcrumb' ) ) {
				yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
			}
			?>
        </div>
    </header> <!-- end article header -->

    <section class="entry-content grid-container" itemprop="text">
        <div class="grid-x grid-padding-x">
            <div class="small-12 medium-4 large-4 cell">
                <div class="ipa-faculty-member styled-container">
					<?php
					if ( ! empty( $image = $faculty_data['image'] ) ) :
						$image_url = FACULTY_MEMBER_IMAGE_URL . $image;
					else :
						$image_url = "https://via.placeholder.com/500x500";
					endif; ?>
                    <img src="<?= $image_url; ?>" class="ipa-faculty-member-image" alt="<?= $faculty_data['name']; ?>">
                    <h3 class="ipa-faculty-member-name text-center"><b><?= $faculty_data['name']; ?></b></h3>
                    <p class="ipa-faculty-member-credentials text-center text-color-medium-gray">
						<?= $faculty_data['credentials']; ?>
                    </p>
                    <div class="ipa-faculty-member-info">
                        <div class="grid-x">
                            <div class="cell small-2">
                                <i class="far fa-envelope fa-lg"></i>
                            </div>
                            <div class="cell auto">
                                <p class="ipa-faculty-member-email">
                                    <a href="mailto:<?= $faculty_data['email']; ?>"><?= $faculty_data['email']; ?></a>
                                </p>
                            </div>
                        </div>
                        <div class="grid-x">
                            <div class="cell small-2">
                                <i class="far fa-mobile fa-lg"></i>
                            </div>
                            <div class="cell auto">
                                <p class="ipa-faculty-member-phone">
                                    <?= $faculty_data['billing_telephone']; ?>
                                </p>
                            </div>
                        </div>
                        <div class="grid-x">
                            <div class="cell small-2">
                                <i class="far fa-map-marker-alt fa-lg"></i>
                            </div>
                            <div class="cell auto">
                                <address>
                                    <ul class="no-bullet">
                                        <li><?= $address_1; ?></li>
                                        <li><?= $address_2; ?></li>
                                    </ul>
                                </address>
                            </div>
                        </div>
                    </div>
                </div>

                <style type="text/css">
                    #map {
                        height: 350px;
                    }
                </style>
                <div id="map"></div>
                <script>
                    let geocoder;
                    let map;
                    let address = "<?= $address_1; ?>, <?= $address_2; ?>";

                    function initMap() {
                        let map = new google.maps.Map(document.getElementById('map'), {
                            zoom: 12,
                            center: {lat: -34.397, lng: 150.644}
                        });
                        geocoder = new google.maps.Geocoder();
                        codeAddress(geocoder, map);
                    }

                    function codeAddress(geocoder, map) {
                        geocoder.geocode({'address': address}, function (results, status) {
                            if (status === 'OK') {
                                map.setCenter(results[0].geometry.location);
                                let marker = new google.maps.Marker({
                                    map: map,
                                    position: results[0].geometry.location
                                });
                            } else {
                                console.log('Geocode was not successful for the following reason: ' + status);
                            }
                        });
                    }
                </script>
                <script async defer
                        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAaA_DH8QjRXzfTsTZvdusqfzNCEAGT0Ps&callback=initMap">
                </script>
            </div>
            <div class="small-12 medium-8 large-7 large-offset-1 cell">
                <h5><b>About <?= $faculty_data['firstname']; ?></b></h5>
				<?= apply_filters( 'the_content', $faculty_data['bio'] ); ?>
            </div>
        </div>
    </section> <!-- end article section -->

    <footer class="article-footer grid-container">
        <div class="grid-x grid-padding-x">
            <div class="cell">
                <h3><b><?= $faculty_data['firstname']; ?>'s Upcoming Courses</b></h3>
			    <?= do_shortcode('[ipa_courses_table limit="5" course_cat="DFA"]'); ?>
            </div>
        </div>
    </footer> <!-- end article footer -->

</article> <!-- end article -->
