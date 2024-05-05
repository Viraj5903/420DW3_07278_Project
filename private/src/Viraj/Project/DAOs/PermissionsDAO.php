<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project PermissionsDAO.php
 *
 * @author Viraj Patel
 * @since 2024-03-31
 */

namespace Viraj\Project\DAOs;

use PDO;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Viraj\Project\DTOs\PermissionDTO;
use Viraj\Project\Services\DBConnectionService;

/**
 * Data Access Object (DAO) for interacting with the 'permissions' table in the database.
 */
class PermissionsDAO {
    
    // Class constants.
    
    // SQL queries for CRUD operations on the 'permissions' table.
    
    /**
     * SQL query to retrieve a permission record by ID.
     */
    private const GET_QUERY = "SELECT * FROM `" . PermissionDTO::TABLE_NAME . "` WHERE `id` = :id ;";
    
    /**
     * SQL query to create a new permission record.
     */
    private const CREATE_QUERY = "INSERT INTO `" . PermissionDTO::TABLE_NAME .
    "` (`unique_permission`, `permission_name`, `description`) VALUES(:unique_permission, :permission_name, :description) ;";
    
    /**
     * SQL query to update an existing permission record.
     */
    private const UPDATE_QUERY = "UPDATE `" . PermissionDTO::TABLE_NAME .
    "` SET `unique_permission` = :unique_permission, `permission_name` = :permission_name, `description` = :description WHERE `id` = :id ;";
    
    /**
     * SQL query to perform a deletion on a permission record.
     */
    private const DELETE_QUERY = "DELETE FROM `" . PermissionDTO::TABLE_NAME . "` WHERE `id` = :id ;";
    
    /**
     * Constructor
     */
    public function __construct() {}
    
    /**
     * Retrieve all permission records from the 'permissions' table of the database.
     *
     * @return array An array of PermissionDTO objects representing the retrieved permission records.
     * @throws RuntimeException If a database connection error occurs.
     * @throws ValidationException If validation of retrieved data fails.
     */
    public function getAll() : array {
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement.
        $statement = $connection->prepare("SELECT * FROM " . PermissionDTO::TABLE_NAME . " ;");
        
        // Execute the SQL statement.
        $statement->execute();
        
        // Fetch the results as an associative array.
        $result_set = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        // Initialize an empty array to store PermissionDTO objects.
        $permissions = [];
        
        // Iterate over the result array and convert each row to a PermissionDTO object.
        foreach ($result_set as $result) {
            $permissions[] = PermissionDTO::fromDbArray($result);
        }
        
        // Return the array of PermissionDTO objects.
        return $permissions;
    }
    
    /**
     * Retrieve a permission record from the 'permissions' table of the database by its ID.
     *
     * @param int $id The ID of the permission record to retrieve.
     * @return PermissionDTO|null A PermissionDTO object representing the retrieved permission record, or null if not found.
     * @throws RuntimeException If a database connection error occurs or if no record is found for the given ID.
     * @throws ValidationException If validation of retrieved data fails.
     */
    public function getById(int $id) : ?PermissionDTO {
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement.
        $statement = $connection->prepare(self::GET_QUERY);
        
        // Bind the ID parameter to the statement.
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        
        // Execute the SQL statement.
        $statement->execute();
        
        // Fetch the result as an associative array.
        $permission_array = $statement->fetch(PDO::FETCH_ASSOC);
        
        // If the $permission_array is bool and false.
        if (is_bool($permission_array) && !$permission_array) {
            // No record found for the given ID.
            return null;
        }
        
        // Convert the $permission_array to a PermissionDTO object and return it.
        return PermissionDTO::fromDbArray($permission_array);
    }
    
