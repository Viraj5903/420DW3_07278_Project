<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project PermissionsController.php
 *
 * This file defines the PermissionsController class, responsible for handling permission-related HTTP requests.
 * It interacts with the PermissionsService class to perform CRUD operations on permission data.
 *
 * This file contains the PermissionsController class, which handles permission-related HTTP requests.
 * It interacts with the PermissionsService class to perform CRUD operations on permission data.
 *
 * @author Viraj Patel
 * @since 2024-04-04
 */

namespace Viraj\Project\Controllers;

use JsonException;
use Teacher\GivenCode\Abstracts\AbstractController;
use Teacher\GivenCode\Exceptions\RequestException;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Viraj\Project\Services\PermissionsService;

/**
 * Controller class for handling permission-related HTTP requests.
 */
class PermissionsController extends AbstractController {
    
    // Class properties.
    private PermissionsService $permissionsService; // PermissionsService object for handling permission-related operations.
    
    /**
     * Constructor for PermissionsController class.
     * Initializes PermissionsService object for handling permission-related operations.
     */
    public function __construct() {
        parent::__construct(); // Call the constructor of the parent class AbstractController.
        $this->permissionsService = new PermissionsService(); // Initialize PermissionsService object.
    }
    
    /**
     * Handles the GET request to retrieve permission data by ID.
     *
     * @return void
     * @throws RequestException If the request is malformed or missing required parameters.
     * @throws RuntimeException If a database connection error occurs.
     * @throws ValidationException If validation of retrieved data fails.
     */
    public function get() : void {
        
        // Retrieve permission ID from request parameters.
        if (empty($_REQUEST["id"])) { // Check if the "id" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [id] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["id"])) { // Check if the "id" parameter is not numeric.
            throw new RequestException("Bad request: parameter [id] value [" . $_REQUEST["id"] . "] is not numeric.", 400);
        }
        $int_id = (int) $_REQUEST["id"]; // Convert the "id" parameter. to an integer.
        
        // Get permission data by ID from the service layer.
        $instance = $this->permissionsService->getPermissionById($int_id); // Retrieve permission data by ID.
        
        // Output permission data as JSON response.
        header("Content-Type: application/json;charset=UTF-8"); // Set response header content type.
        // echo $instance->toJson(); // Output permission data as JSON.
        echo json_encode($instance->toArray());
    }
    
    /**
     * Handles the POST request to create a new permission.
     *
     * @return void
     * @throws RequestException If the request is malformed or missing required parameters.
     * @throws RuntimeException If a database connection error occurs.
     * @throws ValidationException If validation of permission data fails.
     */
    public function post() : void {
        
        // Validate request parameters.
        if (empty($_REQUEST["unique_permission"])) { // Check if the "unique_permission" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [unique_permission] not found in the request.", 400);
        }
        if (empty($_REQUEST["permission_name"])) { // Check if the "permission_name" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [permission_name] not found in the request.", 400);
        }
        if (empty($_REQUEST["description"])) { // Check if the "description" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [description] not found in the request.", 400);
        }
        
        // NOTE: no need for validation of the string lengths here, as that is done by the setter methods of the PermissionDTO class used when creating a PermissionDTO instance in the create method of PermissionsService.
        
        // Create new permission using provided data.
        $instance = $this->permissionsService->createPermission($_REQUEST["unique_permission"], $_REQUEST["permission_name"], $_REQUEST["description"]); // Create new permission.
        
        // Output newly created permission data as JSON response.
        header("Content-Type: application/json;charset=UTF-8"); // Set response header content type.
        // echo $instance->toJson(); // Output newly created permission data as JSON.
        echo json_encode($instance->toArray());
    }
    
