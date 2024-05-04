<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project UsersService.php
 *
 * This file defines the UsersService class, responsible for managing user-related operations.
 * It interacts with the UsersDAO class to perform CRUD operations on user data.
 *
 * This file contains the UsersService class, which provides methods for interacting with user.
 * It handles CRUD operations for user, utilizing the UsersDAO class for database interaction.
 *
 * @author Viraj Patel
 * @since 2024-04-02
 */

namespace Viraj\Project\Services;

use Exception;
use Viraj\Project\DAOs\UserPermissionDAO;
use Viraj\Project\DTOs\UserDTO;
use Viraj\Project\DAOs\UsersDAO;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 * Service class for users operation.
 */
class UsersService {
    
    // Class properties
    private UsersDAO $usersDAO; // UsersDAO object for interacting with the users table of the database.
    private CryptographyService $cryptographyService; // CryptographyService object for hashing passwords.
    private UserPermissionDAO $userPermissionDAO; // CryptographyService object for hashing passwords.
    
    /**
     * Constructor for UsersService class.
     * Initializes UsersDAO object for database interaction and CryptographyService object the hashing and validation
     * of user passwords.
     */
    public function __construct() {
        $this->usersDAO = new UsersDAO(); // Initialize UsersDAO object.
        $this->cryptographyService = new CryptographyService(); // Initialize CryptographyService object.
        $this->userPermissionDAO = new UserPermissionDAO();
    }
    
    /**
     * Retrieves all users from the database.
     *
     * @return UserDTO[] An array of UserDTO objects representing users.
     * @throws ValidationException If validation of retrieved data fails.
     * @throws RuntimeException If a database connection error occurs.
     */
    public function getAllUsers() : array {
        return $this->usersDAO->getAll();
    }
    
    
    /**
     * Retrieves a user with associated permissions by their ID from the database.
     *
     * @param int $id The ID of the user to retrieve.
     * @return UserDTO|null The UserDTO object representing the user, or null if not found.
     * @throws RuntimeException If a database connection error occurs if no record is found for the given ID.
     * @throws ValidationException If validation of retrieved data fails.
     */
    public function getUserById(int $id) : ?UserDTO {
        // return $this->usersDAO->getById($id);
        $user = $this->usersDAO->getById($id);
        $user?->loadPermissions();
        return $user;
    }
    
    /**
     * Creates a new user in the database.
     *
     * @param string $username The username of the new user.
     * @param string $password The password of the new user.
     * @param string $email    The email of the new user.
     * @return UserDTO The UserDTO object representing the newly created user.
     * @throws RuntimeException If failure to create and insert new user into the database.
     */
    public function createUser(string $username, string $password, string $email, array $permissions) : UserDTO {
        
        try {
            $hash_password = $this->cryptographyService->hashPassword($password); // Hash the password.
            $user = UserDTO::fromValues($username, $hash_password, $email);
            
            $user = $this->usersDAO->create($user);
            $this->userPermissionDAO->createManyForUser($user->getId(), $permissions);
            
            return $this->getUserById($user->getId());
        } catch (Exception $exception) {
            throw new RuntimeException("Failure to create user [$username, $email]." ,(int) $exception->getCode(), $exception);
        }
        
    }
    
    /**
     * Updates an existing user in the database.
     *
     * @param int    $id       The ID of the user to update.
     * @param string $username The new username for the user.
     * @param string $password The new password for the user.
     * @param string $email    The new email for the user.
     * @return UserDTO The UserDTO object representing the updated user.
     * @throws RuntimeException If failure to update user into the database.
     */
    public function updateUser(int $id, string $username, string $password, string $email, array $permissions) : UserDTO {
        
        try {
            
            $connection = DBConnectionService::getConnection(); // Get database connection.
            $connection->beginTransaction(); // Begin database transaction.
            
            try {
                $user = $this->usersDAO->getById($id); // Retrieve the user by ID.
                
                // Checking whether $user is null or not.
                if (is_null($user)) {
                    throw new Exception("User id# [$id] not found in the database.");
                }
                
                $user->setUsername($username); // Set the new username.
                
                $hash_password = $this->cryptographyService->hashPassword($password); // Hash the new password.
                
                $user->setPasswordHash($hash_password); // Set the hashed password.
                
                $user->setEmail($email); // Set the new email.
                
                $result = $this->usersDAO->update($user); // Update the user in the database.
                
                // Removing all old permissions.
                $this->userPermissionDAO->deleteAllByUserId($result->getId());
                
                // Adding all new permisssions.
                if (!empty($permissions)) {
                    $this->userPermissionDAO->createManyForUser($result->getId(), $permissions);
                }
                //$this->userPermissionDAO->createManyForUser($result->getId(), $permissions);
                
                $connection->commit(); // Commit the transaction to save changes.
                
                return $this->getUserById($result->getId()); // Return the updated user.
                
            } catch (Exception $inner_exception) {
                $connection->rollBack();
                throw $inner_exception;
            }
            
        } catch (Exception $exception) {
            throw new RuntimeException("Failure to update user id#[$id].", (int) $exception->getCode(), $exception);
        }
        
    }
    
    /**
     * Deletes a user by their ID from the database.
     *
     * @param int $id The ID of the user to delete.
     * @return void
     * @throws RuntimeException If failure to delete user from the database.
     */
    public function deleteUser(int $id) : void {
        
        try {
            
            $connection = DBConnectionService::getConnection(); // Get database connection.
            $connection->beginTransaction(); // Begin database transaction.
            
            try {
                $user = $this->usersDAO->getById($id); // Retrieve the user by ID.
                
                // Checking whether $user is null or not.
                if (is_null($user)) {
                    throw new Exception("User id# [$id] not found in the database.");
                }
                
                // Removing permissions first.
                $this->userPermissionDAO->deleteAllByUserId($user->getId());
                
                $this->usersDAO->delete($user); // Delete the user from the database.
                
                $connection->commit(); // Commit the transaction to save changes.
                
            } catch (Exception $inner_exception) {
                $connection->rollBack();
                throw $inner_exception;
            }
            
        } catch (Exception $exception) {
            throw new RuntimeException("Failure to delete user id# [$id].", (int) $exception->getCode(), $exception);
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
    public function getUserPermissionByUserId(int $id) : array {
        return $this->usersDAO->getPermissionsByUserId($id);
    }
    
    
    /**
     *
     *
     * @param UserDTO $user
     * @return array
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getUserPermission(UserDTO $user) : array {
        return $this->getUserPermissionByUserId($user->getId());
    }
    
    /**
     *
     * @param string $username
     * @param string $password
     * @return UserDTO|null|int
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function validateUser(string $username, string $password) : UserDTO|null|false {
        
        $user = $this->usersDAO->getUserByUsername($username);
        
        if (is_null($user)) {
            return null;
        }
        
        $result = $this->cryptographyService->comparePassword($password, $user->getPasswordHash());
        
        if ($result === false) {
            return false;
        }
        
        return $user;
    }
}