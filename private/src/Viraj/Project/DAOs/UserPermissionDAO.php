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
     * Database table name for this DAO.
     * @const
     */
    public const TABLE_NAME = "user_permissions";
    
    private const CREATE_QUERY = "INSERT INTO " . self::TABLE_NAME .
    "(`user_id`, `permission_id`) VALUES(:userId, :permissionId) ;";
    
    /**
     * Constructor
     */
    public function __construct() {}
    
    /**
     *
     * @param int $userId
     * @param int $permissionId
     * @return void
     * @throws RuntimeException
     */
    public function createForUserAndPermission(int $userId, int $permissionId) : void {
        
        $connection = DBConnectionService::getConnection();
        
        $statement = $connection->prepare(self::CREATE_QUERY);
        
        $statement->bindValue(":userId", $userId, PDO::PARAM_INT);
        $statement->bindValue(":permissionId", $permissionId, PDO::PARAM_INT);
        
        $statement->execute();
    }
    
    /**
     * @param int   $userId
     * @param array $permissionIds
     * @return void
     * @throws RuntimeException
     */
    public function createManyForUser(int $userId, array $permissionIds) : void {
        
        $connection = DBConnectionService::getConnection();
        
        $statement = $connection->prepare(self::CREATE_QUERY);
        
        $statement->bindValue(":userId", $userId, PDO::PARAM_INT);
        
        foreach ($permissionIds as $permission_id) {
            $statement->bindValue(":permissionId", $permission_id, PDO::PARAM_INT);
            $statement->execute();
        }
    }
    
    /**
     * @param int   $permissionId
     * @param array $userIds
     * @return void
     * @throws RuntimeException
     */
    public function createManyForPermission(int $permissionId, array $userIds) : void {
        
        $connection = DBConnectionService::getConnection();
        
        $statement = $connection->prepare(self::CREATE_QUERY);
        
        $statement->bindValue(":permissionId", $permissionId, PDO::PARAM_INT);
        
        foreach ($userIds as $user_id) {
            $statement->bindValue(":userId", $user_id, PDO::PARAM_INT);
            $statement->execute();
        }
    }
    
    /**
     *
     * @param int $userId
     * @return void
     * @throws RuntimeException
     */
    public function deleteAllByUserId(int $userId) : void {
        
        $query = "DELETE FROM " . self::TABLE_NAME . " WHERE `user_id` = :userId ;";
        
        $connection = DBConnectionService::getConnection();
        
        $statement = $connection->prepare($query);
        
        $statement->bindValue(":userId", $userId, PDO::PARAM_INT);
        
        $statement->execute();
    }
    
    /**
     * @param int $permissionId
     * @return void
     * @throws RuntimeException
     */
    public function deleteAllByPermissionId(int $permissionId) : void {
        
        $query = "DELETE FROM " . self::TABLE_NAME . " WHERE `permission_id` = :permissionId ;";
        
        $connection = DBConnectionService::getConnection();
        
        $statement = $connection->prepare($query);
        
        $statement->bindValue(":permissionId", $permissionId, PDO::PARAM_INT);
        
        $statement->execute();
        
    }
}