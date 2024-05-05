<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project UserPermissionDAO.php
 *
 * @author Viraj Patel
 * @since 2024-03-31
 */

namespace Viraj\Project\DAOs;

use PDO;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Viraj\Project\Services\DBConnectionService;

/**
 * Data Access Object (DAO) for interacting with the 'user_group_permissions' table in the database.
 */
class UserGroupPermissionDAO {
    
    // Class constants.
    
    /**
     * Database table name for UserGroupPermission DAO.
     * @const
     */
    public const TABLE_NAME = "user_group_permissions";
    
    /**
     * SQL query to create a user group and permission association record in the 'user_group_permissions' table of the database.
     */
    private const CREATE_QUERY = "INSERT INTO " . self::TABLE_NAME .
    "(`user_group_id`, `permission_id`) VALUES(:userGroupId, :permissionId) ;";
    
    /**
     * Constructor
     */
    public function __construct() {}
    
    /**
     * Create a new association between a user group and permission in the 'user_group_permissions' table of the database.
     *
     * @param int $userGroupId  The ID of the user group to associate with the permission.
     * @param int $permissionId The ID of the permission to associate with the user group.
     * @return void
     * @throws RuntimeException If an error occurs while preparing or executing the database query.
     */
    public function createForUserGroupAndPermission(int $userGroupId, int $permissionId) : void {
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement.
        $statement = $connection->prepare(self::CREATE_QUERY);
        
        // Bind the value to the parameterized statement.
        $statement->bindValue(":userGroupId", $userGroupId, PDO::PARAM_INT);
        $statement->bindValue(":permissionId", $permissionId, PDO::PARAM_INT);
        
        // Execute the SQL statement.
        $statement->execute();
    }
    
    /**
     * Create associations between a user group and multiple permissions in the 'user_group_permissions' table of the database.
     *
     * @param int   $userGroupId   The ID of the user group to associate with the permissions.
     * @param array $permissionIds An array containing the IDs of the permissions to associate with the user group.
     * @return void
     * @throws RuntimeException If an error occurs while preparing or executing the database query.
     */
    public function createManyForUserGroup(int $userGroupId, array $permissionIds) : void {
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement.
        $statement = $connection->prepare(self::CREATE_QUERY);
        
        // Bind the value to the parameterized statement.
        $statement->bindValue(":userGroupId", $userGroupId, PDO::PARAM_INT);
        
        foreach ($permissionIds as $permission_id) {
            $statement->bindValue(":permissionId", $permission_id, PDO::PARAM_INT);
            
            // Execute the SQL statement.
            $statement->execute();
        }
    }
    
    /**
     * Delete all associations for a given user group ID from the 'user_group_permissions' table of the database.
     *
     * @param int $userGroupId The ID of the user group whose associations should be deleted.
     * @return void
     * @throws RuntimeException If an error occurs while preparing or executing the database query.
     */
    public function deleteAllByUserGroupId(int $userGroupId) : void {
        
        // Define the SQL query to delete associations for the given user group ID.
        $query = "DELETE FROM " . self::TABLE_NAME . " WHERE `user_group_id` = :userGroupId ;";
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement.
        $statement = $connection->prepare($query);
        
        // Bind the value to the parameterized statement.
        $statement->bindValue(":userGroupId", $userGroupId, PDO::PARAM_INT);
        
        // Execute the SQL statement.
        $statement->execute();
    }
    
    /**
     * Delete all associations for a given permission ID from the 'user_group_permissions' table of the database.
     *
     * @param int $permissionId The ID of the permission whose associations should be deleted.
     * @return void
     * @throws RuntimeException If an error occurs while preparing or executing the database query.
     */
    public function deleteAllByPermissionId(int $permissionId) : void {
        
        // Define the SQL query to delete associations for the given permission ID.
        $query = "DELETE FROM " . self::TABLE_NAME . " WHERE `permission_id` = :permissionId ;";
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement.
        $statement = $connection->prepare($query);
        
        // Bind the value to the parameterized statement.
        $statement->bindValue(":permissionId", $permissionId, PDO::PARAM_INT);
        
        // Execute the SQL statement.
        $statement->execute();
    }
}