<?php
declare(strict_types=1);

namespace Viraj\Project\DTOs;

use DateTime;
use Teacher\GivenCode\Abstracts\AbstractDTO;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 * Permission DTO-type class
 */
class PermissionDTO extends AbstractDTO {
    /**
     * Database table name for this DTO.
     * @const
     */
    public const TABLE_NAME = "permissions";
    private const DESCRIPTION_MAX_LENGTH = 256;
    
    
    private string $uniquePermission;
    private string $permissionName;
    private string $description;
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
    public static function fromValues(string $uniquePermission, string $permissionName,
                                      string $description) : PermissionDTO {
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
     * @param array $dbAssocArray The associative array of a fetched record of an PermissionDTO entity from the database.
     * @return PermissionDTO The created instance of PermissionDTO.
     * @throws ValidationException ValidationException is thrown when setting the passed arguments as property values.
     */
    public static function fromDbArray(array $dbAssocArray) : PermissionDTO {
        
        $object = new PermissionDTO();
        
        // Set the property values from the array parameter
        $object->setId((int) $dbAssocArray["id"]);
        $object->setUniquePermission($dbAssocArray["unique_permission"]);
        $object->setDescription($dbAssocArray["description"]);
        
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
     * Getter for <code>UniquePermission</code>.
     *
     * @return string
     */
    public function getUniquePermission() : string {
        return $this->uniquePermission;
    }
    
    /**
     * Setter for <code>UniquePermission</code>.
     *
     * @param string $uniquePermission
     */
    public function setUniquePermission(string $uniquePermission) : void {
        $this->uniquePermission = $uniquePermission;
    }
    
    /**
     * Getter for <code>PermissionName</code>.
     *
     * @return string
     */
    public function getPermissionName() : string {
        return $this->permissionName;
    }
    
    /**
     * Setter for <code>PermissionName</code>.
     *
     * @param string $permissionName
     */
    public function setPermissionName(string $permissionName) : void {
        $this->permissionName = $permissionName;
    }
    
    /**
     * Getter for <code>Description</code>.
     *
     * @return string
     */
    public function getDescription() : string {
        return $this->description;
    }
    
    /**
     *  Setter for <code>Description</code>.
     *
     * @param string $description
     * @throws ValidationException
     */
    public function setDescription(string $description) : void {
        if (mb_strlen($description) > self::DESCRIPTION_MAX_LENGTH) {
            throw new ValidationException("Description value must not be longer than " . self::DESCRIPTION_MAX_LENGTH .
                                          " characters.");
        }
        $this->description = $description;
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
     *  Setter for <code>CreationDate</code>.
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
        // deletionDate must not be set
        if (!is_null($this->deletionDate)) {
            if ($optThrowExceptions) {
                throw new ValidationException("PermissionDTO is not valid for DB creation: deletionDateTime value already set.");
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
                throw new ValidationException("PermissionDTO is not valid for DB creation: ID value is not set.");
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
                throw new ValidationException("PermissionDTO is not valid for DB creation: ID value is not set.");
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
            "deletionDate" => $this->getDeletionDate()->format(HTML_DATETIME_FORMAT),
        ];
        return json_encode($array, JSON_PRETTY_PRINT);
    }
}