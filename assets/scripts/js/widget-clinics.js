jQuery(document).ready(function ($) {
    let map;
    let init;
    let prevInfoWindow;
    let activeFilters = {};

    // State codes to full names mapping
    const stateMap = {
        'AL': 'Alabama',
        'AK': 'Alaska',
        'AZ': 'Arizona',
        'AR': 'Arkansas',
        'CA': 'California',
        'CO': 'Colorado',
        'CT': 'Connecticut',
        'DE': 'Delaware',
        'DC': 'District of Columbia',
        'FL': 'Florida',
        'GA': 'Georgia',
        'HI': 'Hawaii',
        'ID': 'Idaho',
        'IL': 'Illinois',
        'IN': 'Indiana',
        'IA': 'Iowa',
        'KS': 'Kansas',
        'KY': 'Kentucky',
        'LA': 'Louisiana',
        'ME': 'Maine',
        'MD': 'Maryland',
        'MA': 'Massachusetts',
        'MI': 'Michigan',
        'MN': 'Minnesota',
        'MS': 'Mississippi',
        'MO': 'Missouri',
        'MT': 'Montana',
        'NE': 'Nebraska',
        'NV': 'Nevada',
        'NH': 'New Hampshire',
        'NJ': 'New Jersey',
        'NM': 'New Mexico',
        'NY': 'New York',
        'NC': 'North Carolina',
        'ND': 'North Dakota',
        'OH': 'Ohio',
        'OK': 'Oklahoma',
        'OR': 'Oregon',
        'PA': 'Pennsylvania',
        'RI': 'Rhode Island',
        'SC': 'South Carolina',
        'SD': 'South Dakota',
        'TN': 'Tennessee',
        'TX': 'Texas',
        'UT': 'Utah',
        'VT': 'Vermont',
        'VA': 'Virginia',
        'WA': 'Washington',
        'WV': 'West Virginia',
        'WI': 'Wisconsin',
        'WY': 'Wyoming'
    };

    // Create reverse mapping from state names to codes
    const stateNameToCode = {};
    for (const code in stateMap) {
        if (stateMap.hasOwnProperty(code)) {
            stateNameToCode[stateMap[code].toLowerCase()] = code;
        }
    }

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

        // Set state data attribute if not already set
        if (!$marker.attr('data-state') && addresses && addresses.length > 0 && addresses[0].state) {
            let stateValue = addresses[0].state;

            // Store the state value as is, the comparison logic will handle matching
            $marker.attr('data-state', stateValue);
        }

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
        map = initMap($(this));
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

    /**
     * Apply all active filters to the clinic cards
     * @param {boolean} fromMap - Whether the filter is being applied from map bounds change
     */
    function filterMix(fromMap = false) {
        const $clinics = $('.single-clinic');

        $clinics.each(function() {
            const $clinic = $(this);
            let show = true;

            // Filter by clinic type
            if (activeFilters.type && activeFilters.type !== '') {
                if (!$clinic.data('type').includes(activeFilters.type)) {
                    show = false;
                }
            }

            // Filter by state
            if (activeFilters.state && activeFilters.state !== 'all') {
                const clinicState = $clinic.data('state');
                const stateCode = activeFilters.state;
                const stateName = stateMap[stateCode];

                // Match either by state code or full state name
                if (clinicState !== stateCode && clinicState !== stateName) {
                    show = false;
                }
            }

            // Filter by name
            if (activeFilters.name && activeFilters.name !== '') {
                const clinicName = $clinic.data('title') || '';
                if (!clinicName.toLowerCase().includes(activeFilters.name.toLowerCase())) {
                    show = false;
                }
            }

            // Filter by zipcode
            if (activeFilters.zipcode && activeFilters.zipcode !== '') {
                const addresses = $clinic.data('addresses') || [];
                let hasMatchingZip = false;

                // Check all addresses for matching zipcode
                for (let i = 0; i < addresses.length; i++) {
                    const address = addresses[i];
                    if (address.post_code && address.post_code.includes(activeFilters.zipcode)) {
                        hasMatchingZip = true;
                        break;
                    }
                }

                if (!hasMatchingZip) {
                    show = false;
                }
            }

            // Apply the visibility
            if (show) {
                $clinic.show();
            } else {
                $clinic.hide();
            }
        });

        // Insert "no results" message and logic after filtering and before checking fromMap
        const anyVisible = $clinics.filter(':visible').length > 0;

        if (!anyVisible) {
            $('.clinics-no-results').remove(); // Remove any previous message
            $('.acf-map').before('<div class="clinics-no-results callout warning">No clinics match your search criteria. Showing all clinics instead.</div>');

            $clinics.show(); // Show all clinics again

            if (!fromMap && map) {
                acfMap.each(function() {
                    map = init($(this)); // Reinitialize map with all clinics
                });
            }

            return; // Prevent reapplying map logic again below
        } else {
            $('.clinics-no-results').remove(); // Remove message if results are found
        }

        // Refresh the map if not filtering from map bounds change
        if (!fromMap && map) {
            // Reinitialize the map with only visible clinics
            acfMap.each(function() {
                map = init($(this));
            });
        }
    }

    // Set up event handlers for the filters
    $('#clinics-filter-select').on('change', function() {
        activeFilters.type = $(this).val();
        filterMix();
    });

    $('#clinics-filter-state').on('change', function() {
        activeFilters.state = $(this).val();
        filterMix();
    });

    $('#clinics-filter-instructor').on('input', function() {
        activeFilters.name = $(this).val().trim();
        filterMix();
    });

    $('#clinics-filter-zip').on('input', function() {
        activeFilters.zipcode = $(this).val().trim();
        filterMix();
    });

    // Clear all filters when clear button is clicked
    $('.clear-button').on('click', function(e) {
        e.preventDefault();

        // Reset filter fields
        $('#clinics-filter-select').val('');
        $('#clinics-filter-state').val('all');
        $('#clinics-filter-instructor').val('');
        $('#clinics-filter-zip').val('');

        // Clear active filters
        activeFilters = {};

        // Show all clinics
        $('.single-clinic').show();

        // Refresh the map
        acfMap.each(function() {
            map = init($(this));
        });
    });
});