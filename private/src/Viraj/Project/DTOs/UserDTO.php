<?php
declare(strict_types=1);

namespace Viraj\Project\DTOs;

use DateTime;
use Teacher\GivenCode\Abstracts\AbstractDTO;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 * User DTO-type class
 */
class UserDTO extends AbstractDTO {
    
    /**
     * Database table name for this DTO.
     * @const
     */
    public const TABLE_NAME = "users";
    private const USERNAME_MAX_LENGTH = 64;
    private const PASSWORD_HASH_MAX_LENGTH = 256;
    
    private string $username;
    private string $passwordHash;
    private string $email;
    private ?DateTime $creationDate;
    private ?DateTime $lastModificationDate;
    private ?DateTime $deletionDate;
    
    /**
     * Empty public constructor function.
     * This empty constructor allows the internal creation of instances with or without the normally required 'id' and other
     * database-managed attributes.
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Static constructor-like function to create instances of UserDTO without an id or temporal management
     * attribute values. Used to create instances before inserting them in the database.
     *
     * @static
     * @param string $username     The initial value for the username property.
     * @param string $passwordHash The initial value for the passwordHash property.
     * @param string $email        The initial value for the email property.
     * @return UserDTO The created instance of UserDTO.
     * @throws ValidationException ValidationException is thrown when setting the passed arguments as property values.
     */
    public static function fromValues(string $username, string $passwordHash, string $email) : UserDTO {
        $object = new UserDTO();
        
        // Set the property values from the parameters.
        // Using the setter methods allows me to validate the values on the spot.
        $object->setUsername($username);
        $object->setPasswordHash($passwordHash);
        $object->setEmail($email);
        
        // return the created instance
        return $object;
    }
    
