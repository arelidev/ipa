/**
 * ACF Google Map field: address search on the Places API (New)
 *
 * ACF builds the field's search box on google.maps.places.Autocomplete and looks up
 * typed addresses with google.maps.Geocoder. Autocomplete belongs to the legacy
 * Places API, which Google stopped enabling on new projects in March 2025, so with
 * our key the search box fails with LegacyApiNotActivatedMapError. This keeps ACF's
 * field and its saved value format, but drives the search box with
 * AutocompleteSuggestion and Place instead.
 *
 * Map clicks and marker drags still use ACF's reverse geocoding, which needs the
 * Geocoding API enabled on the key's project.
 */
(function ($) {
    if (typeof acf === 'undefined' || !acf.getFieldType) {
        return;
    }

    const Field = acf.getFieldType('google_map');
    if (!Field) {
        return;
    }

    const FIELDS = ['formattedAddress', 'location', 'addressComponents', 'displayName', 'id'];
    const proto = Field.prototype;

    // ACF only creates the legacy Autocomplete when google.maps.places.Autocomplete
    // exists, so hide that class while each map is built. Repeater rows build their
    // maps later, so this has to wrap every call rather than run once.
    const initializeMap = proto.initializeMap;
    proto.initializeMap = function () {
        const places = google.maps.places;
        const legacy = places && places.hasOwnProperty('Autocomplete') ? places.Autocomplete : null;

        if (legacy) {
            delete places.Autocomplete;
        }

        try {
            return initializeMap.apply(this, arguments);
        } finally {
            if (legacy) {
                places.Autocomplete = legacy;
            }
        }
    };

    // ACF's Enter key and search icon look up typed text with the Geocoder. Use Places
    // Text Search instead. "lat,lng" searches still go to ACF's searchPosition().
    proto.searchAddress = function (address) {
        if (!address) {
            return;
        }

        const latLng = address.split(',');
        if (latLng.length === 2) {
            const lat = parseFloat(latLng[0]);
            const lng = parseFloat(latLng[1]);
            if (lat && lng) {
                return this.searchPosition(lat, lng);
            }
        }

        this.setState('loading');

        google.maps.importLibrary('places')
            .then(({Place}) => {
                const request = {textQuery: address, fields: FIELDS, maxResultCount: 1};
                const bounds = this.map.getBounds();
                if (bounds) {
                    request.locationBias = bounds;
                }

                return Place.searchByText(request);
            })
            .then(({places}) => {
                this.setState('');

                if (!places.length) {
                    return notFound(this, 'ZERO_RESULTS');
                }

                const val = this.parseResult(toPlaceResult(places[0]));

                // Match ACF: keep the address as the user typed it.
                val.address = address;
                this.val(val);
            })
            .catch((error) => {
                this.setState('');
                notFound(this, error.message);
            });
    };

    function notFound(field, status) {
        field.showNotice({
            text: acf.__('Location not found: %s').replace('%s', status),
            type: 'warning',
        });
    }

    acf.addAction('google_map_init', function (map, marker, field) {
        addSuggestions(field);
    });

    /**
     * Shape a Place (New) like the legacy PlaceResult that ACF's parseResult() reads,
     * so saved values keep the same keys as before.
     */
    function toPlaceResult(place) {
        return {
            formatted_address: place.formattedAddress,
            geometry: {location: place.location},
            address_components: (place.addressComponents || []).map((component) => ({
                long_name: component.longText,
                short_name: component.shortText,
                types: component.types,
            })),
            place_id: place.id,
            name: place.displayName,
        };
    }

    /**
     * Suggestion dropdown under the field's search box.
     */
    function addSuggestions(field) {
        const $input = field.$search();
        const $list = $('<ul class="ipa-places-list" role="listbox" hidden></ul>').insertAfter($input);

        let predictions = [];
        let active = -1;
        let token = null;
        let timer = null;
        let latest = 0;

        $input.attr({autocomplete: 'off', role: 'combobox', 'aria-autocomplete': 'list', 'aria-expanded': 'false'});

        function isOpen() {
            return !$list.prop('hidden');
        }

        function close() {
            $list.prop('hidden', true).empty();
            $input.attr('aria-expanded', 'false');
            predictions = [];
            active = -1;
        }

        function highlight(index) {
            active = index;
            $list.children().removeClass('is-active').eq(index).addClass('is-active');
        }

        function render(suggestions) {
            close();

            predictions = suggestions.map((suggestion) => suggestion.placePrediction).filter(Boolean);
            if (!predictions.length) {
                return;
            }

            predictions.forEach((prediction, index) => {
                const main = prediction.mainText ? prediction.mainText.toString() : prediction.text.toString();
                const secondary = prediction.secondaryText ? prediction.secondaryText.toString() : '';

                $('<li role="option"></li>')
                    .append($('<strong></strong>').text(main))
                    .append(secondary ? $('<span></span>').text(secondary) : null)
                    // mousedown, not click, so the input's blur doesn't close the list first.
                    .on('mousedown', (e) => {
                        e.preventDefault();
                        select(index);
                    })
                    .on('mouseenter', () => highlight(index))
                    .appendTo($list);
            });

            $list.prop('hidden', false);
            $input.attr('aria-expanded', 'true');
        }

        function suggest() {
            const input = $input.val().trim();
            const saved = field.val();
            const request = ++latest;

            if (!input || (saved && input === saved.address)) {
                return close();
            }

            google.maps.importLibrary('places')
                .then(({AutocompleteSuggestion, AutocompleteSessionToken}) => {
                    // One token per typing session, closed by the details request in select().
                    token = token || new AutocompleteSessionToken();

                    const args = {input: input, sessionToken: token};
                    const bounds = field.map.getBounds();
                    if (bounds) {
                        args.locationBias = bounds;
                    }

                    return AutocompleteSuggestion.fetchAutocompleteSuggestions(args);
                })
                .then(({suggestions}) => {
                    // Drop responses that arrive after a newer request or after the user left the box.
                    if (request === latest && $input.is(':focus')) {
                        render(suggestions);
                    }
                })
                .catch((error) => {
                    close();
                    console.warn('Address suggestions failed:', error);
                });
        }

        function select(index) {
            const prediction = predictions[index];
            close();

            if (!prediction) {
                return;
            }

            // ACF's searchPlace() saves the search box text as the address.
            $input.val(prediction.text.toString());
            field.setState('loading');

            const place = prediction.toPlace();
            token = null;

            place.fetchFields({fields: FIELDS})
                .then(() => {
                    field.setState('');
                    field.searchPlace(toPlaceResult(place));
                })
                .catch((error) => {
                    field.setState('');
                    notFound(field, error.message);
                });
        }

        $input.on('input', () => {
            clearTimeout(timer);
            timer = setTimeout(suggest, 200);
        });

        // Bound on the input so it runs before ACF's delegated keydown, which blurs the box on Enter.
        $input.on('keydown', (e) => {
            if (e.which === 40 && isOpen()) {
                e.preventDefault();
                highlight((active + 1) % predictions.length);
            } else if (e.which === 38 && isOpen()) {
                e.preventDefault();
                highlight(active <= 0 ? predictions.length - 1 : active - 1);
            } else if (e.which === 27 && isOpen()) {
                e.preventDefault();
                close();
            } else if (e.which === 13) {
                e.preventDefault();

                if (isOpen() && active > -1) {
                    return select(active);
                }

                const value = $input.val().trim();
                const saved = field.val();

                clearTimeout(timer);
                latest++;
                close();

                if (value && !(saved && value === saved.address)) {
                    field.searchAddress(value);
                }
            }
        });

        $input.on('blur', () => {
            clearTimeout(timer);
            latest++;
            close();
        });
    }

    $('<style>').text(
        '.acf-google-map .ipa-places-list{position:absolute;top:100%;left:-1px;right:-1px;z-index:1000;margin:0;padding:4px 0;list-style:none;background:#fff;border:1px solid #ccd0d4;box-shadow:0 3px 6px rgba(0,0,0,.15)}' +
        '.acf-google-map .ipa-places-list[hidden]{display:none}' +
        '.acf-google-map .ipa-places-list li{margin:0;padding:6px 10px;font-size:13px;line-height:1.4;cursor:pointer;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}' +
        '.acf-google-map .ipa-places-list li.is-active{background:#f0f6fc}' +
        '.acf-google-map .ipa-places-list span{margin-left:6px;color:#646970}'
    ).appendTo('head');
})(jQuery);
