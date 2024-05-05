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
 * Data Access Object (DAO) for interacting with the 'user_permissions' table in the database.
 */
class UserPermissionDAO {
    
    // Class constants.
    
    /**
     * Database table name for UserPermission DAO.
     * @const
     */
    public const TABLE_NAME = "user_permissions";
    
    /**
     * SQL query to create a user and permission association record in the 'user_permissions' table of the database.
     */
    private const CREATE_QUERY = "INSERT INTO " . self::TABLE_NAME .
    "(`user_id`, `permission_id`) VALUES(:userId, :permissionId) ;";
    
    /**
     * Constructor
     */
    public function __construct() {}
    
    /**
     * Create a new association between a user and permission in the 'user_permissions' table of the database.
     *
     * @param int $userId       The ID of the user to associate with the permission.
     * @param int $permissionId The ID of the permission to associate with the user group.
     * @return void
     * @throws RuntimeException If an error occurs while preparing or executing the database query.
     */
    public function createForUserAndPermission(int $userId, int $permissionId) : void {
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement.
        $statement = $connection->prepare(self::CREATE_QUERY);
        
        // Bind the value to the parameterized statement.
        $statement->bindValue(":userId", $userId, PDO::PARAM_INT);
        $statement->bindValue(":permissionId", $permissionId, PDO::PARAM_INT);
        
        // Execute the SQL statement.
        $statement->execute();
    }
    
    /**
     * Create associations between a user and multiple permissions in the 'user_permissions' table of the database.
     *
     * @param int   $userId        The ID of the user to associate with the permissions.
     * @param array $permissionIds An array containing the IDs of the permissions to associate with the user.
     * @return void
     * @throws RuntimeException If an error occurs while preparing or executing the database query.
     */
    public function createManyForUser(int $userId, array $permissionIds) : void {
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement.
        $statement = $connection->prepare(self::CREATE_QUERY);
        
        // Bind the value to the parameterized statement.
        $statement->bindValue(":userId", $userId, PDO::PARAM_INT);
        
        foreach ($permissionIds as $permission_id) {
            $statement->bindValue(":permissionId", $permission_id, PDO::PARAM_INT);
            
            // Execute the SQL statement.
            $statement->execute();
        }
    }
    
    /**
     * Delete all associations for a given user ID from the 'user_permissions' table of the database.
     *
     * @param int $userId The ID of the user whose associations should be deleted.
     * @return void
     * @throws RuntimeException If an error occurs while preparing or executing the database query.
     */
    public function deleteAllByUserId(int $userId) : void {
        
        // Define the SQL query to delete associations for the given user ID.
        $query = "DELETE FROM " . self::TABLE_NAME . " WHERE `user_id` = :userId ;";
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement.
        $statement = $connection->prepare($query);
        
        // Bind the value to the parameterized statement.
        $statement->bindValue(":userId", $userId, PDO::PARAM_INT);
        
        // Execute the SQL statement.
        $statement->execute();
    }
    
    /**
     * Delete all associations for a given permission ID from the 'user_permissions' table of the database.
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