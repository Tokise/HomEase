<div class="map-container">
    <div class="map-sidebar">
        <div class="map-search">
            <h2 class="sidebar-title">Find Service Providers</h2>
            
            <div class="search-form">
                <div class="form-group">
                    <label for="address">Address or Zipcode</label>
                    <div class="input-group">
                        <input type="text" id="address" class="form-control" placeholder="Enter your location">
                        <button type="button" id="useMyLocation" class="btn btn-sm btn-outline">
                            <i class="fas fa-map-marker-alt"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="radius">Distance</label>
                    <select id="radius" class="form-control">
                        <option value="5">5 km</option>
                        <option value="10" selected>10 km</option>
                        <option value="25">25 km</option>
                        <option value="50">50 km</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="category">Service Category</label>
                    <select id="category" class="form-control">
                        <option value="">All Categories</option>
                        <?php
                        // Include service categories dynamically - we'll need to add this
                        require_once SRC_PATH . '/models/ServiceCategory.php';
                        $categoryModel = new ServiceCategory();
                        $categories = $categoryModel->getAll();
                        
                        foreach ($categories as $category) {
                            echo '<option value="' . $category['id'] . '">' . htmlspecialchars($category['name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                
                <button type="button" id="searchProviders" class="btn btn-primary btn-block">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
            
            <div class="providers-list" id="providersList">
                <div class="providers-loading">
                    <i class="fas fa-circle-notch fa-spin"></i>
                    <p>Loading providers...</p>
                </div>
                <!-- Provider results will be loaded here dynamically -->
            </div>
        </div>
    </div>
    
    <div class="map-content">
        <div id="providersMap" class="map"></div>
    </div>
</div>

<div id="providerInfoWindow" class="provider-info-window" style="display: none;">
    <div class="info-window-content">
        <h3 class="provider-name"></h3>
        <div class="provider-rating">
            <div class="stars"></div>
            <span class="rating-value"></span>
        </div>
        <p class="provider-address"></p>
        <div class="provider-services">
            <h4>Services:</h4>
            <ul class="services-list"></ul>
        </div>
        <a href="#" class="provider-profile-link btn btn-sm btn-primary">View Profile</a>
    </div>
</div>

<script>
// Provider markers reference
let providerMarkers = [];
let map;
let infoWindow;
let locationMarker;

// Initialize the map when the page loads
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the map
    if (typeof google !== 'undefined') {
        initMap();
    } else {
        // Google Maps API not loaded yet
        console.error('Google Maps API not loaded');
        document.getElementById('providersMap').innerHTML = `
            <div class="map-error">
                <i class="fas fa-exclamation-triangle"></i>
                <p>Google Maps could not be loaded. Please check your internet connection or try again later.</p>
            </div>
        `;
    }
    
    // Add event listeners
    document.getElementById('searchProviders').addEventListener('click', searchProviders);
    document.getElementById('useMyLocation').addEventListener('click', useMyLocation);
    document.getElementById('address').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            searchProviders();
        }
    });
});

// Initialize the map
function initMap() {
    // Get map container
    const mapElement = document.getElementById('providersMap');
    
    // Create map instance with monochromatic style
    map = new google.maps.Map(mapElement, {
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
    });
    
    // Create info window for provider details
    infoWindow = new google.maps.InfoWindow();
    
    // Load initial providers
    loadProviders();
    
    // Initialize autocomplete for address input
    const addressInput = document.getElementById('address');
    const autocomplete = new google.maps.places.Autocomplete(addressInput);
    
    // Bias autocomplete results to the map's viewport
    autocomplete.bindTo('bounds', map);
}

