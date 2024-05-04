<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project UserGroupsService.php
 *
 * This file defines the UserGroupsService class, responsible for managing user_group-related operations.
 * It interacts with the UserGroupsDAO class to perform CRUD operations on user group data.
 *
 * This file contains the UserGroupsService class, which provides methods for interacting with user groups.
 * It handles CRUD operations for user groups, utilizing the UserGroupsDAO class for database interaction.
 *
 * @author Viraj Patel
 * @since 2024-04-02
 */

namespace Viraj\Project\Services;

use Exception;
use Viraj\Project\DTOs\UserGroupDTO;
use Viraj\Project\DAOs\UserGroupsDAO;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 * Service class for user_groups operation.
 */
class UserGroupsService {
    // Class properties
    private UserGroupsDAO $userGroupsDAO; // UserGroupsDAO object for interacting with the permissions table of the database.
    
    /**
     * Constructor for UserGroupsService class.
     * Initializes UserGroupsDAO object for database interaction.
     */
    public function __construct() {
        $this->userGroupsDAO = new UserGroupsDAO(); // Initialize UserGroupsDAO object.
    }
    
    /**
     * Retrieves all user groups from the database.
     *
     * @return UserGroupDTO[] An array of UserGroupDTO objects representing user groups.
     * @throws ValidationException If validation of retrieved data fails.
     * @throws RuntimeException If a database connection error occurs.
     */
    public function getAllUserGroups() : array {
        return $this->userGroupsDAO->getAll();
    }
    
    /**
     * Retrieves a user group with associated permissions by its ID from the database.
     *
     * @param int $id The ID of the user group to retrieve.
     * @return UserGroupDTO|null The UserGroupDTO object representing the user group, or null if not found.
     * @throws RuntimeException If a database connection error occurs or if no record is found for the given ID.
     * @throws ValidationException If validation of retrieved data fails.
     */
    public function getUserGroupById(int $id) : ?UserGroupDTO {
        // return $this->userGroupsDAO->getById($id);
        $user_group = $this->userGroupsDAO->getById();
        $user_group?->loadPermissions();
        return $user_group;
    }
    
    
    /**
     * Creates a new user group in the database.
     *
     * @param string $groupName   The name of the user group.
     * @param string $description The description of the user group.
     * @return UserGroupDTO The UserGroupDTO object representing the newly created user group.
     * @throws RuntimeException If failure to create and insert new user group into the database.
     */
    public function createUserGroup(string $groupName, string $description) : UserGroupDTO {
        
        try {
            $user_group = UserGroupDTO::fromValues($groupName, $description);
            return $this->userGroupsDAO->create($user_group);
        } catch (Exception $exception) {
            throw new RuntimeException("Failure to create user group [$groupName, $description].", (int) $exception->getCode(), $exception);
        }
    }
    
    /**
     * Updates an existing user group in the database.
     *
     * @param int    $id          The ID of the user group to update.
     * @param string $groupName   The name of the user group.
     * @param string $description The description of the user group.
     * @return UserGroupDTO The UserGroupDTO object representing the updated user group.
     * @throws RuntimeException If failure to update user_group into the database.
     */
    public function updateUserGroup(int $id, string $groupName, string $description) : UserGroupDTO {
        
        try {
            
            $connection = DBConnectionService::getConnection(); // Get database connection.
            $connection->beginTransaction(); // Begin database transaction.
            
            try {
                $user_group = $this->userGroupsDAO->getById($id); // Retrieve the user group by ID.
                
                // Checking whether $user_group is null or not.
                if (is_null($user_group)) {
                    throw new Exception("User group id# [$id] not found in the database.");
                }
                
                $user_group->setGroupName($groupName); // Set the new group name.
                
                $user_group->setDescription($description); // Set the new description.
                
                $result = $this->userGroupsDAO->update($user_group); // Update the  user_group in the database.
                
                $connection->commit(); // Commit the transaction to save changes.
                
                return $result; // Return the updated user_group.
                
            } catch (Exception $inner_exception) {
                $connection->rollBack();
                throw $inner_exception;
            }
            
        } catch (Exception $exception) {
            throw new RuntimeException("Failure to update user_ group id#[$id].", (int) $exception->getCode(), $exception);
        }
        
    }
    
    /**
     * Deletes a user group by their ID from the database.
     *
     * @param int $id The ID of the user group to delete.
     * @return void
     * @throws RuntimeException If a database connection error occurs.
     */
    public function deleteUserGroup(int $id) : void {
        
        try {
            
            $connection = DBConnectionService::getConnection(); // Get database connection.
            $connection->beginTransaction(); // Begin database transaction.
            
            try {
                $user_group = $this->userGroupsDAO->getById($id); // Retrieve the user_group by ID.
                
                // Checking whether $user_group is null or not.
                if (is_null($user_group)) {
                    throw new Exception("User group id# [$id] not found in the database.");
                }
                $this->userGroupsDAO->delete($user_group); // Delete the $user_group from the database.
                
                $connection->commit(); // Commit the transaction to save changes.
                
            } catch (Exception $inner_exception) {
                $connection->rollBack();
                throw $inner_exception;
            }
            
        } catch (Exception $exception) {
            throw new RuntimeException("Failure to delete user group id# [$id].", (int) $exception->getCode(), $exception);
        }
        
    }
    
    /**
     *
     * @param int $id
     * @return array
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getUserGroupPermissionsByUserGroupId(int $id) : array {
        return $this->userGroupsDAO->getPermissionsByUserGroupId($id);
    }
    
    /**
     *
     * @param UserGroupDTO $userGroup
     * @return array
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getUserGroupPermission(UserGroupDTO $userGroup) : array {
        return $this->getUserGroupPermissionsByUserGroupId($userGroup->getId());
    }
}