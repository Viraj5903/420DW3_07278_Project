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
 *
 */
class PermissionCheckService {
    
    public function __construct() {}
    
    /**
     * @param string $permissionUniqueName
     * @return bool
     * @throws RuntimeException If there is an issue with loading the permission records.
     */
    public static function checkPermission(string $permissionUniqueName) : bool {
        
        $user = $_SESSION["LOGGED_IN_USER"];
        
        if (!($user instanceof UserDTO) && !is_null($user)) {
            // http_response_code(401);
            throw new RuntimeException('user is not instance of UserDTO');
        }
        
        foreach ($user->getPermissions() as $permission) {
            if (($permission->toArray())["uniquePermission"] === $permissionUniqueName) {
                return true;
            }
        }
        
        return false;
    }
}