    /**
     * Handles the PUT request to update an existing permission.
     *
     * @return void
     * @throws RequestException If the request is malformed or missing required parameters.
     * @throws RuntimeException If a database connection error occurs.
     * @throws ValidationException If validation of permission data fails.
     */
    public function put() : void {
        /*
        * PUT request handler is designed to update a permission entity record in the database
        * and return it to the client for handling client-side.
        *
        * NOTE: PHP does not always parse PUT and DELETE requests. It must be done manually by reading
        * the PHP://input data stream.
        *
        * It expects the required data attributes for a permission entity as well as the ID as JSON request data  and
        * returns the updated record data also as JSON.
        */
        
        // As stated, we need to manually parse the input content of PUT and DELETE requests.
        // For this PUT update permission, that is application/json content, so we use json_decode()
        
        // Parse JSON request data.
        $request_contents = file_get_contents('php://input'); // Read raw input data.
        try {
            $_REQUEST = json_decode($request_contents, true, 512, JSON_THROW_ON_ERROR); // Decode JSON data.
        } catch (JsonException $json_excep) {
            throw new RequestException("Invalid request contents format. Valid JSON is required.", 400, [], 400, $json_excep);
        }
        
        // Validate request parameters.
        if (empty($_REQUEST["id"])) { // Check if the "id" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [id] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["id"])) { // Check if the "id" parameter is not numeric.
            throw new RequestException("Bad request: parameter [id] value [" . $_REQUEST["id"] . "] is not numeric.", 400);
        }
        
        if (empty($_REQUEST["unique_permission"])) { // Check if the "unique_permission" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [unique_permission] not found in the request.", 400);
        }
        
        if (empty($_REQUEST["permission_name"])) { // Check if the "permission_name" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [permission_name] not found in the request.", 400);
        }
        
        if (empty($_REQUEST["description"])) { // Check if the "description" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [description] not found in the request.", 400);
        }
        
        // NOTE: no need for validation of the string lengths here, as that is done by the setter methods of the
        // PermissionDTO class used when creating an PermissionDTO instance in the creation method of PermissionsService.
        
        // Update existing permission using provided data.
        $int_id = (int) $_REQUEST["id"]; // Convert the "id" parameter to an integer.
        $instance = $this->permissionsService->updatePermission($int_id, $_REQUEST["unique_permission"], $_REQUEST["permission_name"], $_REQUEST["description"]); // Update existing permission.
        
        // Output updated permission data as JSON response.
        header("Content-Type: application/json;charset=UTF-8"); // Set response header content type.
        // echo $instance->toJson(); // Output updated permission data as JSON.
        echo json_encode($instance->toArray());
    }
    
    /**
     * Handles the DELETE request to delete an existing permission.
     *
     * @return void
     * @throws RequestException If the request is malformed or missing required parameters.
     * @throws RuntimeException If a database connection error occurs.
     */
    public function delete() : void {
        /*
         * DELETE request handler is designed to delete a permission entity record in the database.
         * NOTE: PHP does not always parse PUT and DELETE requests. It must be done manually by reading
         * the PHP://input data stream.
         *
         * It expects the ID of a permission entity as urlencoded request data and returns nothing in the response.
         */
        
        // As stated, we need to manually parse the input content of PUT and DELETE requests.
        // For this DELETE deletion example, that is application/x-www-form-urlencoded content.
        // We need to use parse_str() function to decode urlencoded string data instead of the json_decode() used for JSON data.
        
        // Parse request data.
        $request_contents = file_get_contents('php://input');
        parse_str($request_contents, $_REQUEST);
        
        // Validate request parameters.
        if (empty($_REQUEST["id"])) { // Check if the "id" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [id] not found in the request.", 400);
        }
        
        if (!is_numeric($_REQUEST["id"])) { // Check if the "id" parameter is not numeric.
            throw new RequestException("Bad request: parameter [id] value [" . $_REQUEST["id"] . "] is not numeric.", 400);
        }
        
        // Delete permission from database.
        $int_id = (int) $_REQUEST["id"]; // Convert the "id" parameter to an integer.
        $this->permissionsService->deletePermission($int_id); // Delete permission from the database.
        
        
        // Respond with HTTP 204 No Content status code.
        header("Content-Type: application/json;charset=UTF-8"); // Set response header content type.
        // The HTTP 204 No Content success status response code indicates that a request has succeeded, but that the client doesn't need to navigate away from its current page.
        http_response_code(204); // Set HTTP response code to 204 No Content.
    }
}