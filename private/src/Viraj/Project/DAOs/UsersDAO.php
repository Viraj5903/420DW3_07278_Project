<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project UsersDAO.php
 *
 * @author Viraj Patel
 * @since 2024-03-31
 */

namespace Viraj\Project\DAOs;

use PDO;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Viraj\Project\DTOs\PermissionDTO;
use Viraj\Project\DTOs\UserDTO;
use Viraj\Project\Services\DBConnectionService;

/**
 * Data Access Object (DAO) for interacting with the 'users' table in the database.
 */
class UsersDAO {
    
    // Class constants
    
    // SQL queries for CRUD operations on the 'users' table.
    
    /**
     * SQL query to retrieve a user record by ID.
     */
    private const GET_QUERY = "SELECT * FROM `" . UserDTO::TABLE_NAME . "` WHERE `id` = :id ;";
    
    /**
     * SQL query to create a new user record.
     */
    private const CREATE_QUERY = "INSERT INTO `" . UserDTO::TABLE_NAME .
    "` (`username`, `password_hash`, `email`) VALUES (:username, :password_hash, :email) ;";
    
    /**
     * SQL query to update an existing user record.
     */
    private const UPDATE_QUERY = "UPDATE `" . UserDTO::TABLE_NAME .
    "` SET `username` = :username, `password_hash` = :password_hash, `email` = :email WHERE `id` = :id ;";
    
    /**
     * SQL query to perform a deletion on a user record.
     */
    private const DELETE_QUERY = "DELETE FROM `" . UserDTO::TABLE_NAME . "` WHERE `id` = :id";
    
    /**
     * Constructor
     */
    public function __construct() {}
    
    /**
     * Retrieve all user records from the 'users' table of the database.
     *
     * @return array An array of UserDTO objects representing the retrieved user records.
     * @throws RuntimeException If a database connection error occurs.
     * @throws ValidationException If validation of retrieved data fails.
     */
    public function getAll() : array {
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement.
        $statement = $connection->prepare("SELECT * FROM " . UserDTO::TABLE_NAME . " ;");
        
        // Execute the SQL statement.
        $statement->execute();
        
        // Fetch the results as an associative array.
        $result_set = $statement->fetchAll(PDO::FETCH_ASSOC);
        // $result_set = $statement->fetchAll(PDO::FETCH_CLASS, UserDTO::class);
        
        // Initialize an empty array to store UserDTO objects.
        $users = [];
        
        // Iterate over the result array and convert each row to a UserDTO object.
        foreach ($result_set as $result) {
            $users[] = UserDTO::fromDbArray($result);
        }
        
        // Return the array of UserDTO objects.
        return $users;
    }
    
    /**
     * Retrieve a user record from the 'users' table of the database by its ID.
     *
     * @param int $id The ID of the user record to retrieve.
     * @return UserDTO|null A UserDTO object representing the retrieved user record, or null if not found.
     * @throws RuntimeException If a database connection error occurs or if no record is found for the given ID.
     * @throws ValidationException If validation of retrieved data fails.
     */
    public function getById(int $id) : ?UserDTO {
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement.
        $statement = $connection->prepare(self::GET_QUERY);
        
        // Bind the ID parameter to the statement.
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        
        // Execute the SQL statement.
        $statement->execute();
        
        // Fetch the result as an associative array.
        $user_array = $statement->fetch(PDO::FETCH_ASSOC);
        
        // If the $user_array is bool and false.
        if (is_bool($user_array) && !$user_array) {
            // No record found for the given ID.
            return null;
        }
        
        // Convert the $user_array to a UserDTO object and return it.
        return UserDTO::fromDbArray($user_array);
    }
    
    /**
     * Create a new user record in the 'users' table of the database.
     *
     * @param UserDTO $user The UserDTO object representing the user to insert.
     * @return UserDTO The newly created UserDTO object.
     * @throws ValidationException If the <code>$user</code> object properties is not valid for insert.
     * @throws RuntimeException If a database connection error occurs or If the newly created user record cannot be retrieved from the database after insertion.
     */
    public function create(UserDTO $user) : UserDTO {
        
        // Validate the DTO object for database creation.
        $user->validateForDbCreation();
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement for user creation.
        $statement = $connection->prepare(self::CREATE_QUERY);
        
        // Bind the DTO properties to the statement parameters.
        $statement->bindValue(":username", $user->getUsername(), PDO::PARAM_STR);
        $statement->bindValue(":password_hash", $user->getPasswordHash(), PDO::PARAM_STR);
        $statement->bindValue(":email", $user->getEmail(), PDO::PARAM_STR);
        
        // Execute the SQL statement.
        $result = $statement->execute();
        if ($result === false) {
            throw new RuntimeException("Failed to retrieve the newly created user record.");
        }
        
        // Get the ID of the newly inserted user record.
        $new_id = (int) $connection->lastInsertId();
        
        // Retrieve the newly created user record from the database by its ID.
        $created_user = $this->getById($new_id);
        
        // Completed TODO: do something in the case that getById returns null. It shouldn't happen, but its a case to handle.
        // Handle the case where getById returns null (shouldn't happen).
        if ($created_user === null) {
            throw new RuntimeException("Failed to retrieve the newly created user record.");
        }
        
        // Return the newly created user as UserDTO object.
        return $created_user;
    }
    
