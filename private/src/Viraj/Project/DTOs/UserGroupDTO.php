<?php
declare(strict_types=1);

namespace Viraj\Project\DTOs;

use DateTime;
use Teacher\GivenCode\Abstracts\AbstractDTO;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 * UserGroup DTO-type class
 */
class UserGroupDTO extends AbstractDTO {
    /**
     * Database table name for this DTO.
     * @const
     */
    public const TABLE_NAME = "user_groups";
    private const DESCRIPTION_MAX_LENGTH = 256;
    private string $groupName;
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
     * Static constructor-like function to create instances of UserGroupDTO without an id or temporal management
     * attribute values. Used to create instances before inserting them in the database.
     *
     * @static
     * @param string $groupName   The initial value for the groupName property.
     * @param string $description The initial value for the description property.
     * @return UserGroupDTO The created instance of UserGroupDTO.
     * @throws ValidationException ValidationException is thrown when setting the passed arguments as property values.
     */
    public static function fromValues(string $groupName, string $description) : UserGroupDTO {
        $object = new UserGroupDTO();
        
        // Set the property values from the parameters.
        // Using the setter methods allows me to validate the values on the spot.
        $object->setGroupName($groupName);
        $object->setDescription($description);
        
        // return the created instance
        return $object;
    }
    
    /**
     * Static constructor-like function to create instances of UserGroupDTO with an id and temporal management
     * attribute values. Used to create instances from database-fetched arrays.
     *
     * @static
     * @param array $dbAssocArray The associative array of a fetched record of an UserGroupDTO entity from the database.
     * @return UserGroupDTO The created instance of UserGroupDTO.
     * @throws ValidationException ValidationException is thrown when setting the passed arguments as property values.
     */
    public static function fromDbArray(array $dbAssocArray) : UserGroupDTO {
        
        $object = new UserGroupDTO();
        
        // Set the property values from the array parameter
        $object->setId((int) $dbAssocArray["id"]);
        $object->setGroupName($dbAssocArray["group_name"]);
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
     * Getter for <code>GroupName</code>.
     *
     * @return string
     */
    public function getGroupName() : string {
        return $this->groupName;
    }
    
    /**
     *  Setter for <code>GroupName</code>.
     *
     * @param string $groupName
     */
    public function setGroupName(string $groupName) : void {
        $this->groupName = $groupName;
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
                throw new ValidationException("UserGroupDTO is not valid for DB creation: ID value already set.");
            }
            return false;
        }
        // groupName is required
        if (empty($this->groupName)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserGroupDTO is not valid for DB creation: groupName value not set.");
            }
            return false;
        }
        // description is required
        if (empty($this->description)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserGroupDTO is not valid for DB creation: description value not set.");
            }
            return false;
        }
        // creationDate must not be set
        if (!is_null($this->creationDate)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserGroupDTO is not valid for DB creation: creationDateTime value already set.");
            }
            return false;
        }
        // lastModification must not be set
        if (!is_null($this->lastModificationDate)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserGroupDTO is not valid for DB creation: lastModificationDate value already set.");
            }
            return false;
        }
        // deletionDate must not be set
        if (!is_null($this->deletionDate)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserGroupDTO is not valid for DB creation: deletionDateTime value already set.");
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
                throw new ValidationException("UserGroupDTO is not valid for DB creation: ID value is not set.");
            }
            return false;
        }
        // groupName is required
        if (empty($this->groupName)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserGroupDTO is not valid for DB creation: groupName value not set.");
            }
            return false;
        }
        // description is required
        if (empty($this->description)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserGroupDTO is not valid for DB creation: description value not set.");
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
                throw new ValidationException("UserGroupDTO is not valid for DB creation: ID value is not set.");
            }
            return false;
        }
        return true;
    }
    
    /**
     * Function that convert UserGroupDTO object into JSON.
     *
     * @return string
     */
    public function toJson() : string {
        $array = [
            "id" => $this->getId(),
            "groupName" => $this->getGroupName(),
            "description" => $this->getDescription(),
            "creationDate" => $this->getCreationDate()->format(HTML_DATETIME_FORMAT),
            "lastModificationDate" => $this->getLastModificationDate()->format(HTML_DATETIME_FORMAT),
            "deletionDate" => $this->getDeletionDate()->format(HTML_DATETIME_FORMAT),
        ];
        return json_encode($array, JSON_PRETTY_PRINT);
    }
}