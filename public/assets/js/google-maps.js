/**
 * HomEase - Google Maps Integration
 */

/**
 * Initialize Google Map on a specific element
 * 
 * @param {string} elementId - The DOM element ID for the map
 * @param {Object} options - Map configuration options
 */
function initializeMap(elementId, options = {}) {
    const mapElement = document.getElementById(elementId);
    if (!mapElement) return null;
    
    // Default options with monochromatic style
    const defaultOptions = {
        zoom: 12,
        center: { lat: 40.7128, lng: -74.0060 }, // Default to NYC
        styles: [
            { elementType: "geometry", stylers: [{ color: "#f5f5f5" }] },
            { elementType: "labels.icon", stylers: [{ visibility: "off" }] },
            { elementType: "labels.text.fill", stylers: [{ color: "#616161" }] },
            { elementType: "labels.text.stroke", stylers: [{ color: "#f5f5f5" }] },
            { featureType: "road", elementType: "geometry", stylers: [{ color: "#ffffff" }] },
            { featureType: "road.arterial", elementType: "labels.text.fill", stylers: [{ color: "#757575" }] },
            { featureType: "road.highway", elementType: "geometry", stylers: [{ color: "#dadada" }] },
            { featureType: "road.highway", elementType: "labels.text.fill", stylers: [{ color: "#616161" }] },
            { featureType: "water", elementType: "geometry", stylers: [{ color: "#c9c9c9" }] },
            { featureType: "water", elementType: "labels.text.fill", stylers: [{ color: "#9e9e9e" }] }
        ]
    };
    
    // Merge options
    const mapOptions = {...defaultOptions, ...options};
    
    // Create the map
    return new google.maps.Map(mapElement, mapOptions);
}

/**
 * Add a marker to the map
 * 
 * @param {Object} map - Google Maps instance
 * @param {Object} position - Lat/Lng position object
 * @param {string} title - Marker title
 * @param {Object} options - Additional marker options
 */
function addMarker(map, position, title, options = {}) {
    return new google.maps.Marker({
        position,
        map,
        title,
        ...options
    });
}

/**
 * Initialize a provider location map
 * 
 * @param {string} elementId - The DOM element ID for the map
 * @param {number} lat - Provider latitude
 * @param {number} lng - Provider longitude
 * @param {string} providerName - Provider name for marker
 */
function initProviderMap(elementId, lat, lng, providerName) {
    const position = { lat, lng };
    const map = initializeMap(elementId, {
        center: position,
        zoom: 15
    });
    
    if (map) {
        addMarker(map, position, providerName);
    }
    
    return map;
}

/**
 * Initialize a directions map
 * 
 * @param {string} mapElementId - The DOM element ID for the map
 * @param {string} originInputId - The origin input field ID
 * @param {string} destinationInputId - The destination input field ID
 * @param {string} directionsButtonId - The get directions button ID
 * @param {string} routeInfoId - The element ID to display route info
 */
function initDirectionsMap(mapElementId, originInputId, destinationInputId, directionsButtonId, routeInfoId) {
    const map = initializeMap(mapElementId);
    if (!map) return;
    
    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer({
        map: map,
        suppressMarkers: false,
        polylineOptions: {
            strokeColor: '#000000',
            strokeWeight: 5,
            strokeOpacity: 0.7
        }
    });
    
    // Initialize autocomplete for inputs
    const originInput = document.getElementById(originInputId);
    const destinationInput = document.getElementById(destinationInputId);
    const directionsButton = document.getElementById(directionsButtonId);
    
    if (originInput && destinationInput) {
        const originAutocomplete = new google.maps.places.Autocomplete(originInput);
        const destinationAutocomplete = new google.maps.places.Autocomplete(destinationInput);
        
        // Set bounds for better suggestions (optional)
        map.addListener('bounds_changed', () => {
            originAutocomplete.setBounds(map.getBounds());
            destinationAutocomplete.setBounds(map.getBounds());
        });
    }
    
    // Add event listener to the directions button
    if (directionsButton) {
        directionsButton.addEventListener('click', () => {
            calculateAndDisplayRoute(
                directionsService, 
                directionsRenderer, 
                originInput.value, 
                destinationInput.value,
                routeInfoId
            );
        });
    }
    
    return {
        map,
        directionsService,
        directionsRenderer
    };
}

/**
 * Calculate and display a route between two points
 */
function calculateAndDisplayRoute(directionsService, directionsRenderer, origin, destination, routeInfoId) {
    if (!origin || !destination) {
        alert('Please enter both origin and destination');
        return;
    }
    
    directionsService.route({
        origin: origin,
        destination: destination,
        travelMode: google.maps.TravelMode.DRIVING
    }, (response, status) => {
        if (status === 'OK') {
            directionsRenderer.setDirections(response);
            
            // Display route information if element exists
            if (routeInfoId) {
                const routeInfoElement = document.getElementById(routeInfoId);
                if (routeInfoElement && response.routes[0]) {
                    const route = response.routes[0];
                    const leg = route.legs[0];
                    
                    routeInfoElement.innerHTML = `
                        <div class="route-info">
                            <div class="route-distance">
                                <i class="fas fa-road"></i>
                                <strong>Distance:</strong> ${leg.distance.text}
                            </div>
                            <div class="route-duration">
                                <i class="fas fa-clock"></i>
                                <strong>Duration:</strong> ${leg.duration.text}
                            </div>
                            <div class="route-start">
                                <i class="fas fa-map-marker-alt"></i>
                                <strong>From:</strong> ${leg.start_address}
                            </div>
                            <div class="route-end">
                                <i class="fas fa-flag-checkered"></i>
                                <strong>To:</strong> ${leg.end_address}
                            </div>
                        </div>
                    `;
                }
            }
        } else {
            alert('Directions request failed due to ' + status);
        }
    });
}

/**
 * Get the user's current location
 */
function getCurrentLocation(callback) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const location = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                callback(null, location);
            },
            (error) => {
                callback(error, null);
            }
        );
    } else {
        callback(new Error('Geolocation is not supported by this browser.'), null);
    }
} 