    /**
     * Update an existing user record in the 'users' table of the database.
     *
     * @param UserDTO $user The UserDTO object representing the user to update.
     * @return UserDTO The updated UserDTO object.
     * @throws ValidationException If the <code>$user</code> object properties is not valid for update.
     * @throws RuntimeException If a database connection error occurs or if the updated user record cannot be retrieved from the database after update.
     */
    public function update(UserDTO $user) : UserDTO {
        
        // Validate the DTO object for database update.
        $user->validateForDbUpdate();
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement for user update.
        $statement = $connection->prepare(self::UPDATE_QUERY);
        
        // Bind the DTO properties to the statement parameters.
        $statement->bindValue(":id", $user->getId(), PDO::PARAM_INT);
        $statement->bindValue(":username", $user->getUsername(), PDO::PARAM_STR);
        $statement->bindValue(":password_hash", $user->getPasswordHash(), PDO::PARAM_STR);
        $statement->bindValue(":email", $user->getEmail(), PDO::PARAM_STR);
        
        // Execute the SQL statement.
        $result = $statement->execute();
        if ($result === false) {
            throw new RuntimeException("Failed to updated user record.");
        }
        
        // Retrieve the updated user record from the database.
        $updated_user = $this->getById($user->getId());
        
        // CompletedTODO: do something in the case that getById returns null. It shouldn't happen, but it's a case to handle.
        // Handle the case where getById returns null (shouldn't happen).
        if ($updated_user === null) {
            throw new RuntimeException("Failed to retrieve the updated user record.");
        }
        
        // Return the updated user as UserDTO object.
        return $updated_user;
    }
    
    /**
     * Delete a user record from the 'users' table of the database by ID.
     *
     * @param int $id The ID of the user to delete.
     * @return void
     * @throws RuntimeException If a database connection error occurs.
     */
    public function deleteById(int $id) : void {
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement based on the deletion type.
        $statement = $connection->prepare(self::DELETE_QUERY);
        
        // Bind the user ID parameter to the statement.
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        
        // Execute the SQL statement.
        $statement->execute();
    }
    
    /**
     * Delete a user record from the 'users' table of the database.
     *
     * @param UserDTO $user The UserDTO object representing the user to delete.
     * @return void
     * @throws ValidationException     If the <code>$user</code> object properties is not valid for delete.
     * @throws RuntimeException        If a database connection error occurs.
     */
    public function delete(UserDTO $user) : void {
        
        // Validate the DTO object for database deletion.
        $user->validateForDbDelete();
        
        // Call the deleteById method to perform the deletion.
        $this->deleteById($user->getId());
    }
    
    
    /**
     * Retrieves permissions associated with a user by their ID from the database.
     *
     * @param int $id The ID of the user.
     * @return array An array of PermissionDTO objects representing the permissions associated with the user.
     * @throws RuntimeException If a database connection error occurs.
     * @throws ValidationException If there's an issue with the validation of the retrieved data.
     */
    public function getPermissionsByUserId(int $id) : array {
        
        // Join query to retrieve permissions associated with the user by their ID from the `user_permissions` table.
        $query = "SELECT p.* FROM " . UserDTO::TABLE_NAME . " u JOIN " . UserPermissionDAO::TABLE_NAME . " up ON u.id = up.user_id JOIN " . PermissionDTO::TABLE_NAME . " p ON up.permission_id = p.id WHERE u.id = :userId";
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement.
        $statement = $connection->prepare($query);
        
        // Bind the value to the parameterized statement.
        $statement->bindValue(":userId", $id, PDO::PARAM_INT);
        
        // Execute the SQL statement.
        $statement->execute();
        
        // Fetch the results as an associative array.
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
     * Retrieves permissions associated with a user from the database.
     *
     * @param UserDTO $user The UserDTO object representing the user.
     * @return array An array of PermissionDTO objects representing the permissions associated with the user.
     * @throws ValidationException If the user object does not have an ID set.
     * @throws RuntimeException If a database connection error occurs.
     */
    public function getPermissionsByUser(UserDTO $user) : array {
        // Check if the user object has an ID set
        if (empty($user->getId())) {
            throw new ValidationException("Cannot get the permission records for a user with no set [id] property value.");
        }
        
        // Call the getPermissionsByUserId method with the user's ID
        return $this->getPermissionsByUserId($user->getId());
    }
    
    /**
     * Retrieves a user by username from the database.
     *
     * @param string $username The username of the user to retrieve.
     * @return UserDTO|null The UserDTO object representing the user, or null if no user is found with the given username.
     * @throws RuntimeException If a database connection error occurs.
     * @throws ValidationException If there's an issue with the validation of the retrieved data.
     */
    public function getUserByUsername(string $username) : ?UserDTO {
        
        // Establish a database connection.
        $connection = DBConnectionService::getConnection();
        
        // Prepare the SQL statement.
        $statement = $connection->prepare("SELECT * FROM `" . UserDTO::TABLE_NAME . "` WHERE `username` = :username ;");
        
        // Bind the username parameter to the statement.
        $statement->bindValue(":username", $username, PDO::PARAM_STR);
        
        // Execute the SQL statement.
        $statement->execute();
        
        // Fetch the result as an associative array.
        $user_array = $statement->fetch(PDO::FETCH_ASSOC);
        
        // If the $user_array is bool and false.
        if (is_bool($user_array) && !$user_array) {
            // No record found for the given ID.
            return null;
        }
        
        // Convert the $user_array to a UserDTO object and return it.
        return UserDTO::fromDbArray($user_array);
    }
}