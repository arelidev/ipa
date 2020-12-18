jQuery(document).ready(function ($) {
    // Mixitup filter
    let filterSelect = $('#clinics-filter-select');
    let stateFilter = $('#clinics-filter-state');
    let zipFilter = $('#clinics-filter-zip')
    let certFilter = $('#clinics-filter-certification')
    let instructorFilter = $('#clinics-filter-instructor')
    let mixerContainer = '.ipa-clinic-card-wrapper';

    if ($(mixerContainer).length) {
        let mixer = mixitup(mixerContainer);
    }

    let inputText;
    let $matching = $();
    var entities = [];

    // Delay function
    let delay = (function () {
        let timer = 0;
        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

    // Filter Bindings
    filterSelect.on('change', function () {
        filterMix()
    });

    stateFilter.on('change', function () {
        filterMix()
    });

    zipFilter.keyup(function () {
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
        filterMix()
    });

    instructorFilter.keyup(function () {
        delay( function() {
            filterMix()
        }, 300)
    });

    bindMapEvent()

    function reinitMap() {
        init($('.acf-map'))
        bindMapEvent()
    }

    $('#ipa-clinic-card-wrapper .mix').on('click', function() { 
        $(this).find('.accordion').foundation('toggle', $(this).find('.accordion-content'))
    })

    // Filter function
    function filterMix( map = false ) {

        // Delay function invoked to make sure user stopped typing
        delay(function () {
            let mixer = mixitup(mixerContainer);

            $('.mix').each(function () {
                $matching = $matching.add(this);

                if (map == true && !entities.includes( parseInt($(this).attr('data-entity-id')) ) ) {
                    $matching = $matching.not(this);
                }

                if ( filterSelect.val() && filterSelect.val() != 'all' ) {
                    if (!$(this).attr('data-type') || !$(this).attr('data-type').match( filterSelect.val().toLowerCase() )) {
                        $matching = $matching.not(this);
                    }
                }

                if ( stateFilter.val() && stateFilter.val() != 'all' ) {
                    if (!$(this).attr('data-state') || !$(this).attr('data-state').match( stateFilter.val() )) {
                        $matching = $matching.not(this);
                    }
                }

                if ( zipFilter.val() && zipFilter.val() != '' ) {
                    // Don't filter mix, instead rely on map zoom to filter

                    // if (!$(this).attr('data-zip') || !$(this).attr('data-zip').match( zipFilter.val().replace(/\s+/g, '-').toLowerCase() )) {
                    //     $matching = $matching.not(this);
                    // }
                }

                if ( certFilter.val() && certFilter.val() != 'all' ) {
                    if (!$(this).attr('data-certification') || !$(this).attr('data-certification').match( certFilter.val() )) {
                        $matching = $matching.not(this);
                    }
                }

                if ( instructorFilter.val() && instructorFilter.val() != '' ) {
                    if (!$(this).attr('data-title') || $(this).attr('data-title').toLowerCase().indexOf( instructorFilter.val().toLowerCase() ) == -1 ) {
                        $matching = $matching.not(this);
                    }
                }
            });
            mixer.filter($matching);

            if (!map) {
                delay( function() {
                    reinitMap()
                }, 1000)
            }
  
        }, 200);
    }

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
