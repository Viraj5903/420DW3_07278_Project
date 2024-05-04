<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project Permission.php
 *
 * @author Viraj Patel
 * @since 2024-03-28
 */

namespace Viraj\Project\DTOs;

use DateTime;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 * Permission DTO-type class
 */
class PermissionDTO {
    
    // Class constants
    
    /**
     * Database table name for this DTO.
     * @const
     */
    public const TABLE_NAME = "permissions";
    public const DESCRIPTION_MAX_LENGTH = 1024;
    public const PERMISSION_MAX_LENGTH = 256;
    
    // Class properties
    private int $id;
    private string $uniquePermission;
    private string $permissionName;
    private ?string $description;
    private ?DateTime $creationDate = null;
    private ?DateTime $lastModificationDate = null;
    
    /**
     * Empty public constructor function.
     * This empty constructor allows the internal creation of instances with or without the normally required 'id' and
     * other database-managed attributes.
     */
    public function __construct() {}
    
    /**
     * Static constructor-like function to create instances of PermissionDTO without an id or temporal management
     * attribute values. Used to create instances before inserting them in the database.
     *
     * @static
     * @param string $uniquePermission The initial value for the uniquePermission property.
     * @param string $permissionName   The initial value for the permissionName property.
     * @param string $description      The initial value for the description property.
     * @return PermissionDTO The created instance of PermissionDTO.
     * @throws ValidationException ValidationException is thrown when setting the passed arguments as property values.
     */
    public static function fromValues(string $uniquePermission, string $permissionName, string $description) : PermissionDTO {
        
        $object = new PermissionDTO();
        
        // Set the property values from the parameters.
        // Using the setter methods allows me to validate the values on the spot.
        $object->setUniquePermission($uniquePermission);
        $object->setPermissionName($permissionName);
        $object->setDescription($description);
        
        // return the created instance
        return $object;
    }
    
    /**
     * Static constructor-like function to create instances of PermissionDTO with an id and temporal management
     * attribute values. Used to create instances from database-fetched arrays.
     *
     * @static
     * @param array $dbAssocArray The associative array of a fetched record of an PermissionDTO entity from the
     *                            database.
     * @return PermissionDTO The created instance of PermissionDTO.
     * @throws ValidationException ValidationException is thrown when setting the passed arguments as property values.
     */
    public static function fromDbArray(array $dbAssocArray) : PermissionDTO {
        
        self::validateDbArray($dbAssocArray);
        
        $object = new PermissionDTO();
        
        // Set the property values from the array parameter
        $object->setId((int) $dbAssocArray["id"]);
        $object->setUniquePermission($dbAssocArray["unique_permission"]);
        $object->setPermissionName($dbAssocArray["permission_name"]);
        $object->setDescription($dbAssocArray["description"]);
        
        // conversion from DB-formatted datetime strings back into DateTime objects.
        $object->setCreationDate(
            DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbAssocArray["created_at"])
        );
        
