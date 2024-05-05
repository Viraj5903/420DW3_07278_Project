<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project UserGroupDTO.php
 *
 * @author Viraj Patel
 * @since 2024-03-28
 */

namespace Viraj\Project\DTOs;

use DateTime;
use Exception;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Viraj\Project\DAOs\UserGroupsDAO;

/**
 * UserGroup DTO-type class
 */
class UserGroupDTO {
    
    // Class constants
    /**
     * Database table name for this DTO.
     * @const
     */
    public const TABLE_NAME = "user_groups";
    
    /**
     * Group name maximum length.
     * @const
     */
    public const GROUP_NAME_MAX_LENGTH = 64;
    
    /**
     * Description maximum length.
     * @const
     */
    public const DESCRIPTION_MAX_LENGTH = 256;
    
    // Class properties
    private int $id;
    private string $groupName;
    private ?string $description;
    private ?DateTime $creationDate = null;
    private ?DateTime $lastModificationDate = null;
    
    /**
     * Array of permission associated with this user_group.
     * @var PermissionDTO[]
     */
    private array $permissions = [];
    
    /**
     * Constructor
     */
    public function __construct() {}
    
    /**
     * Static constructor-like function to create instances of UserGroupDTO without an id or temporal management
     * attribute values. Used to create instances before inserting them in the database.
     *
     * @static
     * @param string $groupName   The initial value for the groupName property.
     * @param string $description The initial value for the description property.
     * @return UserGroupDTO The created instance of UserGroupDTO.
     * @throws ValidationException If an error occurs during setting the object properties.
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
        
        self::validateDbArray($dbAssocArray);
        $object = new UserGroupDTO();
        
        // Set the property values from the array parameter
        $object->setId((int) $dbAssocArray["id"]);
        $object->setGroupName($dbAssocArray["group_name"]);
        $object->setDescription($dbAssocArray["description"]);
        
        // conversion from DB-formatted datetime strings back into DateTime objects.
        $object->setCreationDate(
            DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbAssocArray["created_at"])
        );
        
        if (!empty($dbAssocArray["last_modified_at"])) {
            $object->setLastModificationDate(DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbAssocArray["last_modified_at"]));
        }
        
        return $object;
    }
    
    /**
     * Validating the array which we retrieved from the database.
     *
     * @throws ValidationException If array doesn't require data/ properties or if invalid data retrieve from the database.
     */
    private static function validateDbArray(array $dbArray) : void {
        
        if (empty($dbArray["id"])) {
            throw new ValidationException("Record array does not contain an [id] field. Check column names.", 500);
        }
        if (!is_numeric($dbArray["id"])) {
            throw new ValidationException("Record array [id] field is not numeric. Check column types.", 500);
        }
        if (empty($dbArray["group_name"])) {
            throw new ValidationException("Record array does not contain an [group_name] field. Check column names.", 500);
        }
        
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
     * Getter for <code>GroupName</code>.
     *
     * @return string The name of the user group.
     */
    public function getGroupName() : string {
        return $this->groupName;
    }
    
    /**
     *  Setter for <code>GroupName</code>.
     *
     * @param string $groupName The name of the user group to set.
     * @throws ValidationException If the group name exceeds the maximum length.
     */
    public function setGroupName(string $groupName) : void {
        if (mb_strlen($groupName) > self::GROUP_NAME_MAX_LENGTH) {
            throw new ValidationException("Group name value must not be longer than " . self::GROUP_NAME_MAX_LENGTH .
                                          " characters.");
        }
        $this->groupName = $groupName;
    }
    
    /**
     * Getter for <code>Description</code>.
     *
     * @return string|null The description of the user group.
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
            throw new ValidationException("Description value must not be longer than " . self::DESCRIPTION_MAX_LENGTH .
                                          " characters.");
        }
        $this->description = $description;
    }
    
    /**
     * Getter for <code>CreationDate</code>.
     *
     * @return DateTime|null The creation date of the user group or null if not set.
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
     * @return DateTime|null The last modification date of the user group or null if not set.
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
                throw new ValidationException("UserGroupDTO is not valid for DB updation: ID value is not set.");
            }
            return false;
        }
        // groupName is required
        if (empty($this->groupName)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserGroupDTO is not valid for DB updation: groupName value not set.");
            }
            return false;
        }
        // description is required
        if (empty($this->description)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserGroupDTO is not valid for DB updation: description value not set.");
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
                throw new ValidationException("UserGroupDTO is not valid for DB deletion: ID value is not set.");
            }
            return false;
        }
        return true;
    }
    
    /**
     * Retrieves permissions associated with the user group.
     *
     * @param bool $forceReload [Optional] If set to <code>true</code>, forces the reload of the permission records from the database. Defaults to <code>false</code>.
     * @return array An array of PermissionDTO objects representing the permissions associated with the user group.
     * @throws RuntimeException If there is an issue with loading the permission records.
     */
    public function getPermissions(bool $forceReload = false) : array {
        try {
            // If the permissions array is empty or forceReload is set to true, reload permissions from the database.
            if (empty($this->permissions) || $forceReload) {
                $this->loadPermissions();
            }
        } catch (Exception $exception) {
            // If an exception occurs during the loading of permissions, throw a RuntimeException.
            throw new RuntimeException("Failed to load permission entity records for user group id# [$this->id].", $exception->getCode(), $exception);
        }
        
        return $this->permissions;
    }
    
    /**
     * Loads permissions associated with the user group from the database.
     *
     * @return void
     * @throws RuntimeException If there is an issue with loading the permissions.
     * @throws ValidationException If there is an issue with the validation of the retrieved data.
     */
    public function loadPermissions() : void {
        $user_group_dao = new UserGroupsDAO();
        $this->permissions = $user_group_dao->getPermissionsByUserGroup($this);
    }
    
    /**
     * Converting UserGroupDTo object into JSON array.
     *
     * @return array
     */
    public function toArray() : array {
        $array = [
            "id" => $this->getId(),
            "groupName" => $this->getGroupName(),
            "description" => $this->getDescription(),
            "creationDate" => $this->getCreationDate()?->format(HTML_DATETIME_FORMAT),
            "lastModificationDate" => $this->getLastModificationDate()?->format(HTML_DATETIME_FORMAT),
            "permissions" => []
        ];
        
        foreach ($this->permissions as $permission) {
            $array["permissions"][$permission->getId()] = $permission->toArray();
        }
        
        return $array;
    }
}