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
        // Get position from marker.
        let address = $marker.data('address')
        let entity = $marker.data('entity-id')
        let type = $marker.data('type')
        let cfmt = $marker.data('cfmt')
        let cafmt = $marker.data('cafmt')
        let fellow = $marker.data('fellow')
        let marker = '';
        let geocoder;
        let icon;
        let zIndex = 1;

        let iconBase = '/wp-content/themes/ipa/assets/images/icon-map-';
        if (type === 'clinic') {
            icon = iconBase + 'clinic-gold.png'
            zIndex = 100;
        } else if (fellow === 1) {
            icon = iconBase + 'fellowship-blue.png'
        } else if (cafmt === 1) {
            icon = iconBase + 'member.png'
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

                let existing = map.markers.filter(mark => mark.getPosition().lat() === marker.getPosition().lat() && mark.getPosition().lng() === marker.getPosition().lng())
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
                        if ( $mark.icon.indexOf( iconBase + 'clinic-gold.png' ) !== -1 ) {
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
        delay( function() {
            geocoder = new google.maps.Geocoder();

            geocoder.geocode({'address': zipFilter.val()}, function(results, status) {
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
        delay( function() {
            zipFilter.val('')
            filterMix()
        }, 300)
    });
    clearButton.on('click', function() {
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

    $('#ipa-clinic-card-wrapper .mix').on('click', function() {
        $(this).find('.accordion').foundation('toggle', $(this).find('.accordion-content'))
    })

    /**
     * Filter function
     * @param map
     */
    function filterMix( map = false ) {

        // Delay function invoked to make sure user stopped typing
        delay(function () {
            let mixer = mixitup(mixerContainer);

            $('.mix').each(function () {
                $matching = $matching.add(this);

                if (map === true && !entities.includes( parseInt($(this).attr('data-entity-id')) ) ) {
                    $matching = $matching.not(this);
                }

                if ( filterSelect.val() && filterSelect.val() !== 'all' ) {
                    if (!$(this).attr('data-type') || !$(this).attr('data-type').match( filterSelect.val().toLowerCase() )) {
                        $matching = $matching.not(this);
                    }
                }

                if ( stateFilter.val() && stateFilter.val() !== 'all' ) {
                    if (!$(this).attr('data-state') || !$(this).attr('data-state').match( stateFilter.val() )) {
                        $matching = $matching.not(this);
                    }
                }

                if ( zipFilter.val() && zipFilter.val() !== '' ) {
                    // Don't filter mix, instead rely on map zoom to filter

                    // if (!$(this).attr('data-zip') || !$(this).attr('data-zip').match( zipFilter.val().replace(/\s+/g, '-').toLowerCase() )) {
                    //     $matching = $matching.not(this);
                    // }
                }

                if ( certFilter.val() && certFilter.val() !== 'all' ) {
                    if (!$(this).attr('data-certification') || !$(this).attr('data-certification').match( certFilter.val() )) {
                        $matching = $matching.not(this);
                    }
                }

                if ( instructorFilter.val() && instructorFilter.val() !== '' ) {
                    if (!$(this).attr('data-title') || $(this).attr('data-title').toLowerCase().indexOf( instructorFilter.val().toLowerCase() ) === -1 ) {
                        $matching = $matching.not(this);
                    }
                }
            });

            mixer.filter($matching);

            if (!map) {
                delay( function() {
                    reInitMap()
                }, 1000)
            }

        }, 200);
    }

    // Bind map events to mix cards
    function bindMapEvent() {
        if ( typeof google !== 'undefined') {
            google.maps.event.addListener(map, 'bounds_changed', function() {
                delay( function() {
                    entities = [];
                    // Check available markers on map, and if not on map, hide the card on the left via mix
                    map.markers.forEach(function (marker) {
                        if (map.getBounds().contains(marker.getPosition())) {
                            entities.push( $(marker)[0].entity )
                        }
                    })
                    filterMix( true )
                }, 500)
            })
        }
    }
});