        if (!empty($dbArray["last_modified_at"])) {
            $object->setLastModificationDate(DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["last_modified_at"]));
        }
        
        // return the created instance
        return $object;
    }
    
    /**
     * Validating the array which we retrieved from the database.
     *
     * @throws ValidationException If array doesn't require data/ properties or if invalid data retrieve from the
     *                             database.
     */
    private static function validateDbArray(array $dbArray) : void {
        
        if (empty($dbArray["id"])) {
            throw new ValidationException("Record array does not contain an [id] field. Check column names.", 500);
        }
        if (!is_numeric($dbArray["id"])) {
            throw new ValidationException("Record array [id] field is not numeric. Check column types.", 500);
        }
        if (empty($dbArray["unique_permission"])) {
            throw new ValidationException("Record array does not contain an [unique_permission] field. Check column names.", 500);
        }
        if (empty($dbArray["permission_name"])) {
            throw new ValidationException("Record array does not contain an [permission_name] field. Check column names.", 500);
        }
//        if (array_key_exists("description", $dbArray)) {
//            throw new ValidationException("Record array does not contain an [description] field. Check column names.", 500);
//        }
        
        if (empty($dbArray["created_at"])) {
            throw new ValidationException("Record array does not contain an [created_at] field. Check column names.", 500);
        }
        if (DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["created_at"]) === false) {
            throw new ValidationException("Failed to parse [created_at] field as DateTime. Check column types.", 500);
        }
        if (!empty($dbArray["last_modified_at"]) &&
            (DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["last_modified_at"]) === false)
        ) {
            throw new ValidationException("Failed to parse [last_modified_at] field as DateTime. Check column types.", 500);
        }
    }
    
    /**
     * Get the Table name.
     *
     * @return string The name of the database table associated with this DTO.
     */
    public function getDatabaseTableName() : string {
        return self::TABLE_NAME;
    }
    
    /**
     * Getter for <code>Id</code>
     *
     * @return int
     */
    public function getId() : int {
        return $this->id;
    }
    
    /**
     * Setter for <code>Id</code>
     *
     * @param int $id
     * @throws ValidationException If the id is lower than 1.
     */
    public function setId(int $id) : void {
        if ($id < 1) {
            throw new ValidationException("Id value cannot be inferior to 1.");
        }
        $this->id = $id;
    }
    
    /**
     * Getter for <code>UniquePermission</code>.
     *
     * @return string The unique identifier for the permission.
     */
    public function getUniquePermission() : string {
        return $this->uniquePermission;
    }
    
    /**
     * Setter for <code>UniquePermission</code>.
     *
     * @param string $uniquePermission The unique identifier to set.
     */
    public function setUniquePermission(string $uniquePermission) : void {
        $this->uniquePermission = $uniquePermission;
    }
    
    /**
     * Getter for <code>PermissionName</code>.
     *
     * @return string The name of the permission.
     */
    public function getPermissionName() : string {
        return $this->permissionName;
    }
    
    /**
     * Setter for <code>PermissionName</code>.
     *
     * @param string $permissionName The name of the permission to set.
     * @throws ValidationException If the permission exceeds the maximum length.
     */
    public function setPermissionName(string $permissionName) : void {
        if (mb_strlen($permissionName) > self::PERMISSION_MAX_LENGTH) {
            throw new ValidationException("Permission value must not be longer than " . self::PERMISSION_MAX_LENGTH . " characters.");
        }
        $this->permissionName = $permissionName;
    }
    
    /**
     * Getter for <code>Description</code>.
     *
     * @return string|null The description of the permission.
     */
    public function getDescription() : ?string {
        return $this->description;
    }
    
    /**
     *  Setter for <code>Description</code>.
     *
     * @param string|null $description The description to set.
     * @throws ValidationException If the description exceeds the maximum length.
     */
    public function setDescription(?string $description) : void {
        if (!empty($description) && (mb_strlen($description) > self::DESCRIPTION_MAX_LENGTH)) {
            throw new ValidationException("Description value must not be longer than " . self::DESCRIPTION_MAX_LENGTH . " characters.");
        }
        $this->description = $description;
    }
    
    /**
     * Getter for <code>CreationDate</code>.
     *
     * @return DateTime|null The creation date of the permission or null if not set.
     */
    public function getCreationDate() : ?DateTime {
        return $this->creationDate;
    }
    
    /**
     *  Setter for <code>CreationDate</code>.
     *
     * @param DateTime|null $creationDate The creation date to set.
     */
    public function setCreationDate(?DateTime $creationDate) : void {
        $this->creationDate = $creationDate;
    }
    
    /**
     * Getter for <code>LastModificationDate</code>.
     *
     * @return DateTime|null The last modification date of the permission or null if not set.
     */
    public function getLastModificationDate() : ?DateTime {
        return $this->lastModificationDate;
    }
    
    /**
     * Setter for <code>LastModificationDate</code>.
     *
     * @param DateTime|null $lastModificationDate The last modification date to set.
     */
    public function setLastModificationDate(?DateTime $lastModificationDate) : void {
        $this->lastModificationDate = $lastModificationDate;
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
                throw new ValidationException("PermissionDTO is not valid for DB creation: ID value already set.");
            }
            return false;
        }
        // uniquePermission is required
        if (empty($this->uniquePermission)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB creation: uniquePermission value not set.");
            }
            return false;
        }
        // permissionName is required
        if (empty($this->permissionName)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB creation: permissionName value not set.");
            }
            return false;
        }
        // description is required
        if (empty($this->description)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB creation: description value not set.");
            }
            return false;
        }
        // creationDate must not be set
        if (!is_null($this->creationDate)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB creation: creationDateTime value already set.");
            }
            return false;
        }
        // lastModification must not be set
        if (!is_null($this->lastModificationDate)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB creation: lastModificationDate value already set.");
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
                throw new ValidationException("PermissionDTO is not valid for DB updation: ID value is not set.");
            }
            return false;
        }
        // uniquePermission is required
        if (empty($this->uniquePermission)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB updation: uniquePermission value not set.");
            }
            return false;
        }
        // permissionName is required
        if (empty($this->permissionName)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB updation: permissionName value not set.");
            }
            return false;
        }
        // description is required
        if (empty($this->description)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB updation: description value not set.");
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
                throw new ValidationException("PermissionDTO is not valid for DB deletion: ID value is not set.");
            }
            return false;
        }
        return true;
    }
    
    /**
     * Function that convert PermissionDTO object into JSON.
     *
     * @return string
     */
    public function toJson() : string {
        $array = [
            "id" => $this->getId(),
            "uniquePermission" => $this->getUniquePermission(),
            "permissionName" => $this->getPermissionName(),
            "description" => $this->getDescription(),
            "creationDate" => $this->getCreationDate()->format(HTML_DATETIME_FORMAT),
            "lastModificationDate" => $this->getLastModificationDate()->format(HTML_DATETIME_FORMAT),
        ];
        return json_encode($array, JSON_PRETTY_PRINT);
    }
    
    /**
     * Converting PermissionDTO object into array.
     *
     * @return array
     */
    public function toArray() : array {
        return [
            "id" => $this->getId(),
            "uniquePermission" => $this->getUniquePermission(),
            "permissionName" => $this->getPermissionName(),
            "description" => $this->getDescription(),
            "creationDate" => $this->getCreationDate()?->format(HTML_DATETIME_FORMAT),
            "lastModificationDate" => $this->getLastModificationDate()?->format(HTML_DATETIME_FORMAT),
        ];
    }
    
}