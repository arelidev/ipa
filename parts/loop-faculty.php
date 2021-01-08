<?php
/**
 * Template part for displaying page content in page.php
 */

$faculty_name = get_query_var( 'faculty_name' );
$faculty_id   = get_query_var( 'faculty_id' );
$faculty_data = get_faculty( $faculty_id );
$faculty_data = $faculty_data[0];

$address_1 = "{$faculty_data['work_street']}";
$address_2 = "{$faculty_data['work_city']}, {$faculty_data['work_state']} {$faculty_data['work_zip']}";
?>

<article id="faculty-<?= $faculty_id; ?>" role="article" itemscope itemtype="http://schema.org/WebPage">

    <header class="article-header hero hero-standard">
        <div class="hero-inner grid-container">
            <h1 class="page-title text-center"><b><?= $faculty_data['name']; ?></b></h1>
            <h5 class="ipa-faculty-member-credentials text-center">
		        <?= $faculty_data['credentials']; ?>
            </h5>
        </div>
    </header> <!-- end article header -->

    <section class="entry-content grid-container" itemprop="text">
        <div class="grid-x grid-padding-x">
            <div class="small-12 medium-4 large-4 cell">
                <div class="ipa-faculty-member styled-container">
                    <img src="<?= get_instructor_image( $faculty_data['image'] ); ?>" class="ipa-faculty-member-image" alt="<?= $faculty_data['name']; ?>">
                    <h3 class="ipa-faculty-member-name text-center"><b><?= $faculty_data['name']; ?></b></h3>
                    <p class="ipa-faculty-member-credentials text-center text-color-medium-gray">
						<?= $faculty_data['credentials']; ?>
                    </p>
                    <div class="ipa-faculty-member-info">
	                    <?php if ( $faculty_data['FAAOMPT'] == 1 ) : ?>
                            <div class="grid-x">
                                <div class="cell small-2">
                                    <img src="<?= get_template_directory_uri(); ?>/assets/images/icon-certification.svg" class="ipa-single-card-widget-icon" alt="" loading="lazy" width="22px">
                                    <!-- <i class="far fa-user-md fa-lg"></i> -->
                                </div>
                                <div class="cell auto">
                                    <p>
                                        FMT Fellow
                                    </p>
                                </div>
                            </div>
	                    <?php endif; ?>
	                    <?php if ( $faculty_data['cfmt_honors'] == 1 ) : ?>
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
	                    <?php if ( $faculty_data['cfmt_distinction'] == 1 ) : ?>
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
	                    <?php if ( $faculty_data['current_fellow'] == 1 ) : ?>
                            <div class="grid-x">
                                <div class="cell small-2">
                                    <i class="far fa-user-md fa-lg"></i>
                                </div>
                                <div class="cell auto">
                                    <p>
                                        FMT Follow
                                    </p>
                                </div>
                            </div>
	                    <?php endif; ?>
                        <div class="grid-x">
                            <div class="cell small-2">
                                <i class="far fa-envelope fa-lg"></i>
                            </div>
                            <div class="cell auto">
                                <p class="ipa-faculty-member-email">
                                    <a href="mailto:<?= $faculty_data['work_']; ?>"><?= $faculty_data['work_email']; ?></a>
                                </p>
                            </div>
                        </div>
                        <div class="grid-x">
                            <div class="cell small-2">
                                <i class="far fa-mobile fa-lg"></i>
                            </div>
                            <div class="cell auto">
                                <p class="ipa-faculty-member-phone">
                                    <?= $faculty_data['work_telephone']; ?>
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
            </div>
            <div class="small-12 medium-8 large-7 large-offset-1 cell">
                <h5><b>About <?= $faculty_data['firstname']; ?></b></h5>
				<?= apply_filters( 'the_content', $faculty_data['bio'] ); ?>

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
                <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_API_KEY; ?>&callback=initMap"></script>
            </div>
        </div>
    </section> <!-- end article section -->

	<?php if ( $faculty_data['instructor_status'] == "1" || $faculty_data['instructor_status'] == "2" ) : ?>
        <footer class="article-footer grid-container">
            <div class="grid-x grid-padding-x">
                <div class="cell">
                    <h3><b><?= $faculty_data['firstname']; ?>'s Upcoming Courses</b></h3>
					<?php get_instructor_course_table( $faculty_id ); ?>
                </div>
            </div>
        </footer> <!-- end article footer -->
	<?php endif; ?>

</article> <!-- end article -->
