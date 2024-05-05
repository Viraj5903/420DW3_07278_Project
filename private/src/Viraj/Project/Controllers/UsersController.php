<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project UsersController.php
 *
 * @author Viraj Patel
 * @since 2024-04-04
 */

namespace Viraj\Project\Controllers;

use Teacher\GivenCode\Abstracts\AbstractController;
use Teacher\GivenCode\Exceptions\RequestException;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Viraj\Project\Services\UsersService;

/**
 * Controller class for handling user-related HTTP requests.
 */
class UsersController extends AbstractController {
    
    // Class properties.
    private UsersService $usersService; // UsersService object for handling user-related operations.
    
    /**
     * Constructor for UsersController class.
     * Initializes UsersService object for handling user-related operations.
     */
    public function __construct() {
        parent::__construct(); // Call the constructor of the parent class AbstractController.
        $this->usersService = new UsersService(); // Initialize UsersService object.
    }
    
    /**
     * Handles the GET request to retrieve user data by ID.
     *
     * @return void
     * @throws RequestException If the request is malformed or missing required parameters.
     * @throws RuntimeException If a database connection error occurs.
     * @throws ValidationException If validation of retrieved data fails.
     */
    public function get() : void {
        
        // Retrieve user ID from request parameters.
        if (empty($_REQUEST["id"])) { // Check if the "id" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [id] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["id"])) { // Check if the "id" parameter is not numeric.
            throw new RequestException("Bad request: parameter [id] value [" . $_REQUEST["id"] . "] is not numeric.", 400);
        }
        $int_id = (int) $_REQUEST["id"]; // Convert the "id" parameter to an integer.
        
        // Get user data by ID from the service layer.
        $instance = $this->usersService->getUserById($int_id); // Retrieve user data by ID.
        
        // Output user data as JSON response.
        header("Content-Type: application/json;charset=UTF-8"); // Set response header content type.
        // echo $instance->toJson(); // Output user data as JSON.
        echo json_encode($instance->toArray());
    }
    
    /**
     * Handles the POST request to create a new user.
     *
     * @return void
     * @throws RequestException If the request is malformed or missing required parameters.
     * @throws RuntimeException If a database connection error occurs.
     * @throws ValidationException If validation of user data fails.
     */
    public function post() : void {
        
        // Validate request parameters.
        if (empty($_REQUEST["username"])) { // Check if the "username" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [username] not found in the request.", 400);
        }
        if (empty($_REQUEST["password"])) { // Check if the "password" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [password] not found in the request.", 400);
        }
        if (empty($_REQUEST["email"])) { // Check if the "email" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [email] not found in the request.", 400);
        }
        
        $permissions = [];
        
        if (!empty($_REQUEST["permissions"])) {
            $permissions = array_map('intval', explode(",", $_REQUEST["permissions"]));
        }
        
        // Create new user using provided data.
        $instance = $this->usersService->createUser($_REQUEST["username"], $_REQUEST["password"], $_REQUEST["email"], $permissions); // Create new user.
        
        // Output newly created user data as JSON response.
        header("Content-Type: application/json;charset=UTF-8"); // Set response header content type.
        echo json_encode($instance->toArray());
        // echo $instance->toJson(); // Output newly created user data as JSON.
    }
    
    /**
     * Handles the PUT request to update an existing user.
     *
     * @return void
     * @throws RequestException If the request is malformed or missing required parameters.
     * @throws RuntimeException If a database connection error occurs.
     * @throws ValidationException If validation of user data fails.
     */
    public function put() : void {
        
        // Parse request data.
        $request_contents = file_get_contents("php://input");
        parse_str($request_contents, $_REQUEST);
        
        // Validate request parameters.
        if (empty($_REQUEST["id"])) { // Check if the "id" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [id] not found in the request.", 400);
        }
        
        if (!is_numeric($_REQUEST["id"])) { // Check if the "id" parameter is not numeric.
            throw new RequestException("Bad request: parameter [id] value [" . $_REQUEST["id"] . "] is not numeric.", 400);
        }
        
        
        if (empty($_REQUEST["username"])) { // Check if the "username" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [username] not found in the request.", 400);
        }
        
        if (empty($_REQUEST["password"])) { // Check if the "password" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [password] not found in the request.", 400);
        }
        
        if (empty($_REQUEST["email"])) { // Check if the "email" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [email] not found in the request.", 400);
        }
        
        // Update existing user using provided data.
        $int_id = (int) $_REQUEST["id"]; // Convert the "id" parameter to an integer.
        
        $permissions = [];
        
        if (!empty($_REQUEST["permissions"])) {
            $permissions = array_map('intval', explode(",", $_REQUEST["permissions"]));
        }
        
        $instance = $this->usersService->updateUser($int_id, $_REQUEST["username"], $_REQUEST["password"], $_REQUEST["email"], $permissions); // Update existing user.
        
        // Output updated user data as JSON response.
        header("Content-Type: application/json;charset=UTF-8"); // Set response header content type.
        // echo $instance->toJson(); // Output updated user data as JSON.
        echo json_encode($instance->toArray());
    }
    
    /**
     * Handles the DELETE request to delete an existing user.
     *
     * @return void
     * @throws RequestException If the request is malformed or missing required parameters.
     * @throws RuntimeException If a database connection error occurs.
     */
    public function delete() : void {
        /*
         * DELETE request handler is designed to delete a user entity record in the database.
         * NOTE: PHP does not always parse PUT and DELETE requests. It must be done manually by reading
         * the PHP://input data stream.
         *
         * It expects the ID of a user entity as urlencoded request data and returns nothing in the response.
         */
        
        // As stated, we need to manually parse the input content of PUT and DELETE requests.
        // For this DELETE deletion example, that is application/x-www-form-urlencoded content.
        // We need to use parse_str() function to decode urlencoded string data instead of the json_decode() used for JSON data.
        
        // Parse request data.
        $request_contents = file_get_contents('php://input'); // Read raw input data.
        parse_str($request_contents, $_REQUEST); // Parse urlencoded data.
        
        
        // Validate request parameters.
        if (empty($_REQUEST["id"])) { // Check if the "id" parameter is missing in the request.
            throw new RequestException("Bad request: required parameter [id] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["id"])) { // Check if the "id" parameter is not numeric.
            throw new RequestException("Bad request: parameter [id] value [" . $_REQUEST["id"] . "] is not numeric.", 400);
        }
        
        
        // Delete user from database.
        $int_id = (int) $_REQUEST["id"]; // Convert the "id" parameter to an integer.
        $this->usersService->deleteUser($int_id); // Delete user from the database.
        
        // Respond with HTTP 204 No Content status code.
        header("Content-Type: application/json;charset=UTF-8");
        // The HTTP 204 No Content success status response code indicates that a request has succeeded, but that the client doesn't need to navigate away from its current page.
        http_response_code(204); // Set HTTP response code to 204 No Content.
    }
}