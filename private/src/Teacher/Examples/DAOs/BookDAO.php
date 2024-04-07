<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project BookDAO.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-04-01
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\Examples\DAOs;

use PDO;
use Teacher\Examples\DTOs\AuthorDTO;
use Teacher\Examples\DTOs\BookDTO;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Teacher\GivenCode\Services\DBConnectionService;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-04-01
 */
class BookDAO {
    
    public function __construct() {}
    
    /**
     * TODO: Function documentation
     *
     * @return BookDTO[]
     * @throws RuntimeException
     * @throws ValidationException
     *
     * @author Marc-Eric Boury
     * @since  2024-04-06
     */
    public function getAll() : array {
        $query = "SELECT * FROM `" . BookDTO::TABLE_NAME . "`;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->execute();
        $records_array = $statement->fetchAll(PDO::FETCH_ASSOC);
        $books = [];
        foreach ($records_array as $record) {
            $books[] = BookDTO::fromDbArray($record);
        }
        return $books;
    }
    
    public function getById(int $id) : ?BookDTO {
        $query = "SELECT * FROM `" . BookDTO::TABLE_NAME . "` WHERE `id` = :id ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();
        $record_array = $statement->fetch(PDO::FETCH_ASSOC);
        return BookDTO::fromDbArray($record_array);
    }
    
    public function insert(BookDTO $book) : BookDTO {
        $book->validateForDbCreation();
        $query =
            "INSERT INTO `" . BookDTO::TABLE_NAME .
            "` (`title`, `description`, `isbn`, `publication_year`) VALUES (:title, :description, :isbn, :year);";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":title", $book->getTitle(), PDO::PARAM_STR);
        if (!is_null($book->getDescription())) {
            $statement->bindValue(":description", $book->getDescription(), PDO::PARAM_STR);
        } else {
            $statement->bindValue(":description", $book->getDescription(), PDO::PARAM_NULL);
        }
        $statement->bindValue(":isbn", $book->getIsbn(), PDO::PARAM_STR);
        $statement->bindValue(":year", $book->getPublicationYear(), PDO::PARAM_INT);
        $statement->execute();
        $new_id = (int) $connection->lastInsertId();
        return $this->getById($new_id);
    }
    
    public function update(BookDTO $book) : BookDTO {
        $book->validateForDbUpdate();
        $query =
            "UPDATE `" . BookDTO::TABLE_NAME .
            "` SET `title` = :title, `description` = :description, `isbn` = :isbn, `publication_year` = :year WHERE `id` = :id ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":title", $book->getTitle(), PDO::PARAM_STR);
        if (!is_null($book->getDescription())) {
            $statement->bindValue(":description", $book->getDescription(), PDO::PARAM_STR);
        } else {
            $statement->bindValue(":description", $book->getDescription(), PDO::PARAM_NULL);
        }
        $statement->bindValue(":isbn", $book->getIsbn(), PDO::PARAM_STR);
        $statement->bindValue(":year", $book->getPublicationYear(), PDO::PARAM_INT);
        $statement->bindValue(":id", $book->getId(), PDO::PARAM_INT);
        $statement->execute();
        return $this->getById($book->getId());
    }
    
    public function delete(BookDTO $book) : void {
        $book->validateForDbDelete();
        $this->deleteById($book->getId());
    }
    
    public function deleteById(int $bookId) : void {
        $query =
            "DELETE FROM `" . BookDTO::TABLE_NAME .
            "` WHERE `id` = :id ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":id", $bookId, PDO::PARAM_INT);
        $statement->execute();
    }
    
    /**
     * TODO: Function documentation
     *
     * @param BookDTO $book
     * @return AuthorDTO[]
     *
     * @throws RuntimeException
     * @author Marc-Eric Boury
     * @since  2024-04-01
     */
    public function getAuthorsByBook(BookDTO $book) : array {
        return $this->getAuthorsByBookId($book->getId());
    }
    
    /**
     * TODO: Function documentation
     *
     * @param int $id
     * @return AuthorDTO[]
     *
     * @throws RuntimeException
     * @author Marc-Eric Boury
     * @since  2024-04-01
     */
    public function getAuthorsByBookId(int $id) : array {
        $query = "SELECT a.* FROM " . AuthorDTO::TABLE_NAME . " a JOIN " . AuthorBookDAO::TABLE_NAME .
            " ab ON a.id = ab.author_id JOIN " . BookDTO::TABLE_NAME .
            " b ON ab.book_id = b.id WHERE b.id = :bookId ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":bookId", $id, PDO::PARAM_INT);
        $statement->execute();
        
        $result_set = $statement->fetchAll(PDO::FETCH_ASSOC);
        $author_array = [];
        foreach ($result_set as $author_record) {
            $author_array[] = AuthorDTO::fromDbArray($author_record);
        }
        return $author_array;
    }
    
}