    /**
     * Create a new permission record in the 'permissions' table of the database.
     *
     * @param PermissionDTO $permission The PermissionDTO object representing the permission to insert.
     * @return PermissionDTO The newly created PermissionDTO object.
     * @throws ValidationException If the <code>$permission</code> object properties is not valid for insert.
     * @throws RuntimeException If a database connection error occurs or If the newly created permission record cannot be retrieved from the database after insertion.
     */
    public function create(PermissionDTO $permission) : PermissionDTO {
        
        // Validate the DTO object for database creation.
        $permission->validateForDbCreation();
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement for permission creation.
        $statement = $connection->prepare(self::CREATE_QUERY);
        
        // Bind the DTO properties to the statement parameters.
        $statement->bindValue(":unique_permission", $permission->getUniquePermission(), PDO::PARAM_STR);
        $statement->bindValue(":permission_name", $permission->getPermissionName(), PDO::PARAM_STR);
        if (is_null($permission->getDescription())) {
            $statement->bindValue(":description", $permission->getDescription(), PDO::PARAM_NULL);
        } else {
            $statement->bindValue(":description", $permission->getDescription(), PDO::PARAM_STR);
        }
        // Execute the SQL statement.
        $result = $statement->execute();
        if ($result === false) {
            throw new RuntimeException("Failed to create new permission.");
        }
        
        // Get the ID of the newly inserted permission record.
        $new_id = (int) $connection->lastInsertId();
        
        // Retrieve the newly created permission record from the database by its ID.
        $created_permission = $this->getById($new_id);
        
        // Handle the case where getById returns null (shouldn't happen).
        if ($created_permission === null) {
            throw new RuntimeException("Failed to retrieve the newly created permission record.");
        }
        
        // Return the newly created pemission as PermissionDTO object.
        return $created_permission;
    }
    
    /**
     * Update an existing permission record in the 'permissions' table of the database.
     *
     * @param PermissionDTO $permission The PermissionDTO object representing the permission to update.
     * @return PermissionDTO The updated PermissionDTO object.
     * @throws ValidationException If the <code>$permission</code> object properties is not valid for update.
     * @throws RuntimeException If a database connection error occurs or if the updated permission record cannot be retrieved from the database after update.
     */
    public function update(PermissionDTO $permission) : PermissionDTO {
        
        // Validate the DTO object for database update.
        $permission->validateForDbUpdate();
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement for permission update.
        $statement = $connection->prepare(self::UPDATE_QUERY);
        
        // Bind the DTO properties to the statement parameters.
        $statement->bindValue(":id", $permission->getId(), PDO::PARAM_INT);
        $statement->bindValue(":unique_permission", $permission->getUniquePermission(), PDO::PARAM_STR);
        $statement->bindValue(":permission_name", $permission->getPermissionName(), PDO::PARAM_STR);
        
        if (is_null($permission->getDescription())) {
            $statement->bindValue(":description", $permission->getDescription(), PDO::PARAM_NULL);
        } else {
            $statement->bindValue(":description", $permission->getDescription(), PDO::PARAM_STR);
        }
        
        // Execute the SQL statement.
        $statement->execute();
        
        // Retrieve the updated permission record from the database.
        $updated_permission = $this->getById($permission->getId());
        
        // Handle the case where getById returns null (shouldn't happen).
        if ($updated_permission === null) {
            throw new RuntimeException("Failed to retrieve the updated permission record.");
        }
        
        // Return the updated permission as PermissionDTO object.
        return $updated_permission;
    }
    
    /**
     * Delete a permission record from the 'permissions' table of the database by ID.
     *
     * @param int $id The ID of the permission to delete.
     * @return void
     * @throws RuntimeException If a database connection error occurs.
     */
    public function deleteById(int $id) : void {
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement.
        $statement = $connection->prepare(self::DELETE_QUERY);
        
        // Bind the permission ID parameter to the statement.
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        
        // Execute the SQL statement.
        $statement->execute();
    }
    
    /**
     * Delete a permission record from the 'permissions' table of the database.
     *
     * @param PermissionDTO $permission The PermissionDTO object representing the permission to delete.
     * @return void
     * @throws ValidationException     If the <code>$permission</code> object properties is not valid for delete.
     * @throws RuntimeException        If a database connection error occurs.
     */
    public function delete(PermissionDTO $permission) : void {
        
        // Validate the DTO object for database deletion.
        $permission->validateForDbDelete();
        
        // Call the deleteById method to perform the deletion.
        $this->deleteById($permission->getId());
    }
}
