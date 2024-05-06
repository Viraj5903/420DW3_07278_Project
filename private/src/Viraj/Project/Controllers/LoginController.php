<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project LoginController.php
 *
 * @author Viraj Patel
 * @since 2024-04-11
 */

namespace Viraj\Project\Controllers;

use Exception;
use Teacher\GivenCode\Abstracts\AbstractController;
use Teacher\GivenCode\Exceptions\RequestException;
use Viraj\Project\Services\LoginService;

/**
 * Controller class responsible for handling login and logout operations.
 */
class LoginController extends AbstractController {
    
    // Class properties.
    private LoginService $loginService;
    
    /**
     * Constructs a new LoginController object.
     */
    public function __construct() {
        parent::__construct();
        $this->loginService = new LoginService();
    }
    
    /**
     * Handles the GET request method.
     *
     * @return void
     * @throws RequestException Always throws a RequestException since no GET operation is supported for the login system.
     */
    public function get() : void {
        // Voluntary exception throw: no GET operation supported for login system.
        throw new RequestException("NOT IMPLEMENTED.", 501);
    }
    
    /**
     * Handles the POST request method for logging in.
     *
     * @return void
     * @throws Exception If there is an error during the login process.
     */
    public function post() : void {
        
        try {
            // Check if username parameter is provided in the request.
            if (empty($_REQUEST["username"])) {
                throw new RequestException("Missing required parameter [username] in request.", 400, [], 400);
            }
            
            // Check if password parameter is provided in the request.
            if (empty($_REQUEST["password"])) {
                throw new RequestException("Missing required parameter [password] in request.", 400, [], 400);
            }
            
            // Attempt to log in the user using provided username and password.
            $this->loginService->doLogin($_REQUEST["username"], $_REQUEST["password"]);
            
            // if the user came to the login page by being redirected from another page that required to be logged in
            // redirect to that originally requested page after login.
            // If the user was redirected to the login page from another page, redirect them back after successful login.
            $response = [
                "navigateTo" => WEB_ROOT_DIR
            ];
            if (!empty($_REQUEST["from"])) {
                $response["navigateTo"] = $_REQUEST["from"];
            }
            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode($response);
            exit();
            
        } catch (Exception $excep) {
            throw new Exception("Failure to log user in.", $excep->getCode(), $excep);
        }
    }
    
    /**
     * Handles the PUT request method.
     *
     * @return void
     * @throws RequestException Always throws a RequestException since no PUT operation is supported for the login system.
     */
    public function put() : void {
        // Voluntary exception throw: no PUT operation supported for login system
        throw new RequestException("NOT IMPLEMENTED.", 501);
    }
    
    /**
     * Handles the DELETE request method for logging out.
     *
     * @return void
     */
    public function delete() : void {
        // Log out the user
        $this->loginService->doLogout();
        
        // Prepare response to redirect user to the login page after logout
        $response = [
            "navigateTo" => WEB_ROOT_DIR . "pages/login"
        ];
        
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($response);
        exit();
    }
}