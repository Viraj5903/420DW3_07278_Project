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
     * Database table name for this DAO.
     * @const
     */
    public const TABLE_NAME = "user_group_permissions";
    
    private const CREATE_QUERY = "INSERT INTO ". self::TABLE_NAME . "(`user_group_id`, `permission_id`) VALUES(:userGroupId, :permissionId) ;";
    
    /**
     * Constructor
     */
    public function __construct() {}
    
    /**
     *
     * @param int $userGroupId
     * @param int $permissionId
     * @return void
     * @throws RuntimeException
     */
    public function createForUserGroupAndPermission(int $userGroupId, int $permissionId) : void {
        
        $connection = DBConnectionService::getConnection();
        
        $statement = $connection->prepare(self::CREATE_QUERY);
        
        $statement->bindValue(":userGroupId", $userGroupId, PDO::PARAM_INT);
        $statement->bindValue(":permissionId", $permissionId, PDO::PARAM_INT);
        
        $statement->execute();
    }
    
    /**
     *
     * @param int   $userGroupId
     * @param array $permissionIds
     * @return void
     * @throws RuntimeException
     */
    public function createManyForUserGroup(int $userGroupId, array $permissionIds) : void {
        
        $connection = DBConnectionService::getConnection();
        
        $statement = $connection->prepare(self::CREATE_QUERY);
        
        $statement->bindValue(":userGroupId", $userGroupId, PDO::PARAM_INT);
        
        foreach ($permissionIds as $permission_id) {
            $statement->bindValue(":permissionId", $permission_id, PDO::PARAM_INT);
            $statement->execute();
        }
    }
    
    
    /**
     *
     * @param int   $permissionId
     * @param array $userGroupIds
     * @return void
     * @throws RuntimeException
     */
    public function createManyForPermission(int $permissionId, array $userGroupIds) : void {
        
        $connection = DBConnectionService::getConnection();
        
        $statement = $connection->prepare(self::CREATE_QUERY);
        
        $statement->bindValue(":permissionId", $permissionId, PDO::PARAM_INT);
        
        foreach ($userGroupIds as $user_group_id) {
            $statement->bindValue(":userGroupId", $user_group_id, PDO::PARAM_INT);
            $statement->execute();
        }
    }
    
    /**
     * @param int $userGroupId
     * @return void
     * @throws RuntimeException
     */
    public function deleteAllByUserGroupId(int $userGroupId) : void {
        
        $query = "DELETE FROM " . self::TABLE_NAME . " WHERE `user_group_id` = :userGroupId ;";
        
        $connection = DBConnectionService::getConnection();
        
        $statement = $connection->prepare($query);
        
        $statement->bindValue(":userGroupId", $userGroupId, PDO::PARAM_INT);
        
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