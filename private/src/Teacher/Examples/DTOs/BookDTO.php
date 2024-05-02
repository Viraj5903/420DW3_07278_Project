<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project BookDTO.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-21
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\Examples\DTOs;

use DateTime;
use Exception;
use Teacher\Examples\DAOs\BookDAO;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-21
 */
class BookDTO {
    
    /**
     * The database table name for this entity type.
     * @const
     */
    public const TABLE_NAME = "books";
    public const TITLE_MAX_LENGTH = 256;
    public const DESCRIPTION_MAX_LENGTH = 1024;
    public const ISBN_MAX_LENGTH = 32;
    public const PUBLICATION_YEAR_LOWER_BOUND = -8000;
    
    private int $id;
    private string $title;
    private ?string $description;
    private string $isbn;
    private int $publicationYear;
    private ?DateTime $dateCreated = null;
    private ?DateTime $dateLastModified = null;
    private ?DateTime $dateDeleted = null;
    
    /**
     * TODO: Property documentation
     *
     * @var AuthorDTO[]
     */
    private array $authors = [];
    
    public function __construct() {}
    
    /**
     * TODO: Function documentation
     *
     * @param string      $title
     * @param string      $isbn
     * @param int         $publicationYear
     * @param string|null $description
     * @return BookDTO
     * @throws ValidationException
     *
     * @author Marc-Eric Boury
     * @since  2024-04-04
     */
    public static function fromValues(string $title, string $isbn, int $publicationYear, ?string $description = null) : BookDTO {
        $instance = new BookDTO();
        $instance->setTitle($title);
        $instance->setIsbn($isbn);
        $instance->setPublicationYear($publicationYear);
        $instance->setDescription($description);
        return $instance;
    }
    
