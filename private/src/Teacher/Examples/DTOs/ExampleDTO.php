<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project ExampleDTO.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-14
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\Examples\DTOs;

use DateTime;
use Teacher\Examples\Enumerations\DaysOfWeekEnum;
use Teacher\GivenCode\Abstracts\AbstractDTO;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 * Example DTO-type class
 *
 * @author Marc-Eric Boury
 * @since  2024-03-14
 */
class ExampleDTO extends AbstractDTO {
    
    /**
     * Database table name for this DTO.
     * @const
     */
    public const TABLE_NAME = "examples";
    private const DESCRIPTION_MAX_LENGTH = 256;
    
    
    private DaysOfWeekEnum $dayOfTheWeek;
    private string $description;
    private ?DateTime $creationDate = null;
    private ?DateTime $lastModificationDate = null;
    private ?DateTime $deletionDate = null;
    
    
    // <editor-fold defaultstate="collapsed" desc="CONSTRUCTORS">
    
    /**
     * Empty protected constructor function.
     * Use {@see ExampleDTO::fromValues()} or {@see ExampleDTO::fromDbArray()} to create object instances.
     *
     * This empty constructor allows the internal creation of instances with or without the normally required 'id' and other
     * database-managed attributes.
     *
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Static constructor-like function to create {@see ExampleDTO} instances without an id or temporal management
     * attribute values. Used to create instances before inserting them in the database.
     *
     * @static
     * @param DaysOfWeekEnum $dayOfTheWeek The initial value for the {@see ExampleDTO::$dayOfTheWeek} property.
     * @param string         $description  The initial value for the {@see ExampleDTO::$description} property.
     * @return ExampleDTO The created {@see ExampleDTO} instance.
     *
     * @throws ValidationException If a {@see ValidationException} is thrown when setting the passed arguments as property values.
     * @author Marc-Eric Boury
     * @since  2024-03-16
     */
    public static function fromValues(DaysOfWeekEnum $dayOfTheWeek, string $description) : ExampleDTO {
        // Use the protected constructor to create an empty ExampleDTO instance
        $object = new ExampleDTO();
        
        // Set the property values from the parameters.
        // Using the setter methods allows me to validate the values on the spot.
        $object->setDayOfTheWeek($dayOfTheWeek);
        $object->setDescription($description);
        
        // return the created instance
        return $object;
    }
    
