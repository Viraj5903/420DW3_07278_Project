<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project UsergroupsController.php
 *
 * This file defines the UsergroupsController class, responsible for handling user_group-related HTTP requests.
 * It interacts with the UserGroupsService class to perform CRUD operations on user_group data.
 *
 * This file contains the UsergroupsController class, which handles user_group-related HTTP requests.
 * It interacts with the UserGroupsService class to perform CRUD operations on user_group data.
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
use Viraj\Project\Services\UserGroupsService;

/**
 * Controller class for handling user_group-related HTTP requests.
 */
class UsergroupsController extends AbstractController {
    
    // Class properties.
    private UserGroupsService $groupsService; // UserGroupsService object for handling user_group-related operations.
    
    /**
     * Constructor for UsergroupsController class.
     * Initializes UserGroupsService object for handling user_group-related operations.
     */
    public function __construct() {
        parent::__construct(); // Call the constructor of the parent class AbstractController.
        $this->groupsService = new UserGroupsService(); // Initialize UserGroupsService object.
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
        $instance = $this->groupsService->getUserGroupById($int_id); // Retrieve permission data by ID.
        
        // Output permission data as JSON response.
        header("Content-Type: application/json;charset=UTF-8"); // Set response header content type.
        // echo $instance->toJson(); // Output permission data as JSON.
        echo json_encode($instance->toArray());
    }
    
    /**
     * Handles the POST request to create a new user_group.
     *
     * @return void
     * @throws RequestException If the request is malformed or missing required parameters.
     * @throws RuntimeException If a database connection error occurs.
     * @throws ValidationException If validation of user_group data fails.
     */
    public function post() : void {
        
        // Validate request parameters.
        if (empty($_REQUEST["group_name"])) { // Check if the "group_name" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [group_name] not found in the request.", 400);
        }
        /*if (empty($_REQUEST["description"])) { // Check if the "description" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [description] not found in the request.", 400);
        }*/
        
        // NOTE: no need for validation of the string lengths here, as that is done by the setter methods of the UserGroupDTO class used when creating a UserGroupDTO instance in the create method of UserGroupsService.
        
        $permissions = [];
        
        if (!empty($_REQUEST["permissions"])) {
            $permissions = array_map('intval', explode(",", $_REQUEST["permissions"]));
        }
        
        // Create new user_group using provided data.
        $instance = $this->groupsService->createUserGroup($_REQUEST["group_name"], $_REQUEST["description"], $permissions); // Create new user_group.
        
        // Output newly created user_group data as JSON response.
        header("Content-Type: application/json;charset=UTF-8"); // Set response header content type.
        // echo $instance->toJson(); // Output newly created user_group data as JSON.
        echo json_encode($instance->toArray());
    }
    
    /**
     * Handles the PUT request to update an existing user_group.
     *
     * @return void
     * @throws RequestException If the request is malformed or missing required parameters.
     * @throws RuntimeException If a database connection error occurs.
     * @throws ValidationException If validation of user_group data fails.
     */
    public function put() : void {
        
        // Parse request data.
        $request_contents = file_get_contents('php://input');
        parse_str($request_contents, $_REQUEST);
        
        
        // Validate request parameters.
        if (empty($_REQUEST["id"])) { // Check if the "id" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [id] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["id"])) { // Check if the "id" parameter is not numeric.
            throw new RequestException("Bad request: parameter [id] value [" . $_REQUEST["id"] .
                                       "] is not numeric.", 400);
        }
        
        if (empty($_REQUEST["group_name"])) { // Check if the "group_name" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [group_name] not found in the request.", 400);
        }
        
        /*if (empty($_REQUEST["description"])) { // Check if the "description" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [description] not found in the request.", 400);
        }*/
        
        // NOTE: no need for validation of the string lengths here, as that is done by the setter methods of the
        // UserGroupDTO class used when creating an UserGroupDTO instance in the creation method of UserGroupsService.
        
        // Update existing permission using provided data.
        $int_id = (int) $_REQUEST["id"]; // Convert the "id" parameter to an integer.
        
        $permissions = [];
        
        if (!empty($_REQUEST["permissions"])) {
            $permissions = array_map('intval', explode(",", $_REQUEST["permissions"]));
        }
        
        $instance = $this->groupsService->updateUserGroup($int_id, $_REQUEST["group_name"], $_REQUEST["description"], $permissions); // Update existing user_group.
        
        // Output updated user_group data as JSON response.
        header("Content-Type: application/json;charset=UTF-8"); // Set response header content type.
        // echo $instance->toJson(); // Output updated user_group data as JSON.
        echo json_encode($instance->toArray());
    }
    
    /**
     * Handles the DELETE request to delete an existing user_group.
     *
     * @return void
     * @throws RequestException If the request is malformed or missing required parameters.
     * @throws RuntimeException If a database connection error occurs.
     */
    public function delete() : void {
        /*
         * DELETE request handler is designed to delete a user_group entity record in the database.
         * NOTE: PHP does not always parse PUT and DELETE requests. It must be done manually by reading
         * the PHP://input data stream.
         *
         * It expects the ID of a user_group entity as urlencoded request data and returns nothing in the response.
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
        
        // Delete user_group from database.
        $int_id = (int) $_REQUEST["id"]; // Convert the "id" parameter to an integer.
        $this->groupsService->deleteUserGroup($int_id); // Delete user_group from the database.
        
        
        // Respond with HTTP 204 No Content status code.
        header("Content-Type: application/json;charset=UTF-8"); // Set response header content type.
        // The HTTP 204 No Content success status response code indicates that a request has succeeded, but that the client doesn't need to navigate away from its current page.
        http_response_code(204); // Set HTTP response code to 204 No Content.
    }
}