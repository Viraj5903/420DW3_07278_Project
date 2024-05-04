<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project PermissionsService.php
 *
 * This file defines the PermissionsService class, responsible for managing permission-related operations.
 * It interacts with the PermissionsDAO class to perform CRUD operations on permission data.
 *
 * This file contains the PermissionsService class, which provides methods for interacting with permissions table.
 * It handles CRUD operations for permissions, utilizing the PermissionsDAO class for database interaction.
 *
 * @author Viraj Patel
 * @since 2024-04-02
 */

namespace Viraj\Project\Services;

use Exception;
use Viraj\Project\DTOs\PermissionDTO;
use Viraj\Project\DAOs\PermissionsDAO;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 * Service class for permissions operation.
 */
class PermissionsService {
    
    // Class properties
    private PermissionsDAO $permissionsDAO; // PermissionsDAO object for interacting with the permissions table of the database.
    
    /**
     * Constructor for PermissionsService class.
     * Initializes PermissionsDAO object for database interaction.
     */
    public function __construct() {
        $this->permissionsDAO = new PermissionsDAO(); // Initialize PermissionsDAO object.
    }
    
    /**
     * Retrieves all permissions from the database.
     *
     * @return PermissionDTO[] An array of PermissionDTO objects representing permissions.
     * @throws ValidationException If validation of retrieved data fails.
     * @throws RuntimeException If a database connection error occurs.
     */
    public function getAllPermissions() : array {
        return $this->permissionsDAO->getAll();
    }
    
    /**
     * Retrieves a permission by its ID from the database.
     *
     * @param int $id The ID of the permission to retrieve.
     * @return PermissionDTO|null The PermissionDTO object representing the permission, or null if not found.
     * @throws RuntimeException If a database connection error occurs or if no record is found for the given ID.
     * @throws ValidationException If validation of retrieved data fails.
     */
    public function getPermissionById(int $id) : ?PermissionDTO {
        return $this->permissionsDAO->getById($id);
    }
    
    /**
     * Creates a new permission in the database.
     *
     * @param string      $uniquePermission The unique identifier for the permission.
     * @param string      $permissionName   The name of the permission.
     * @param string|null $description      The description of the permission.
     * @return PermissionDTO The PermissionDTO object representing the newly created permission.
     * @throws RuntimeException If failure to create and insert new permission into the database.
     */
    public function createPermission(string $uniquePermission, string $permissionName, ?string $description) : PermissionDTO {
        
        try {
            
            $permission = PermissionDTO::fromValues($uniquePermission, $permissionName, $description);
            
            return $this->permissionsDAO->create($permission);
            
        } catch (Exception $exception) {
            throw new RuntimeException("Failure to create user [$uniquePermission, $permissionName, $description]." ,(int) $exception->getCode(), $exception);
        }
        
    }
    
    /**
     * Updates an existing permission in the database.
     *
     * @param int    $id               The ID of the permission to update.
     * @param string $uniquePermission The unique identifier for the permission.
     * @param string $permissionName   The name of the permission.
     * @param string $description      The description of the permission.
     * @return PermissionDTO The PermissionDTO object representing the updated user.
     * @throws RuntimeException If failure to update user into the database.
     */
    public function updatePermission(int $id, string $uniquePermission, string $permissionName, string $description) : PermissionDTO {
        
        try {
            
            $connection = DBConnectionService::getConnection(); // Get database connection.
            $connection->beginTransaction(); // Begin database transaction.
            
            try {
                $permission = $this->permissionsDAO->getById($id); // Retrieve the permission by ID.
                
                // Checking whether $permission is null or not.
                if (is_null($permission)) {
                    throw new Exception("Permission id# [$id] not found in the database.");
                }
                
                $permission->setUniquePermission($uniquePermission); // Set the new unique Permission.
                
                $permission->setPermissionName($permissionName); // Set the new permission name.
                
                $permission->setDescription($description); // Set the new description.
                
                $result = $this->permissionsDAO->update($permission); // Update the permission in the database.
                
                $connection->commit(); // Commit the transaction to save changes.
                
                return $result; // Return the updated permission.
                
            } catch (Exception $inner_exception) {
                $connection->rollBack();
                throw $inner_exception;
            }
            
        } catch (Exception $exception) {
            throw new RuntimeException("Failure to update permission id#[$id].", (int) $exception->getCode(), $exception);
        }
    }
    
    /**
     * Deletes a permission by their ID from the database.
     *
     * @param int $id The ID of the permission to delete.|
     * @return void
     * @throws RuntimeException If failure to delete permission from the database.
     */
    public function deletePermission(int $id) : void {
        
        try {
            
            $connection = DBConnectionService::getConnection(); // Get database connection.
            $connection->beginTransaction(); // Begin database transaction.
            
            try {
                $permission = $this->permissionsDAO->getById($id); // Retrieve the permission by ID.
                
                // Checking whether $permission is null or not.
                if (is_null($permission)) {
                    throw new Exception("User id# [$id] not found in the database.");
                }
                $this->permissionsDAO->delete($permission); // Delete the permission from the database.
                
                $connection->commit(); // Commit the transaction to save changes.
                
            } catch (Exception $inner_exception) {
                $connection->rollBack();
                throw $inner_exception;
            }
            
        } catch (Exception $exception) {
            throw new RuntimeException("Failure to delete permission id# [$id].", (int) $exception->getCode(), $exception);
        }
        
    }
    
    /**
     *
     *
     * @param int $id
     * @return array
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getUserPermissionsByPermissionId(int $id) : array {
        return $this->permissionsDAO->getUsersByPermissionId($id);
    }
    
    /**
     *
     *
     * @throws ValidationException
     * @throws RuntimeException
     */
    public function getUserPermissions(PermissionDTO $permission) : array {
        return $this->getUserPermissionsByPermissionId($permission->getId());
    }
    
    /**
     *
     *
     * @param int $id
     * @return array
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getUserGroupPermissionsByPermissionId(int $id) : array {
        return $this->permissionsDAO->getUserGroupsByPermissionId($id);
    }
    
    /**
     *
     *
     * @param PermissionDTO $permission
     * @return array
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getUserGroupPermissions(PermissionDTO $permission) : array {
        return $this->getUserGroupPermissionsByPermissionId($permission->getId());
    }
}