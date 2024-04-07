<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project AuthorDTO.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-21
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\Examples\DTOs;

use DateTime;
use Exception;
use Teacher\Examples\DAOs\AuthorDAO;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-21
 */
class AuthorDTO {
    
    /**
     * The database table name for this entity type.
     * @const
     */
    public const TABLE_NAME = "authors";
    public const FIRST_NAME_MAX_LENGTH = 64;
    public const LAST_NAME_MAX_LENGTH = 64;
    
    private int $id;
    private string $firstName;
    private string $lastName;
    private ?DateTime $dateCreated = null;
    private ?DateTime $dateLastModified = null;
    private ?DateTime $dateDeleted = null;
    
    /**
     * TODO: Property documentation
     *
     * @var BookDTO[]
     */
    private array $books = [];
    
    
    public function __construct() {}
    
    /**
     * TODO: Function documentation
     *
     * @param string $firstName
     * @param string $lastName
     * @return AuthorDTO
     *
     * @author Marc-Eric Boury
     * @since  2024-04-01
     */
    public static function fromValues(string $firstName, string $lastName) : AuthorDTO {
        $instance = new AuthorDTO();
        $instance->setFirstName($firstName);
        $instance->setLastName($lastName);
        return $instance;
    }
    
    /**
     * TODO: Function documentation
     *
     * @param array $dbArray
     * @return AuthorDTO
     *
     * @author Marc-Eric Boury
     * @since  2024-04-01
     */
    public static function fromDbArray(array $dbArray) : AuthorDTO {
        self::validateDbArray($dbArray);
        $instance = new AuthorDTO();
        $instance->setId((int) $dbArray["id"]);
        $instance->setFirstName($dbArray["first_name"]);
        $instance->setLastName($dbArray["last_name"]);
        $instance->setDateCreated(DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["date_created"]));
        if (!empty($dbArray["date_last_modified"])) {
            $instance->setDateLastModified(DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["date_last_modified"]));
        }
        if (!empty($dbArray["date_deleted"])) {
            $instance->setDateDeleted(DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["date_deleted"]));
        }
        return $instance;
    }
    
    // <editor-fold defaultstate="collapsed" desc="VALIDATION METHODS">
    
    private static function validateDbArray(array $dbArray) : void {
        if (empty($dbArray["id"])) {
            throw new ValidationException("Record array does not contain an [id] field. Check column names.");
        }
        if (!is_numeric($dbArray["id"])) {
            throw new ValidationException("Record array [id] field is not numeric. Check column types.");
        }
        if (empty($dbArray["first_name"])) {
            throw new ValidationException("Record array does not contain an [first_name] field. Check column names.");
        }
        if (empty($dbArray["last_name"])) {
            throw new ValidationException("Record array does not contain an [last_name] field. Check column names.");
        }
        if (empty($dbArray["date_created"])) {
            throw new ValidationException("Record array does not contain an [date_created] field. Check column names.");
        }
        if (DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["date_created"]) === false) {
            throw new ValidationException("Failed to parse [date_created] field as DateTime. Check column types.");
        }
        if (!empty($dbArray["date_last_modified"]) &&
            (DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["date_last_modified"]) === false)
        ) {
            throw new ValidationException("Failed to parse [date_last_modified] field as DateTime. Check column types.");
        }
        if (!empty($dbArray["date_deleted"]) &&
            (DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["date_deleted"]) === false)
        ) {
            throw new ValidationException("Failed to parse [date_last_modified] field as DateTime. Check column types.");
        }
    }
    
    public function validateForDbCreation(bool $optThrowExceptions = true) : bool {
        // ID must not be set
        if (!empty($this->id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("AuthorDTO is not valid for DB creation: ID value already set.");
            }
            return false;
        }
        // firstName is required
        if (empty($this->firstName)) {
            if ($optThrowExceptions) {
                throw new ValidationException("AuthorDTO is not valid for DB creation: firstName value not set.");
            }
            return false;
        }
        // lastName is required
        if (empty($this->lastName)) {
            if ($optThrowExceptions) {
                throw new ValidationException("AuthorDTO is not valid for DB creation: lastName value not set.");
            }
            return false;
        }
        if (!is_null($this->dateCreated)) {
            if ($optThrowExceptions) {
                throw new ValidationException("AuthorDTO is not valid for DB creation: dateCreated value already set.");
            }
            return false;
        }
        if (!is_null($this->dateLastModified)) {
            if ($optThrowExceptions) {
                throw new ValidationException("AuthorDTO is not valid for DB creation: dateLastModified value already set.");
            }
            return false;
        }
        if (!is_null($this->dateDeleted)) {
            if ($optThrowExceptions) {
                throw new ValidationException("AuthorDTO is not valid for DB creation: dateDeleted value already set.");
            }
            return false;
        }
        return true;
    }
    
    public function validateForDbUpdate(bool $optThrowExceptions = true) : bool {
        // ID is required
        if (empty($this->id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("AuthorDTO is not valid for DB update: ID value is not set.");
            }
            return false;
        }
        // firstName is required
        if (empty($this->firstName)) {
            if ($optThrowExceptions) {
                throw new ValidationException("AuthorDTO is not valid for DB update: firstName value not set.");
            }
            return false;
        }
        // lastName is required
        if (empty($this->lastName)) {
            if ($optThrowExceptions) {
                throw new ValidationException("AuthorDTO is not valid for DB update: lastName value not set.");
            }
            return false;
        }
        return true;
    }
    
    public function validateForDbDelete(bool $optThrowExceptions = true) : bool {
        // ID is required
        if (empty($this->id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("AuthorDTO is not valid for DB creation: ID value is not set.");
            }
            return false;
        }
        return true;
    }
    
    // </editor-fold>
    
    
    // <editor-fold defaultstate="collapsed" desc="GETTERS AND SETTERS">
    
    /**
     * @return int
     */
    public function getId() : int {
        return $this->id;
    }
    
    /**
     * @param int $id
     */
    public function setId(int $id) : void {
        if ($id <= 0) {
            throw new ValidationException("Invalid value for AuthorDTO [id]: must be a positive integer > 0.");
        }
        $this->id = $id;
    }
    
    /**
     * @return string
     */
    public function getFirstName() : string {
        return $this->firstName;
    }
    
    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName) : void {
        if (mb_strlen($firstName) > self::FIRST_NAME_MAX_LENGTH) {
            throw new ValidationException("Invalid value for AuthorDTO [firstName]: string length is > " .
                                          self::FIRST_NAME_MAX_LENGTH . ".");
        }
        $this->firstName = $firstName;
    }
    
    /**
     * @return string
     */
    public function getLastName() : string {
        return $this->lastName;
    }
    
    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName) : void {
        if (mb_strlen($lastName) > self::LAST_NAME_MAX_LENGTH) {
            throw new ValidationException("Invalid value for AuthorDTO [lastName]: string length is > " .
                                          self::LAST_NAME_MAX_LENGTH . ".");
        }
        $this->lastName = $lastName;
    }
    
    /**
     * @return DateTime
     */
    public function getDateCreated() : DateTime {
        return $this->dateCreated;
    }
    
    /**
     * @param DateTime $dateCreated
     */
    public function setDateCreated(DateTime $dateCreated) : void {
        $this->dateCreated = $dateCreated;
    }
    
    /**
     * @return DateTime|null
     */
    public function getDateLastModified() : ?DateTime {
        return $this->dateLastModified;
    }
    
    /**
     * @param DateTime|null $dateLastModified
     */
    public function setDateLastModified(?DateTime $dateLastModified) : void {
        $this->dateLastModified = $dateLastModified;
    }
    
    /**
     * @return DateTime|null
     */
    public function getDateDeleted() : ?DateTime {
        return $this->dateDeleted;
    }
    
    /**
     * @param DateTime|null $dateDeleted
     */
    public function setDateDeleted(?DateTime $dateDeleted) : void {
        $this->dateDeleted = $dateDeleted;
    }
    
    /**
     * TODO: Function documentation
     *
     * @param bool $forceReload [default=false] If <code>true</code>, forces the reload of the book records from the database.
     * @return array
     * @throws RuntimeException
     */
    public function getBooks(bool $forceReload = false) : array {
        try {
            if (empty($this->books) || $forceReload) {
                $this->loadBooks();
            }
        } catch (Exception $excep) {
            throw new RuntimeException("Failed to load book entity records for author id# [$this->id].", $excep->getCode(), $excep);
        }
        return $this->books;
    }
    
    // </editor-fold>
    
    
    public function loadBooks() : void {
        $dao = new AuthorDAO();
        $this->books = $dao->getBooksByAuthor($this);
    }
    
    public function toArray() : array {
        $array = [
            "id" => $this->getId(),
            "firstName" => $this->getFirstName(),
            "lastName" => $this->getLastName(),
            "dateCreated" => $this->getDateCreated()?->format(HTML_DATETIME_FORMAT),
            "dateLastModified" => $this->getDateLastModified()?->format(HTML_DATETIME_FORMAT),
            "dateDeleted" => $this->getDateDeleted()?->format(HTML_DATETIME_FORMAT),
            "books" => []
        ];
        // Note: i'm not using getBooks() here in order not to trigger the loading of the books.
        // Include them in the array only if loaded previously.
        // otherwise infinite loop author loads books loads authors loads books loads authors...
        foreach ($this->books as $book) {
            $array["books"][$book->getId()] = $book->toArray();
        }
        return $array;
    }
    
    
    public function getDatabaseTableName() : string {
        return self::TABLE_NAME;
    }
}