    /**
     * Static constructor-like function to create {@see ExampleDTO} instances with an id and temporal management
     * attribute values. Used to create instances from database-fetched arrays.
     *
     * @static
     * @param array $dbAssocArray The associative array of a fetched record of an {@see ExampleDTO} entity from the
     *                            database.
     * @return ExampleDTO The created {@see ExampleDTO} instance.
     *
     * @throws ValidationException If a {@see ValidationException} is thrown when setting the passed arguments as property values.
     * @author Marc-Eric Boury
     * @since  2024-03-16
     */
    public static function fromDbArray(array $dbAssocArray) : ExampleDTO {
        // Use the protected constructor to create an empty ExampleDTO instance
        $object = new ExampleDTO();
        
        // Set the property values from the array parameter
        // do not forget to cast numeric values!
        $object->setId((int) $dbAssocArray["id"]);
        // conversion from DB string values back to enumeration value
        $object->setDayOfTheWeek(DaysOfWeekEnum::from($dbAssocArray["dayOfTheWeek"]));
        $object->setDescription($dbAssocArray["description"]);
        // conversion from DB-formatted datetime strings back into DateTime objects.
        $object->setCreationDate(
            DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbAssocArray["created_at"])
        );
        $object->setLastModificationDate(
            (empty($dbAssocArray["last_modified_at"])) ? null
                : DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbAssocArray["last_modified_at"])
        );
        $object->setDeletionDate(
            (empty($dbAssocArray["deleted_at"])) ? null
                : DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbAssocArray["deleted_at"])
        );
        
        // return the created instance
        return $object;
    }
    
    // </editor-fold>
    
    
    /**
     * @inheritDoc
     */
    public function getDatabaseTableName() : string {
        return self::TABLE_NAME;
    }
    
    // <editor-fold defaultstate="collapsed" desc="GETTERS AND SETTERS">
    
    /**
     * Getter for <code>DayOfTheWeek</code>
     *
     * @return DaysOfWeekEnum
     */
    public function getDayOfTheWeek() : DaysOfWeekEnum {
        return $this->dayOfTheWeek;
    }
    
    /**
     * Setter for <code>DayOfTheWeek</code>
     *
     * @param DaysOfWeekEnum $dayOfTheWeek
     */
    public function setDayOfTheWeek(DaysOfWeekEnum $dayOfTheWeek) : void {
        $this->dayOfTheWeek = $dayOfTheWeek;
    }
    
    /**
     * Getter for <code>Description</code>
     *
     * @return string
     */
    public function getDescription() : string {
        return $this->description;
    }
    
    /**
     * Setter for <code>Description</code>
     *
     * @param string $description
     * @throws ValidationException If the value is invalid.
     */
    public function setDescription(string $description) : void {
        if (mb_strlen($description) > self::DESCRIPTION_MAX_LENGTH) {
            throw new ValidationException("Description value must not be longer than " . self::DESCRIPTION_MAX_LENGTH .
                                          " characters.");
        }
        $this->description = $description;
    }
    
    /**
     * Getter for <code>CreationDate</code>
     *
     * @return DateTime|null
     */
    public function getCreationDate() : ?DateTime {
        return $this->creationDate;
    }
    
    /**
     * Setter for <code>CreationDate</code>
     *
     * @param DateTime|null $creationDate
     */
    public function setCreationDate(?DateTime $creationDate) : void {
        $this->creationDate = $creationDate;
    }
    
    /**
     * Getter for <code>LastModificationDate</code>
     *
     * @return DateTime|null
     */
    public function getLastModificationDate() : ?DateTime {
        return $this->lastModificationDate;
    }
    
    /**
     * Setter for <code>LastModificationDate</code>
     *
     * @param DateTime|null $lastModificationDate
     */
    public function setLastModificationDate(?DateTime $lastModificationDate) : void {
        $this->lastModificationDate = $lastModificationDate;
    }
    
    /**
     * Getter for <code>DeletionDate</code>
     *
     * @return DateTime|null
     */
    public function getDeletionDate() : ?DateTime {
        return $this->deletionDate;
    }
    
    /**
     * Setter for <code>DeletionDate</code>
     *
     * @param DateTime|null $deletionDate
     */
    public function setDeletionDate(?DateTime $deletionDate) : void {
        $this->deletionDate = $deletionDate;
    }
    
    // </editor-fold>
    
    
    // <editor-fold defaultstate="collapsed" desc="VALIDATION METHODS">
    
    /**
     * Validates the instance for creation of its record in the database.
     *
     * @param bool $optThrowExceptions [OPTIONAL] Whether to throw exceptions or not if invalid. Defaults to true.
     * @return bool <code>True</code> if valid, <code>false</code> otherwise.
     * @throws ValidationException If the instance is invalid and the <code>$optThrowExceptions</code> parameter is <code>true</code>.
     *
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function validateForDbCreation(bool $optThrowExceptions = true) : bool {
        // ID must not be set
        if (!empty($this->id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("ExampleDTO is not valid for DB creation: ID value already set.");
            }
            return false;
        }
        // dayOfTheWeek is required
        if (empty($this->dayOfTheWeek)) {
            if ($optThrowExceptions) {
                throw new ValidationException("ExampleDTO is not valid for DB creation: dayOfTheWeek value not set.");
            }
            return false;
        }
        // description is required
        if (empty($this->description)) {
            if ($optThrowExceptions) {
                throw new ValidationException("ExampleDTO is not valid for DB creation: description value not set.");
            }
            return false;
        }
        // creationDate must not be set
        if (!is_null($this->creationDate)) {
            if ($optThrowExceptions) {
                throw new ValidationException("ExampleDTO is not valid for DB creation: creationDate value already set.");
            }
            return false;
        }
        // lastModificationDate must not be set
        if (!is_null($this->lastModificationDate)) {
            if ($optThrowExceptions) {
                throw new ValidationException("ExampleDTO is not valid for DB creation: lastModificationDate value already set.");
            }
            return false;
        }
        // deletionDate must not be set
        if (!is_null($this->deletionDate)) {
            if ($optThrowExceptions) {
                throw new ValidationException("ExampleDTO is not valid for DB creation: deletionDate value already set.");
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
     *
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function validateForDbUpdate(bool $optThrowExceptions = true) : bool {
        // ID is required
        if (empty($this->id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("ExampleDTO is not valid for DB creation: ID value is not set.");
            }
            return false;
        }
        // dayOfTheWeek is required
        if (empty($this->dayOfTheWeek)) {
            if ($optThrowExceptions) {
                throw new ValidationException("ExampleDTO is not valid for DB creation: dayOfTheWeek value not set.");
            }
            return false;
        }
        // description is required
        if (empty($this->description)) {
            if ($optThrowExceptions) {
                throw new ValidationException("ExampleDTO is not valid for DB creation: description value not set.");
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
     *
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function validateForDbDelete(bool $optThrowExceptions = true) : bool {
        // ID is required
        if (empty($this->id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("ExampleDTO is not valid for DB creation: ID value is not set.");
            }
            return false;
        }
        return true;
    }
    
    // </editor-fold>
    
    
    /**
     * TODO: Function documentation
     *
     * @return string
     *
     * @author Marc-Eric Boury
     * @since  2024-03-28
     */
    public function toJson() : string {
        $array = [
            "id" => $this->getId(),
            "dayOfTheWeek" => $this->getDayOfTheWeek()->value,
            "description" => $this->description,
            "creationDate" => $this->getCreationDate()->format(HTML_DATETIME_FORMAT),
            "lastModificationDate" => $this->getLastModificationDate()?->format(HTML_DATETIME_FORMAT),
            "deletionDate" => $this->getDeletionDate()?->format(HTML_DATETIME_FORMAT),
        ];
        return json_encode($array, JSON_PRETTY_PRINT);
    }
    
}