    /**
     * TODO: Function documentation
     *
     * @param array $dbArray
     * @return BookDTO
     * @throws ValidationException
     *
     * @author Marc-Eric Boury
     * @since  2024-04-04
     */
    public static function fromDbArray(array $dbArray) : BookDTO {
        self::validateDbArray($dbArray);
        $instance = new BookDTO();
        $instance->setId((int) $dbArray["id"]);
        $instance->setTitle($dbArray["title"]);
        $instance->setDescription($dbArray["description"]);
        $instance->setIsbn($dbArray["isbn"]);
        $instance->setPublicationYear((int) $dbArray["publication_year"]);
        $instance->setDateCreated(DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["date_created"]));
        $dateLast_modified = null;
        if (!empty($dbArray["date_last_modified"])) {
            $dateLast_modified = DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["date_last_modified"]);
        }
        $instance->setDateLastModified($dateLast_modified);
        $date_deleted = null;
        if (!empty($dbArray["date_deleted"])) {
            $date_deleted = DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["date_deleted"]);
        }
        $instance->setDateDeleted($date_deleted);
        return $instance;
    }
    
    
    public function getDatabaseTableName() : string {
        return self::TABLE_NAME;
    }
    
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
        if ($id < 1) {
            throw new ValidationException("[id] value must be a positive integer greater than 0.");
        }
        $this->id = $id;
    }
    
    /**
     * @return string
     */
    public function getTitle() : string {
        return $this->title;
    }
    
    /**
     * @param string $title
     */
    public function setTitle(string $title) : void {
        if (mb_strlen($title) > self::TITLE_MAX_LENGTH) {
            throw new ValidationException("[title] value must be a string no longer than " . self::TITLE_MAX_LENGTH .
                                          " characters; found length: [" . mb_strlen($title) . "].");
        }
        $this->title = $title;
    }
    
    /**
     * @return string|null
     */
    public function getDescription() : ?string {
        return $this->description;
    }
    
    /**
     * @param string|null $description
     */
    public function setDescription(?string $description) : void {
        if (is_string($description) && (mb_strlen($description) > self::TITLE_MAX_LENGTH)) {
            throw new ValidationException("[description] value must be a string no longer than " .
                                          self::DESCRIPTION_MAX_LENGTH . " characters; found length: [" .
                                          mb_strlen($description) . "].");
        }
        $this->description = $description;
    }
    
    /**
     * @return string
     */
    public function getIsbn() : string {
        return $this->isbn;
    }
    
    /**
     * @param string $isbn
     */
    public function setIsbn(string $isbn) : void {
        if (mb_strlen($isbn) > self::ISBN_MAX_LENGTH) {
            throw new ValidationException("[isbn] value must be a string no longer than " . self::ISBN_MAX_LENGTH .
                                          " characters; found length: [" . mb_strlen($isbn) . "].");
        }
        $this->isbn = $isbn;
    }
    
    /**
     * @return int
     */
    public function getPublicationYear() : int {
        return $this->publicationYear;
    }
    
    /**
     * @param int $publicationYear
     */
    public function setPublicationYear(int $publicationYear) : void {
        $current_year = (int) (new DateTime())->format("Y");
        if (($publicationYear < self::PUBLICATION_YEAR_LOWER_BOUND) || ($publicationYear > $current_year)) {
            throw new ValidationException("[publicationYear] value must be an integer between [" .
                                          self::PUBLICATION_YEAR_LOWER_BOUND . "] and [" . $current_year .
                                          "] inclusively.");
        }
        $this->publicationYear = $publicationYear;
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
     * TODO: function documentation
     *
     * @param bool $forceReload
     * @return array
     * @throws RuntimeException
     */
    public function getAuthors(bool $forceReload = false) : array {
        try {
            if (empty($this->books) || $forceReload) {
                $this->loadAuthors();
            }
        } catch (Exception $excep) {
            throw new RuntimeException("Failed to load author entity records for book id# [$this->id].", $excep->getCode(), $excep);
        }
        return $this->authors;
    }
    
    // </editor-fold>
    
    
    public function loadAuthors() : void {
        $dao = new BookDAO();
        $this->authors = $dao->getAuthorsByBook($this);
    }
    
    public function toArray() : array {
        $array = [
            "id" => $this->getId(),
            "title" => $this->getTitle(),
            "description" => $this->getDescription(),
            "isbn" => $this->getIsbn(),
            "publicationYear" => $this->getPublicationYear(),
            "dateCreated" => $this->getDateCreated()?->format(HTML_DATETIME_FORMAT),
            "dateLastModified" => $this->getDateLastModified()?->format(HTML_DATETIME_FORMAT),
            "dateDeleted" => $this->getDateDeleted()?->format(HTML_DATETIME_FORMAT),
            "authors" => []
        ];
        // Note: i'm not using getAuthors() here in order not to trigger the loading of the authors.
        // Include them in the array only if loaded previously.
        // otherwise infinite loop book loads authors loads books loads authors loads books...
        foreach ($this->authors as $author) {
            $array["authors"][$author->getId()] = $author->toArray();
        }
        return $array;
    }
    
    
    // <editor-fold defaultstate="collapsed" desc="VALIDATION METHODS">
    
    public function validateForDbCreation() : void {
        // ID must not be set
        if (!empty($this->id)) {
            throw new ValidationException("BookDTO is not valid for DB creation: ID value already set.");
        }
        // title is required
        if (empty($this->title)) {
            throw new ValidationException("BookDTO is not valid for DB creation: title value not set.");
        }
        // isbn is required
        if (empty($this->isbn)) {
            throw new ValidationException("BookDTO is not valid for DB creation: isbn value not set.");
        }
        // publicationYear is required
        if (empty($this->publicationYear)) {
            throw new ValidationException("BookDTO is not valid for DB creation: publicationYear value not set.");
        }
        if (!is_null($this->dateCreated)) {
            throw new ValidationException("BookDTO is not valid for DB creation: dateCreated value already set.");
        }
        if (!is_null($this->dateLastModified)) {
            throw new ValidationException("BookDTO is not valid for DB creation: dateLastModified value already set.");
        }
        if (!is_null($this->dateDeleted)) {
            throw new ValidationException("BookDTO is not valid for DB creation: dateDeleted value already set.");
        }
    }
    
    public function validateForDbUpdate() : void {
        // ID must be set
        if (empty($this->id)) {
            throw new ValidationException("BookDTO is not valid for DB update: ID value not set.");
        }
        // title is required
        if (empty($this->title)) {
            throw new ValidationException("BookDTO is not valid for DB update: title value not set.");
        }
        // isbn is required
        if (empty($this->isbn)) {
            throw new ValidationException("BookDTO is not valid for DB update: isbn value not set.");
        }
        // publicationYear is required
        if (empty($this->publicationYear)) {
            throw new ValidationException("BookDTO is not valid for DB update: publicationYear value not set.");
        }
    }
    
    public function validateForDbDelete() : void {
        // ID must be set
        if (empty($this->id)) {
            throw new ValidationException("BookDTO is not valid for DB update: ID value not set.");
        }
    }
    
    
    /**
     * TODO: Function documentation
     *
     * @param array $dbArray
     * @return void
     * @throws ValidationException
     *
     * @author Marc-Eric Boury
     * @since  2024-04-02
     */
    private static function validateDbArray(array $dbArray) : void {
        if (empty($dbArray["id"])) {
            throw new ValidationException("Database array for [" . self::class .
                                          "] does not contain an [id] key. Check your column names.",
                                          500);
        }
        if (empty($dbArray["title"])) {
            throw new ValidationException("Database array for [" . self::class .
                                          "] does not contain an [title] key. Check your column names.",
                                          500);
        }
        if (empty($dbArray["isbn"])) {
            throw new ValidationException("Database array for [" . self::class .
                                          "] does not contain an [isbn] key. Check your column names.",
                                          500);
        }
        if (empty($dbArray["publication_year"])) {
            throw new ValidationException("Database array for [" . self::class .
                                          "] does not contain an [publication_year] key. Check your column names.",
                                          500);
        }
        if (!is_numeric($dbArray["publication_year"])) {
            throw new ValidationException("Database array for [" . self::class .
                                          "] [publication_year] entry value is not numeric. Check your column types.",
                                          500);
        }
        if (empty($dbArray["date_created"])) {
            throw new ValidationException("Database array for [" . self::class .
                                          "] does not contain an [date_created] key. Check your column names.",
                                          500);
        }
        if (DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["date_created"]) === false) {
            throw new ValidationException("Database array for [" . self::class .
                                          "] [date_created] entry value could not be parsed to a valid DateTime. Check your column types.",
                                          500);
        }
        if (!empty($dbArray["date_last_modified"])
            && (DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["date_last_modified"]) === false)
        ) {
            throw new ValidationException("Database array for [" . self::class .
                                          "] [date_last_modified] entry value could not be parsed to a valid DateTime. Check your column types.",
                                          500);
        }
        if (!empty($dbArray["date_deleted"])
            && (DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["date_deleted"]) === false)
        ) {
            throw new ValidationException("Database array for [" . self::class .
                                          "] [date_deleted] entry value could not be parsed to a valid DateTime. Check your column types.",
                                          500);
        }
    }
    
    
    // </editor-fold>
}