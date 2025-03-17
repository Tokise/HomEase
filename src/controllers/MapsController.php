<?php
/**
 * Maps Controller for Google Maps integration
 */
require_once SRC_PATH . '/controllers/Controller.php';
require_once SRC_PATH . '/models/Service.php';
require_once SRC_PATH . '/models/ServiceProvider.php';

class MapsController extends Controller {
    private $serviceModel;
    private $providerModel;
    
    public function __construct() {
        $this->serviceModel = new Service();
        $this->providerModel = new ServiceProvider();
    }
    
    /**
     * Renders the map view with all service providers
     */
    public function index() {
        // Get all active service providers
        $providers = $this->providerModel->getAllActive();
        
        $this->render('maps/index', [
            'title' => 'Find Service Providers Near You',
            'providers' => $providers,
            'apiKey' => $_ENV['GOOGLE_MAPS_API_KEY'] ?? ''
        ]);
    }
    
    /**
     * API endpoint to get service providers as JSON for the map
     */
    public function getProviders() {
        // Check if category filter is applied
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        
        // Get providers based on filter
        if ($categoryId) {
            $providers = $this->providerModel->getByCategory($categoryId);
        } else {
            $providers = $this->providerModel->getAllActive();
        }
        
        // Format providers for map display
        $mapData = [];
        foreach ($providers as $provider) {
            // Only include providers with latitude and longitude
            if (!empty($provider['latitude']) && !empty($provider['longitude'])) {
                $mapData[] = [
                    'id' => $provider['id'],
                    'name' => $provider['business_name'],
                    'lat' => (float)$provider['latitude'],
                    'lng' => (float)$provider['longitude'],
                    'address' => $provider['address'],
                    'rating' => $provider['avg_rating'] ?? 0,
                    'services' => $this->serviceModel->getProviderServices($provider['id']),
                    'profileUrl' => '/provider/profile/' . $provider['id']
                ];
            }
        }
        
        // Set content type to JSON
        header('Content-Type: application/json');
        
        // Return JSON data
        echo json_encode([
            'status' => 'success',
            'providers' => $mapData
        ]);
        exit;
    }
    
    /**
     * Find nearest service providers to a location
     */
    public function findNearest() {
        // Get location parameters
        $lat = isset($_GET['lat']) ? (float)$_GET['lat'] : null;
        $lng = isset($_GET['lng']) ? (float)$_GET['lng'] : null;
        $radius = isset($_GET['radius']) ? (int)$_GET['radius'] : 10; // Default 10km radius
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        
        // Validate coordinates
        if ($lat === null || $lng === null) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Latitude and longitude are required'
            ]);
            exit;
        }
        
        // Get providers within radius
        $providers = $this->providerModel->findInRadius($lat, $lng, $radius, $categoryId);
        
        // Format providers for map display
        $mapData = [];
        foreach ($providers as $provider) {
            $mapData[] = [
                'id' => $provider['id'],
                'name' => $provider['business_name'],
                'lat' => (float)$provider['latitude'],
                'lng' => (float)$provider['longitude'],
                'address' => $provider['address'],
                'rating' => $provider['avg_rating'] ?? 0,
                'distance' => round($provider['distance'], 1), // Distance in km
                'services' => $this->serviceModel->getProviderServices($provider['id']),
                'profileUrl' => '/provider/profile/' . $provider['id']
            ];
        }
        
        // Set content type to JSON
        header('Content-Type: application/json');
        
        // Return JSON data
        echo json_encode([
            'status' => 'success',
            'location' => [
                'lat' => $lat,
                'lng' => $lng,
                'radius' => $radius
            ],
            'providers' => $mapData
        ]);
        exit;
    }
    
    /**
     * Geocode an address to coordinates
     */
    public function geocode() {
        // Get address parameter
        $address = isset($_GET['address']) ? trim($_GET['address']) : null;
        
        // Validate address
        if (empty($address)) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Address is required'
            ]);
            exit;
        }
        
        // Get API key
        $apiKey = $_ENV['GOOGLE_MAPS_API_KEY'] ?? '';
        
        if (empty($apiKey)) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Google Maps API key is not configured'
            ]);
            exit;
        }
        
        // Make request to Google Maps Geocoding API
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=' . $apiKey;
        
        $response = file_get_contents($url);
        $result = json_decode($response, true);
        
        // Check if geocoding was successful
        if ($result['status'] !== 'OK') {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Geocoding failed: ' . ($result['error_message'] ?? $result['status'])
            ]);
            exit;
        }
        
        // Extract coordinates from response
        $location = $result['results'][0]['geometry']['location'];
        
        // Return coordinates
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'location' => [
                'lat' => $location['lat'],
                'lng' => $location['lng'],
                'formatted_address' => $result['results'][0]['formatted_address']
            ]
        ]);
        exit;
    }
} 