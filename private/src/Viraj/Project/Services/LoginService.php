<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project LoginService.php
 *
 * @author Viraj Patel
 * @since 2024-04-011
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
    
    private UsersService $usersService;
    
    public function __construct() {
        $this->usersService = new UsersService();
    }
    
    /**
     * @return bool
     */
    public static function isUserLoggedIn() : bool {
        $return_val = false;
        if (!empty($_SESSION["LOGGED_IN_USER"]) && ($_SESSION["LOGGED_IN_USER"] instanceof UserDTO)) {
            $return_val = true;
        }
        Debug::log(("Is logged in author check result: [" . $return_val)
                       ? "true"
                       : ("false" . "]" .
                ($return_val ? (" id# [" . $_SESSION["LOGGED_IN_AUTHOR"]->getId() . "].") : ".")));
        return $return_val;
    }
    
    /**
     * @return void
     */
    public static function redirectToLogin() : void {
        header("Location: " . WEB_ROOT_DIR . "pages/login?from=" . $_SERVER["REQUEST_URI"]);
        http_response_code(303);
        exit();
    }
    
    /**
     * @return void
     */
    public function doLogout() : void {
        $_SESSION["LOGGED_IN_USER"] = null;
        Debug::debugToHtmlTable($_SESSION);
    }
    
    /**
     * @param string $username
     * @param string $password
     * @return void
     * @throws RuntimeException
     * @throws ValidationException
     * @throws Exception
     */
    public function doLogin(string $username, string $password) : void {
        
        $user = $this->usersService->validateUser($username, $password);
        
        if ($user === false) {
            throw new Exception("Invalid password");
        }
        
        if (is_null($user)) {
            throw new Exception("Username is [$username] not found.", 404);
        }
        
        $_SESSION["LOGGED_IN_USER"] = $user;
    }
}