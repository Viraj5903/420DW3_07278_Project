<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project LoginService.php
 *
 * @author Viraj Patel
 * @since 2024-04-11
 */

namespace Viraj\Project\Services;

use Debug;
use Exception;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Viraj\Project\DTOs\UserDTO;

/**
 * Service class for login operation.
 */
class LoginService {
    
    // Class properties
    private UsersService $usersService;
    
    /**
     * Constructs a new LoginService object.
     */
    public function __construct() {
        $this->usersService = new UsersService();
    }
    
    /**
     * Checks if a user is logged in.
     *
     * @return bool True if a user is logged in, false otherwise.
     */
    public static function isUserLoggedIn() : bool {
        $return_val = false;
        // Check if the logged in user session variable is not empty and is an instance of UserDTO.
        if (!empty($_SESSION["LOGGED_IN_USER"]) && ($_SESSION["LOGGED_IN_USER"] instanceof UserDTO)) {
            $return_val = true;
        }
        // Log the result of the check.
        Debug::log(("Is logged in author check result: [" . $return_val)
                       ? "true"
                       : ("false" . "]" .
                ($return_val ? (" id# [" . $_SESSION["LOGGED_IN_AUTHOR"]->getId() . "].") : ".")));
        return $return_val;
    }
    
    /**
     * Redirects the user to the login page.
     *
     * @return void
     */
    public static function redirectToLogin() : void {
        // Redirect the user to the login page with a redirection HTTP status code.
        header("Location: " . WEB_ROOT_DIR . "pages/login?from=" . $_SERVER["REQUEST_URI"]);
        http_response_code(303);
        exit();
    }
    
    /**
     * Logs the user out.
     *
     * @return void
     */
    public function doLogout() : void {
        // Set the logged in user session variable to null.
        $_SESSION["LOGGED_IN_USER"] = null;
        // Log the current session.
        Debug::debugToHtmlTable($_SESSION);
    }
    
    /**
     * Logs in a user with the given username and password.
     *
     * @param string $username The username of the user.
     * @param string $password The password of the user.
     * @return void
     * @throws RuntimeException If there is an error during login process.
     * @throws ValidationException If there is an issue with the validation of the user.
     * @throws Exception If the username is not found or the password is invalid.
     */
    public function doLogin(string $username, string $password) : void {
        
        // Validate the user credentials.
        $user = $this->usersService->validateUser($username, $password);
        
        // If the user validation fails due to an invalid password.
        if ($user === false) {
            throw new Exception("Invalid password");
        }
        
        // If the user validation fails due to the username not being found.
        if (is_null($user)) {
            throw new Exception("Username is [$username] not found.", 404);
        }
        
        // Set the logged in user session variable.
        $_SESSION["LOGGED_IN_USER"] = $user;
    }
}