// Load providers from API
function loadProviders() {
    // Clear existing markers
    clearMarkers();
    
    // Show loading state
    const providersList = document.getElementById('providersList');
    providersList.innerHTML = `
        <div class="providers-loading">
            <i class="fas fa-circle-notch fa-spin"></i>
            <p>Loading providers...</p>
        </div>
    `;
    
    // Get category filter
    const categoryId = document.getElementById('category').value;
    
    // Build API URL
    let apiUrl = `<?= APP_URL ?>/maps/getProviders`;
    if (categoryId) {
        apiUrl += `?category=${categoryId}`;
    }
    
    // Fetch providers
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                displayProviders(data.providers);
            } else {
                providersList.innerHTML = `
                    <div class="providers-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Failed to load providers. Please try again.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading providers:', error);
            providersList.innerHTML = `
                <div class="providers-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>Failed to load providers. Please try again.</p>
                </div>
            `;
        });
}

// Search providers by location
function searchProviders() {
    const address = document.getElementById('address').value;
    const radius = document.getElementById('radius').value;
    const categoryId = document.getElementById('category').value;
    
    if (!address) {
        alert('Please enter an address or use your current location.');
        return;
    }
    
    // Show loading state
    const providersList = document.getElementById('providersList');
    providersList.innerHTML = `
        <div class="providers-loading">
            <i class="fas fa-circle-notch fa-spin"></i>
            <p>Searching providers...</p>
        </div>
    `;
    
    // Geocode the address
    fetch(`<?= APP_URL ?>/maps/geocode?address=${encodeURIComponent(address)}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const location = data.location;
                
                // Center the map on this location
                map.setCenter({ lat: location.lat, lng: location.lng });
                
                // Add a marker for the search location
                if (locationMarker) {
                    locationMarker.setMap(null);
                }
                
                locationMarker = new google.maps.Marker({
                    position: { lat: location.lat, lng: location.lng },
                    map: map,
                    title: 'Your Location',
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 10,
                        fillColor: '#4285F4',
                        fillOpacity: 0.8,
                        strokeColor: '#FFFFFF',
                        strokeWeight: 2
                    }
                });
                
                // Search for providers near this location
                let apiUrl = `<?= APP_URL ?>/maps/findNearest?lat=${location.lat}&lng=${location.lng}&radius=${radius}`;
                if (categoryId) {
                    apiUrl += `&category=${categoryId}`;
                }
                
                return fetch(apiUrl);
            } else {
                throw new Error(data.message || 'Failed to geocode address');
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                displayProviders(data.providers, true);
                
                // Draw circle for radius
                if (window.radiusCircle) {
                    window.radiusCircle.setMap(null);
                }
                
                window.radiusCircle = new google.maps.Circle({
                    strokeColor: '#4285F4',
                    strokeOpacity: 0.3,
                    strokeWeight: 2,
                    fillColor: '#4285F4',
                    fillOpacity: 0.1,
                    map: map,
                    center: { lat: data.location.lat, lng: data.location.lng },
                    radius: data.location.radius * 1000 // Convert km to meters
                });
                
                // Fit bounds to include all markers and circle
                const bounds = window.radiusCircle.getBounds();
                map.fitBounds(bounds);
            } else {
                providersList.innerHTML = `
                    <div class="providers-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>${data.message || 'No providers found in this area.'}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error searching providers:', error);
            providersList.innerHTML = `
                <div class="providers-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>${error.message || 'Failed to search providers. Please try again.'}</p>
                </div>
            `;
        });
}

// Use current location
function useMyLocation() {
    if (navigator.geolocation) {
        // Show loading indicator
        document.getElementById('useMyLocation').innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                // Reverse geocode to get the address
                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({ location: { lat, lng } }, (results, status) => {
                    if (status === 'OK' && results[0]) {
                        document.getElementById('address').value = results[0].formatted_address;
                    } else {
                        document.getElementById('address').value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                    }
                    
                    // Restore button
                    document.getElementById('useMyLocation').innerHTML = '<i class="fas fa-map-marker-alt"></i>';
                    
                    // Trigger search
                    searchProviders();
                });
            },
            (error) => {
                // Restore button
                document.getElementById('useMyLocation').innerHTML = '<i class="fas fa-map-marker-alt"></i>';
                
                // Show error
                let errorMessage = 'Failed to get your location.';
                
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = 'You need to allow location access to use this feature.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = 'Location information is unavailable.';
                        break;
                    case error.TIMEOUT:
                        errorMessage = 'The request to get your location timed out.';
                        break;
                }
                
                alert(errorMessage);
            }
        );
    } else {
        alert('Geolocation is not supported by your browser.');
    }
}

// Display providers on the map and in the list
function displayProviders(providers, includeDistance = false) {
    // Clear existing markers
    clearMarkers();
    
    // Get references
    const providersList = document.getElementById('providersList');
    
    // Reset providers list
    providersList.innerHTML = '';
    
    if (providers.length === 0) {
        providersList.innerHTML = `
            <div class="providers-empty">
                <i class="fas fa-search"></i>
                <p>No service providers found. Try adjusting your search criteria.</p>
            </div>
        `;
        return;
    }
    
    // Create a bounds object to fit all markers
    const bounds = new google.maps.LatLngBounds();
    
    // Add each provider to the map and list
    providers.forEach(provider => {
        // Create marker
        const marker = new google.maps.Marker({
            position: { lat: provider.lat, lng: provider.lng },
            map: map,
            title: provider.name
        });
        
        // Add marker to tracking array
        providerMarkers.push(marker);
        
        // Extend bounds to include this marker
        bounds.extend(marker.getPosition());
        
        // Create info window content
        marker.addListener('click', () => {
            const infoWindowTemplate = document.getElementById('providerInfoWindow').cloneNode(true);
            infoWindowTemplate.style.display = 'block';
            
            infoWindowTemplate.querySelector('.provider-name').textContent = provider.name;
            infoWindowTemplate.querySelector('.provider-address').textContent = provider.address;
            
            // Set rating stars
            const starsContainer = infoWindowTemplate.querySelector('.stars');
            starsContainer.innerHTML = getStarsHTML(provider.rating);
            infoWindowTemplate.querySelector('.rating-value').textContent = provider.rating.toFixed(1);
            
            // Set services list
            const servicesList = infoWindowTemplate.querySelector('.services-list');
            servicesList.innerHTML = '';
            
            if (provider.services && provider.services.length > 0) {
                provider.services.forEach(service => {
                    const li = document.createElement('li');
                    li.textContent = `${service.name} - $${service.price}`;
                    servicesList.appendChild(li);
                });
            } else {
                const li = document.createElement('li');
                li.textContent = 'No services listed';
                servicesList.appendChild(li);
            }
            
            // Set profile link
            const profileLink = infoWindowTemplate.querySelector('.provider-profile-link');
            profileLink.href = `<?= APP_URL ?>${provider.profileUrl}`;
            
            infoWindow.setContent(infoWindowTemplate);
            infoWindow.open(map, marker);
        });
        
        // Create provider list item
        const providerItem = document.createElement('div');
        providerItem.className = 'provider-item';
        
        let distanceText = '';
        if (includeDistance && provider.distance) {
            distanceText = `<span class="provider-distance">${provider.distance} km away</span>`;
        }
        
        providerItem.innerHTML = `
            <h3 class="provider-name">${provider.name}</h3>
            ${distanceText}
            <div class="provider-rating">
                <div class="stars">${getStarsHTML(provider.rating)}</div>
                <span class="rating-value">${provider.rating.toFixed(1)}</span>
            </div>
            <p class="provider-address">${provider.address}</p>
            <a href="<?= APP_URL ?>${provider.profileUrl}" class="provider-link">View Profile</a>
        `;
        
        // Add click handler to center map on this provider
        providerItem.addEventListener('click', () => {
            map.setCenter({ lat: provider.lat, lng: provider.lng });
            map.setZoom(15);
            google.maps.event.trigger(marker, 'click');
        });
        
        providersList.appendChild(providerItem);
    });
    
    // Fit map to show all markers if we're not doing a radius search
    if (!includeDistance && providerMarkers.length > 0) {
        map.fitBounds(bounds);
    }
}

// Clear all markers from the map
function clearMarkers() {
    providerMarkers.forEach(marker => {
        marker.setMap(null);
    });
    providerMarkers = [];
}

// Generate HTML for star ratings
function getStarsHTML(rating) {
    let html = '';
    const fullStars = Math.floor(rating);
    const halfStar = rating % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);
    
    // Full stars
    for (let i = 0; i < fullStars; i++) {
        html += '<i class="fas fa-star"></i>';
    }
    
    // Half star
    if (halfStar) {
        html += '<i class="fas fa-star-half-alt"></i>';
    }
    
    // Empty stars
    for (let i = 0; i < emptyStars; i++) {
        html += '<i class="far fa-star"></i>';
    }
    
    return html;
}
</script>

<!-- Include Google Maps API with required libraries -->
<script src="https://maps.googleapis.com/maps/api/js?key=<?= htmlspecialchars($apiKey) ?>&libraries=places" async defer></script>

<style>
    .map-container {
        display: flex;
        height: calc(100vh - 120px);
        min-height: 500px;
        border-radius: var(--radius-md);
        overflow: hidden;
        margin: var(--spacing-lg) 0;
        box-shadow: var(--shadow-md);
    }
    
    .map-sidebar {
        width: 350px;
        background-color: var(--color-white);
        border-right: 1px solid var(--color-gray-200);
        overflow-y: auto;
    }
    
    .map-content {
        flex: 1;
        position: relative;
    }
    
    #providersMap {
        width: 100%;
        height: 100%;
    }
    
    .sidebar-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: var(--spacing-md);
        color: var(--color-primary);
    }
    
    .map-search {
        padding: var(--spacing-lg);
    }
    
    .input-group {
        display: flex;
        align-items: center;
    }
    
    .input-group .form-control {
        flex: 1;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    
    .input-group .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    
    .providers-list {
        margin-top: var(--spacing-lg);
    }
    
    .provider-item {
        padding: var(--spacing-md);
        border-top: 1px solid var(--color-gray-200);
        cursor: pointer;
        transition: background-color var(--transition-fast);
    }
    
    .provider-item:hover {
        background-color: var(--color-gray-100);
    }
    
    .provider-item:last-child {
        border-bottom: 1px solid var(--color-gray-200);
    }
    
    .provider-name {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: var(--spacing-xs);
    }
    
    .provider-distance {
        display: inline-block;
        font-size: 0.875rem;
        color: var(--color-primary);
        font-weight: 600;
        margin-left: var(--spacing-sm);
    }
    
    .provider-rating {
        display: flex;
        align-items: center;
        gap: var(--spacing-xs);
        margin-bottom: var(--spacing-xs);
    }
    
    .stars {
        color: #FFD700;
        font-size: 0.875rem;
    }
    
    .rating-value {
        font-size: 0.875rem;
        color: var(--color-gray-600);
    }
    
    .provider-address {
        font-size: 0.875rem;
        color: var(--color-gray-600);
        margin-bottom: var(--spacing-sm);
    }
    
    .provider-link {
        font-size: 0.875rem;
        color: var(--color-primary);
        font-weight: 500;
    }
    
    .providers-loading,
    .providers-error,
    .providers-empty {
        padding: var(--spacing-lg);
        text-align: center;
        color: var(--color-gray-600);
    }
    
    .providers-loading i,
    .providers-error i,
    .providers-empty i {
        font-size: 1.5rem;
        margin-bottom: var(--spacing-sm);
        display: block;
    }
    
    .providers-error i {
        color: #e41d3d;
    }
    
    .map-error {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(255, 255, 255, 0.9);
        padding: var(--spacing-lg);
        border-radius: var(--radius-md);
        text-align: center;
        max-width: 400px;
    }
    
    .map-error i {
        font-size: 2rem;
        color: #e41d3d;
        margin-bottom: var(--spacing-md);
        display: block;
    }
    
    /* Info Window Styling */
    .provider-info-window {
        padding: var(--spacing-sm);
        max-width: 300px;
    }
    
    .provider-info-window h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: var(--spacing-xs);
    }
    
    .provider-info-window .provider-rating {
        margin-bottom: var(--spacing-xs);
    }
    
    .provider-info-window .provider-address {
        margin-bottom: var(--spacing-sm);
    }
    
    .provider-info-window .provider-services {
        margin-bottom: var(--spacing-sm);
    }
    
    .provider-info-window h4 {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: var(--spacing-xs);
    }
    
    .provider-info-window .services-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .provider-info-window .services-list li {
        font-size: 0.875rem;
        margin-bottom: var(--spacing-xs);
    }
    
    @media (max-width: 768px) {
        .map-container {
            flex-direction: column;
            height: auto;
        }
        
        .map-sidebar {
            width: 100%;
            border-right: none;
            border-bottom: 1px solid var(--color-gray-200);
        }
        
        .map-content {
            height: 400px;
        }
    }
</style> 