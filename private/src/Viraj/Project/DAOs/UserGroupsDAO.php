<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project UserGroupsDAO.php
 *
 * @author Viraj Patel
 * @since 2024-03-31
 */

namespace Viraj\Project\DAOs;

use PDO;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Viraj\Project\DTOs\PermissionDTO;
use Viraj\Project\DTOs\UserGroupDTO;
use Viraj\Project\Services\DBConnectionService;

/**
 * Data Access Object (DAO) for interacting with the 'user_groups' table in the database.
 */
class UserGroupsDAO {
    
    // Class constants.
    
    // SQL queries for CRUD operations on the 'user_groups' table.
    
    /**
     * SQL query to retrieve a user_group record by ID.
     */
    private const GET_QUERY = "SELECT * FROM `" . UserGroupDTO::TABLE_NAME .
    "` WHERE `id` = :id ;";
    
    /**
     * SQL query to create a new user_group record.
     */
    private const CREATE_QUERY = "INSERT INTO `" . UserGroupDTO::TABLE_NAME .
    "` (`group_name`, `description`) VALUES(:group_name, :description) ;";
    
    /**
     * SQL query to update an existing user_group record.
     */
    private const UPDATE_QUERY = "UPDATE `" . UserGroupDTO::TABLE_NAME .
    "` SET `group_name` = :group_name, `description` = :description WHERE `id` = :id ;";
    
    /**
     * SQL query to perform a deletion on a user_group record.
     */
    private const DELETE_QUERY = "DELETE FROM `" . UserGroupDTO::TABLE_NAME . "` WHERE `id` = :id ;";
    
    /**
     * Constructor
     */
    public function __construct() {}
    
    /**
     * Retrieve all user_group records from the 'user_groups' table of the database.
     *
     * @return array An array of UserGroupDTO objects representing the retrieved user_group records.
     * @throws RuntimeException If a database connection error occurs.
     * @throws ValidationException If validation of retrieved data fails.
     */
    public function getAll() : array {
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement.
        $statement = $connection->prepare("SELECT * FROM " . UserGroupDTO::TABLE_NAME . " ;");
        
        // Execute the SQL statement.
        $statement->execute();
        
        // Fetch the results as an associative array.
        $result_set = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        // Initialize an empty array to store UserGroupDTO objects.
        $user_groups = [];
        
        // Iterate over the result array and convert each row to a UserGroupDTO object.
        foreach ($result_set as $result) {
            $user_groups[] = UserGroupDTO::fromDbArray($result);
        }
        
        // Return the array of UserGroupDTO objects.
        return $user_groups;
    }
    
    /**
     * Retrieve a user_group record from the 'user_groups' table of the database by its ID.
     *
     * @param int $id            The ID of the user_group record to retrieve.
     * @return UserGroupDTO|null A UserGroupDTO object representing the retrieved user_group record, or null if not
     *                           found.
     * @throws RuntimeException If a database connection error occurs or if no record is found for the given ID.
     * @throws ValidationException If validation of retrieved data fails.
     */
    public function getById(int $id) : ?UserGroupDTO {
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement.
        $statement = $connection->prepare(self::GET_QUERY);
        
        // Bind the ID parameter to the statement.
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        
        // Execute the SQL statement.
        $statement->execute();
        
        // Fetch the result as an associative array.
        $user_group_array = $statement->fetch(PDO::FETCH_ASSOC);
        
        // If the $user_group_array is bool and false.
        if (is_bool($user_group_array) && !$user_group_array) {
            // No record found for the given ID.
            return null;
        }
        
        // Convert the $user_group_array to a UserGroupDTO object and return it.
        return UserGroupDTO::fromDbArray($user_group_array);
    }
    
    /**
     * Create a new user_group record in the 'user_groups' table of the database.
     *
     * @param UserGroupDTO $userGroup The UserGroupDTO object representing the user_group to insert.
     * @return UserGroupDTO The newly created UserGroupDTO object.
     * @throws ValidationException If the <code>$userGroup</code> object properties is not valid for insert.
     * @throws RuntimeException If a database connection error occurs or If the newly created user_group record cannot be retrieved from the database after insertion.
     */
    public function create(UserGroupDTO $userGroup) : UserGroupDTO {
        
        // Validate the DTO object for database creation.
        $userGroup->validateForDbCreation();
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement for user_group creation.
        $statement = $connection->prepare(self::CREATE_QUERY);
        
        // Bind the DTO properties to the statement parameters.
        $statement->bindValue(":group_name", $userGroup->getGroupName(), PDO::PARAM_STR);
        if (is_null($userGroup->getDescription())) {
            $statement->bindValue(":description", $userGroup->getDescription(), PDO::PARAM_NULL);
        } else {
            $statement->bindValue(":description", $userGroup->getDescription(), PDO::PARAM_STR);
        }
        
        // Execute the SQL statement.
        $result = $statement->execute();
        if ($result === false) {
            throw new RuntimeException("Failed to create new user group.");
        }
        
        // Get the ID of the newly inserted user_group record.
        $new_id = (int) $connection->lastInsertId();
        
        // Retrieve the newly created user_group record from the database by its ID.
        $created_user_group = $this->getById($new_id);
        
        // Handle the case where getById returns null (shouldn't happen).
        if ($created_user_group === null) {
            throw new RuntimeException("Failed to retrieve the newly created user_group record.");
        }
        
        // Return the newly created user_group as UserGroupDTO object.
        return $created_user_group;
    }
    
