<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project PermissionCheckService.php
 *
 * @author Viraj Patel
 * @since 2024-05-05
 */

namespace Viraj\Project\Services;

use Teacher\GivenCode\Exceptions\RuntimeException;
use Viraj\Project\DTOs\UserDTO;

/**
 * Service class for checking user permissions.
 */
class PermissionCheckService {
    
    /**
     * Constructor.
     */
    public function __construct() {}
    
    /**
     * Checks if a user has a specific permission.
     *
     * @param string $permissionUniqueName The unique name of the permission to check.
     * @return void
     * @throws RuntimeException If there is an issue with loading the permission records or if the user is not logged in.
     */
    public static function checkPermission(string $permissionUniqueName) : void {
        
        // Get the logged-in user from the session.
        $user = $_SESSION["LOGGED_IN_USER"];
        
        // Check if the user is an instance of UserDTO.
        if (!($user instanceof UserDTO) && !is_null($user)) {
            // If not, throw a runtime exception.
            throw new RuntimeException('User is not an instance of UserDTO');
        }
        
        // Iterate through the user's permissions.
        foreach ($user->getPermissions() as $permission) {
            // Check if the permission unique name matches the provided permission unique name.
            if (($permission->toArray())["uniquePermission"] === $permissionUniqueName) {
                // If yes, return.
                return;
            }
        }
        
        // If the user does not have the required permission, redirect to the access denied page.
        //header("Location: " . WEB_ROOT_DIR . "pages/accessdenied");
        header("Location: accessdenied");
        exit();
    }
}