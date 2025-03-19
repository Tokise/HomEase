<?php
require_once SRC_PATH . '/controllers/ServiceProviderController.php';

/**
 * Provider Controller
 * 
 * This is a routing class that extends ServiceProviderController.
 * Used to handle /provider/* routes.
 */
class ProviderController extends ServiceProviderController {
    // Inherits all functionality from ServiceProviderController
    
    /**
     * Service management routes
     */
    public function services($action = 'index', $id = null) {
        switch ($action) {
            case 'add':
                $this->add();
                break;
            case 'create':
                $this->createService();
                break;
            case 'edit':
                $this->editService($id);
                break;
            case 'update':
                $this->updateService($id);
                break;
            case 'delete':
                $this->deleteService($id);
                break;
            case 'toggle':
                $this->toggleService($id);
                break;
            default:
                parent::services();
                break;
        }
    }
    
    /**
     * Delete a service
     */
    public function deleteService($id) {
        $this->requireRole(ROLE_PROVIDER);
        
        // Verify service ownership
        $service = $this->serviceModel->findById($id);
        if (!$service || $service['provider_id'] != $_SESSION['user_id']) {
            $_SESSION['flash_message'] = 'Unauthorized access';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/provider/services');
            return;
        }
        
        if ($this->serviceModel->delete($id)) {
            $_SESSION['flash_message'] = 'Service deleted successfully';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'Failed to delete service';
            $_SESSION['flash_type'] = 'danger';
        }
        
        $this->redirect(APP_URL . '/provider/services');
    }
    
    /**
     * Toggle service status (active/inactive)
     */
    public function toggleService($id) {
        $this->requireRole(ROLE_PROVIDER);
        
        // Verify service ownership
        $service = $this->serviceModel->findById($id);
        if (!$service || $service['provider_id'] != $_SESSION['user_id']) {
            $_SESSION['flash_message'] = 'Unauthorized access';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/provider/services');
            return;
        }
        
        // Toggle status
        $newStatus = $service['is_active'] ? 0 : 1;
        
        if ($this->serviceModel->update($id, ['is_active' => $newStatus])) {
            $statusText = $newStatus ? 'activated' : 'deactivated';
            $_SESSION['flash_message'] = "Service $statusText successfully";
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'Failed to update service status';
            $_SESSION['flash_type'] = 'danger';
        }
        
        $this->redirect(APP_URL . '/provider/services');
    }
} 