    /**
     * Static constructor-like function to create instances of UserDTO with an id and temporal management
     * attribute values. Used to create instances from database-fetched arrays.
     *
     * @static
     * @param array $dbAssocArray The associative array of a fetched record of an UserDTO entity from the database.
     * @return UserDTO The created instance of UserGroupDTO.
     * @throws ValidationException ValidationException is thrown when setting the passed arguments as property values.
     */
    public static function fromDbArray(array $dbAssocArray) : UserDTO {
        $object = new UserDTO();
        
        // Set the property values from the array parameter
        $object->setId((int) $dbAssocArray["id"]);
        $object->setUsername($dbAssocArray["username"]);
        $object->setPasswordHash($dbAssocArray["password_hash"]);
        $object->setEmail($dbAssocArray["email"]);
        
        // conversion from DB-formatted datetime strings back into DateTime objects.
        $object->setCreationDate(
            DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbAssocArray["created_date"])
        );
        $object->setLastModificationDate(
            DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbAssocArray["last_modified_date"])
        );
        $object->setDeletionDate(
            DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbAssocArray["deleted_date"])
        );
        
        // return the created instance
        return $object;
    }
    
    /**
     * Get the Table name.
     *
     * @return string
     */
    public function getDatabaseTableName() : string {
        return self::TABLE_NAME;
    }
    
    /**
     *  Getter for <code>Username</code>.
     *
     * @return string
     */
    public function getUsername() : string {
        return $this->username;
    }
    
    /**
     * Setter for <code>Username</code>.
     *
     * @param string $username
     * @throws ValidationException
     */
    public function setUsername(string $username) : void {
        if (mb_strlen($username) > self::USERNAME_MAX_LENGTH) {
            throw new ValidationException("Username length must not be longer than " . self::USERNAME_MAX_LENGTH . ".");
        }
        $this->username = $username;
    }
    
    /**
     * Getter for <code>PasswordHash</code>.
     *
     * @return string
     */
    public function getPasswordHash() : string {
        return $this->passwordHash;
    }
    
    /**
     * Setter for <code>PasswordHash</code>.
     *
     * @param string $passwordHash
     * @throws ValidationException
     */
    public function setPasswordHash(string $passwordHash) : void {
        if (mb_strlen($passwordHash) > self::PASSWORD_HASH_MAX_LENGTH) {
            throw new ValidationException("Password hash length must not be longer than " .
                                          self::PASSWORD_HASH_MAX_LENGTH . ".");
        }
        $this->passwordHash = $passwordHash;
    }
    
    /**
     * Getter for <code>Email</code>.
     *
     * @return string
     */
    public function getEmail() : string {
        return $this->email;
    }
    
    /**
     * Setter for <code>Email</code>.
     *
     * @param string $email
     */
    public function setEmail(string $email) : void {
        $this->email = $email;
    }
    
    /**
     * Getter for <code>CreationDate</code>.
     *
     * @return DateTime|null
     */
    public function getCreationDate() : ?DateTime {
        return $this->creationDate;
    }
    
    /**
     * Setter for <code>CreationDate</code>.
     *
     * @param DateTime|null $creationDate
     */
    public function setCreationDate(?DateTime $creationDate) : void {
        $this->creationDate = $creationDate;
    }
    
    /**
     * Getter for <code>LastModificationDate</code>.
     *
     * @return DateTime|null
     */
    public function getLastModificationDate() : ?DateTime {
        return $this->lastModificationDate;
    }
    
    /**
     * Setter for <code>LastModificationDate</code>.
     *
     * @param DateTime|null $lastModificationDate
     */
    public function setLastModificationDate(?DateTime $lastModificationDate) : void {
        $this->lastModificationDate = $lastModificationDate;
    }
    
    /**
     * Getter for <code>DeletionDate</code>.
     *
     * @return DateTime|null
     */
    public function getDeletionDate() : ?DateTime {
        return $this->deletionDate;
    }
    
    /**
     * Setter for <code>DeletionDate</code>.
     *
     * @param DateTime|null $deletionDate
     */
    public function setDeletionDate(?DateTime $deletionDate) : void {
        $this->deletionDate = $deletionDate;
    }
    
    /**
     * Validates the instance for creation of its record in the database.
     *
     * @param bool $optThrowExceptions [OPTIONAL] Whether to throw exceptions or not if invalid. Defaults to true.
     * @return bool <code>True</code> if valid, <code>false</code> otherwise.
     * @throws ValidationException If the instance is invalid and the <code>$optThrowExceptions</code> parameter is <code>true</code>.
     */
    public function validateForDbCreation(bool $optThrowExceptions = true) : bool {
        // ID must not be set
        if (!empty($this->id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: ID value already set.");
            }
            return false;
        }
        // username is required
        if (empty($this->username)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: dayOfTheWeek value not set.");
            }
            return false;
        }
        // passwordHash is required
        if (empty($this->passwordHash)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: passwordHash value not set.");
            }
            return false;
        }
        // email is required
        if (empty($this->email)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: email value not set.");
            }
            return false;
        }
        // creationDate must not be set
        if (!is_null($this->creationDate)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: creationDateTime value already set.");
            }
            return false;
        }
        // lastModification must not be set
        if (!is_null($this->lastModificationDate)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: lastModificationDate value already set.");
            }
            return false;
        }
        // deletionDate must not be set
        if (!is_null($this->deletionDate)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: deletionDateTime value already set.");
            }
            return false;
        }
        return true;
    }
    
    /**
     * Validates the instance for the update of its record in the database.
     *
     * @param bool $optThrowExceptions [OPTIONAL] Whether to throw exceptions or not if invalid. Defaults to true.
     * @return bool <code>True</code> if valid, <code>false</code> otherwise.
     * @throws ValidationException If the instance is invalid and the <code>$optThrowExceptions</code> parameter is <code>true</code>.
     */
    public function validateForDbUpdate(bool $optThrowExceptions = true) : bool {
        // ID is required
        if (empty($this->id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: ID value is not set.");
            }
            return false;
        }
        // username is required
        if (empty($this->username)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: dayOfTheWeek value not set.");
            }
            return false;
        }
        // passwordHash is required
        if (empty($this->passwordHash)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: passwordHash value not set.");
            }
            return false;
        }
        // email is required
        if (empty($this->email)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: email value not set.");
            }
            return false;
        }
        return true;
    }
    
    /**
     * Validates the instance for the deletion of its record in the database.
     *
     * @param bool $optThrowExceptions [OPTIONAL] Whether to throw exceptions or not if invalid. Defaults to true.
     * @return bool <code>True</code> if valid, <code>false</code> otherwise.
     * @throws ValidationException If the instance is invalid and the <code>$optThrowExceptions</code> parameter is <code>true</code>.
     */
    public function validateForDbDelete(bool $optThrowExceptions = true) : bool {
        // ID is required
        if (empty($this->id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: ID value is not set.");
            }
            return false;
        }
        return true;
    }
    
    /**
     * Function that convert UserDTO object into JSON.
     *
     * @return string
     */
    public function toJson() : string {
        $array = [
            "id" => $this->getId(),
            "username" => $this->getUsername(),
            "passwordHash" => $this->getPasswordHash(),
            "email" => $this->getEmail(),
            "creationDate" => $this->getCreationDate()->format(HTML_DATETIME_FORMAT),
            "lastModificationDate" => $this->getLastModificationDate()->format(HTML_DATETIME_FORMAT),
            "deletionDate" => $this->getDeletionDate()->format(HTML_DATETIME_FORMAT),
        ];
        return json_encode($array, JSON_PRETTY_PRINT);
    }
}