    /**
     * Update an existing user_group record in the 'user_groups' table of the database.
     *
     * @param UserGroupDTO $userGroup The UserGroupDTO object representing the user_group to update.
     * @return UserGroupDTO The updated UserGroupDTO object.
     * @throws ValidationException If the <code>$userGroup</code> object properties is not valid for update.
     * @throws RuntimeException If a database connection error occurs or if the updated user_group record cannot be retrieved from the database after update.
     */
    public function update(UserGroupDTO $userGroup) : UserGroupDTO {
        
        // Validate the DTO object for database update.
        $userGroup->validateForDbUpdate();
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement for user_group update.
        $statement = $connection->prepare(self::UPDATE_QUERY);
        
        // Bind the DTO properties to the statement parameters.
        $statement->bindValue(":id", $userGroup->getId(), PDO::PARAM_INT);
        $statement->bindValue(":group_name", $userGroup->getGroupName(), PDO::PARAM_STR);
        $statement->bindValue(":description", $userGroup->getDescription(), PDO::PARAM_STR);
        
        // Execute the SQL statement.
        $statement->execute();
        
        // Retrieve the updated user_group record from the database.
        $updated_user_group = $this->getById($userGroup->getId());
        
        // Completed TODO: do something in the case that getById returns null. It shouldn't happen, but its a case to handle.
        // Handle the case where getById returns null (shouldn't happen).
        if ($updated_user_group === null) {
            throw new RuntimeException("Failed to retrieve the updated user_group record.");
        }
        
        // Return the updated user_group as UserGroupDTO object.
        return $updated_user_group;
    }
    
    /**
     * Delete a user_group record from the 'user_groups' table of the database by ID.
     *
     * @param int $id The ID of the user_group to delete.
     * @return void
     * @throws RuntimeException If a database connection error occurs.
     */
    public function deleteById(int $id) : void {
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement based on the deletion type.
        $statement = $connection->prepare(self::DELETE_QUERY);
        
        // Bind the user_group ID parameter to the statement.
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        
        // Execute the SQL statement.
        $statement->execute();
    }
    
    /**
     * Delete a user_group record from the 'user_groups' table of the database.
     *
     * @param UserGroupDTO $userGroup The UserGroupDTO object representing the user_group to delete.
     * @return void
     * @throws ValidationException     If the <code>$userGroup</code> object parameter is not an UserGroupDTO instance or if the <code>$userGroup</code> object properties is not valid for delete.
     * @throws RuntimeException        If a database connection error occurs.
     */
    public function delete(UserGroupDTO $userGroup) : void {
        
        // Validate the DTO object for database deletion.
        $userGroup->validateForDbDelete();
        
        // Call the deleteById method to perform the deletion.
        $this->deleteById($userGroup->getId());
    }
    
    /**
     * Retrieves permissions associated with a user group by its ID from the database.
     *
     * @param int $id The ID of the user group.
     * @return array An array of PermissionDTO objects representing the permissions associated with the user group.
     * @throws RuntimeException If a database connection error occurs.
     * @throws ValidationException If there's an issue with the validation of the retrieved data.
     */
    public function getPermissionsByUserGroupId(int $id) : array {
        
        // Join query to retrieve permissions associated with the user group by their ID from the `user_group_permissions` table.
        $query = "SELECT p.* FROM " . UserGroupDTO::TABLE_NAME . " g JOIN " . UserGroupPermissionDAO::TABLE_NAME . " gp ON g.id = gp.user_group_id JOIN " . PermissionDTO::TABLE_NAME . " p ON gp.permission_id = p.id WHERE g.id = :userGroupId";
        
        // Establish a database connection
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement
        $statement = $connection->prepare($query);
        
        // Bind the user group ID parameter to the statement
        $statement->bindValue(":userGroupId", $id, PDO::PARAM_INT);
        
        // Execute the SQL statement
        $statement->execute();
        
        // Fetch the results as an associative array
        $result_set = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        // Initialize an array to store PermissionDTO objects
        $permissions_array = [];
        
        // Iterate through each permission record and create PermissionDTO objects
        foreach ($result_set as $permission_record_array) {
            $permissions_array[] = PermissionDTO::fromDbArray($permission_record_array);
        }
        
        // Return the array of PermissionDTO objects
        return $permissions_array;
    }
    
    
    /**
     * Retrieves permissions associated with a user group from the database.
     *
     * @param UserGroupDTO $userGroup The UserGroupDTO object representing the user group.
     * @return array An array of PermissionDTO objects representing the permissions associated with the user group.
     * @throws ValidationException If the user group object does not have an ID set.
     * @throws RuntimeException If a database connection error occurs.
     */
    public function getPermissionsByUserGroup(UserGroupDTO $userGroup) : array {
        // Check if the user group object has an ID set
        if (empty($userGroup->getId())) {
            throw new ValidationException("Cannot get the permission records for a user group with no set [id] property value.");
        }
        
        // Call the getPermissionsByUserGroupId method with the user group's ID
        return $this->getPermissionsByUserGroupId($userGroup->getId());
    }
    
}
