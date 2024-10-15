jQuery(document).ready(function ($) {
    let map;
    let init;
    let prevInfoWindow;

    const acfMap = $('.acf-map');

    /**
     * Initiate the map
     * @param $el
     * @return {*}
     */
    function initMap($el) {

        // Find marker elements within map.
        let $markers = $('.single-clinic:visible');

        // Create generic map.
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

        // Add a marker cluster to manage the markers.
        let markerCluster = new MarkerClusterer(map, map.markers, {
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

    /**
     * Add map markers
     * @param $marker
     * @param map
     */
    function initMarker($marker, map) {
        // Extract the address data from the 'data-addresses' attribute
        let addresses = $marker.data('addresses'); // This is an array of objects
        let entity = $marker.data('entity-id');
        let type = $marker.data('type');
        let icon;
        let zIndex = 1;

        // Define icons based on the marker type and other criteria
        let iconBase = '/wp-content/themes/ipa/assets/images/icon-map-';
        if (type === 'clinic') {
            icon = iconBase + 'clinic-gold.png';
            zIndex = 100;
        } else {
            icon = iconBase + 'cfmt-gray.png'; // Default icon
        }

        // Iterate over the array of addresses to create markers
        addresses.forEach(function (address, index) {
            let lat = parseFloat(address.lat);
            let lng = parseFloat(address.lng);

            // Check if latitude and longitude are valid
            if (!isNaN(lat) && !isNaN(lng)) {
                let latLng = {lat: lat, lng: lng};

                // Create a Google Maps marker
                let marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                    entity: entity,
                    icon: icon,
                    zIndex: zIndex
                });

                // Store the marker in the map for future reference
                map.markers.push(marker);

                // Handle single address case
                if (addresses.length === 1) {
                    // Make the entire card clickable if there is only one address
                    $marker.on('click', function () {
                        map.setCenter(marker.getPosition());
                        map.setZoom(14); // Adjust zoom level as needed

                        // Optionally, trigger the marker's click event to open the info window
                        google.maps.event.trigger(marker, 'click');
                    });
                } else {
                    // For multiple addresses, bind the click event to each specific address element
                    let $addressElement = $marker.find('.single-clinic-inner-info-address').eq(index); // Target the corresponding address div

                    // Bind the click event to the corresponding address
                    $addressElement.on('click', function () {
                        map.setCenter(marker.getPosition());
                        map.setZoom(14); // Adjust zoom level as needed

                        // Optionally, trigger the marker's click event to open the info window
                        google.maps.event.trigger(marker, 'click');
                    });
                }

                // If the marker contains HTML, add it to an infoWindow
                if ($marker.html()) {
                    let windowContent = '';

                    // Create a clone of the marker's content for the infoWindow
                    let html = $marker.clone();

                    // Modify this part to filter out inactive addresses if there are two or more
                    if (addresses.length > 1) {
                        // Find all address elements within the cloned marker HTML
                        let $addressElements = $(html).find('.single-clinic-inner-info-address');

                        // Loop through all address elements and remove the inactive one(s)
                        $addressElements.each(function (index) {
                            // If this address doesn't match the active marker's coordinates, remove it
                            let lat = addresses[index].lat;
                            let lng = addresses[index].lng;

                            if (lat !== marker.getPosition().lat() || lng !== marker.getPosition().lng()) {
                                $(this).parent().parent().remove(); // Remove the non-active address
                            }
                        });
                    }

                    // Set the remaining content (only the active address) as the InfoWindow content
                    windowContent = $(html).html();

                    // Create the InfoWindow
                    let infowindow = new google.maps.InfoWindow({
                        content: windowContent
                    });

                    // Show the InfoWindow when the marker is clicked
                    google.maps.event.addListener(marker, 'click', function () {
                        // Close previously opened InfoWindow, if any
                        if (prevInfoWindow) {
                            prevInfoWindow.close();
                        }

                        // Open the new InfoWindow with the filtered content
                        infowindow.open(map, marker);

                        // Store the new InfoWindow as the previously opened one
                        prevInfoWindow = infowindow;
                    });
                }
            } else {
                console.log('Invalid latitude or longitude for entity: ' + entity);
            }
        });
    }

    /**
     * Center the map
     * @param map
     */
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

    acfMap.each(function () {
        let map = initMap($(this));
    });

    if (acfMap.length) {
        bindMapEvent()
    }

    init = initMap

    // Delay function
    let delay = (function () {
        let timer = 0;
        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

    // Mixitup filter
    let filterSelect = $('#clinics-filter-select');
    let stateFilter = $('#clinics-filter-state');
    let zipFilter = $('#clinics-filter-zip')
    let certFilter = $('#clinics-filter-certification')
    let instructorFilter = $('#clinics-filter-instructor')
    let mixerContainer = '.ipa-clinic-card-wrapper';
    let clearButton = $('.clear-button')

    if ($(mixerContainer).length) {
        let mixer = mixitup(mixerContainer);
    }

    let inputText;
    let $matching = $();
    let entities = [];

    // Filter Bindings
    filterSelect.on('change', function () {
        zipFilter.val('')
        filterMix()
    });
    stateFilter.on('change', function () {
        zipFilter.val('')
        filterMix()
    });
    zipFilter.keyup(function () {
        clearFilters()
        delay(function () {
            geocoder = new google.maps.Geocoder();

            geocoder.geocode({'address': zipFilter.val()}, function (results, status) {
                if (status === 'OK') {
                    map.setCenter(results[0].geometry.location);
                    map.setZoom(13)
                } else {
                    console.log('Geocode was not successful for the following reason: ' + status);
                }
            });
        }, 300)
    });
    certFilter.on('change', function () {
        zipFilter.val('')
        filterMix()
    });
    instructorFilter.keyup(function () {
        delay(function () {
            zipFilter.val('')
            filterMix()
        }, 300)
    });
    clearButton.on('click', function () {
        clearFilters()
        zipFilter.val('')
        filterMix()
    })

    // Clear all filters
    function clearFilters() {
        filterSelect.val('')
        stateFilter.val('all')
        instructorFilter.val('')
    }

    // Re-initialize the map
    function reInitMap() {
        init(acfMap)
        bindMapEvent()
    }

    /**
     * Filter function
     * @param map
     */
    function filterMix(map = false) {

        // Delay function invoked to make sure user stopped typing
        delay(function () {
            let mixer = mixitup(mixerContainer);

            $('.mix').each(function () {
                $matching = $matching.add(this);

                if (map === true && !entities.includes(parseInt($(this).attr('data-entity-id')))) {
                    $matching = $matching.not(this);
                }

                if (filterSelect.val() && filterSelect.val() !== 'all') {
                    if (!$(this).attr('data-type') || !$(this).attr('data-type').match(filterSelect.val().toLowerCase())) {
                        $matching = $matching.not(this);
                    }
                }

                if (stateFilter.val() && stateFilter.val() !== 'all') {
                    if (!$(this).attr('data-state') || !$(this).attr('data-state').match(stateFilter.val())) {
                        $matching = $matching.not(this);
                    }
                }

                if (zipFilter.val() && zipFilter.val() !== '') {
                    // Don't filter mix, instead rely on map zoom to filter

                    // if (!$(this).attr('data-zip') || !$(this).attr('data-zip').match( zipFilter.val().replace(/\s+/g, '-').toLowerCase() )) {
                    //     $matching = $matching.not(this);
                    // }
                }

                if (certFilter.val() && certFilter.val() !== 'all') {
                    if (!$(this).attr('data-certification') || !$(this).attr('data-certification').match(certFilter.val())) {
                        $matching = $matching.not(this);
                    }
                }

                if (instructorFilter.val() && instructorFilter.val() !== '') {
                    if (!$(this).attr('data-title') || $(this).attr('data-title').toLowerCase().indexOf(instructorFilter.val().toLowerCase()) === -1) {
                        $matching = $matching.not(this);
                    }
                }
            });

            mixer.filter($matching);

            if (!map) {
                delay(function () {
                    reInitMap()
                }, 1000)
            }

        }, 200);
    }

    // Bind map events to mix cards
    function bindMapEvent() {
        if (typeof google !== 'undefined') {
            google.maps.event.addListener(map, 'bounds_changed', function () {
                delay(function () {
                    entities = [];
                    // Check available markers on map, and if not on map, hide the card on the left via mix
                    map.markers.forEach(function (marker) {
                        if (map.getBounds().contains(marker.getPosition())) {
                            entities.push($(marker)[0].entity)
                        }
                    })
                    filterMix(true)
                }, 500)
            })
        }
    }
});