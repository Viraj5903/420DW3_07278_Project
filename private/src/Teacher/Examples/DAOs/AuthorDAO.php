<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project AuthorDAO.php
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
class AuthorDAO {
    
    /**
     * TODO: Function documentation
     *
     * @return AuthorDTO[]
     *
     * @throws RuntimeException
     * @author Marc-Eric Boury
     * @since  2024-04-01
     */
    public function getAll() : array {
        $query = "SELECT * FROM " . AuthorDTO::TABLE_NAME . ";";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->execute();
        $result_set = $statement->fetchAll(PDO::FETCH_ASSOC);
        $authors = [];
        
        foreach ($result_set as $result) {
            $authors[] = AuthorDTO::fromDbArray($result);
        }
        return $authors;
        
    }
    
    /**
     * TODO: Function documentation
     *
     * @param int $id
     * @return AuthorDTO|null
     *
     * @author Marc-Eric Boury
     * @since  2024-04-01
     */
    public function getById(int $id) : ?AuthorDTO {
        $query = "SELECT * FROM " . AuthorDTO::TABLE_NAME . " WHERE id = :id ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();
        $author_array = $statement->fetch(PDO::FETCH_ASSOC);
        return AuthorDTO::fromDbArray($author_array);
    }
    
    /**
     * TODO: Function documentation
     *
     * @param AuthorDTO $author
     * @return AuthorDTO
     *
     * @author Marc-Eric Boury
     * @since  2024-04-01
     */
    public function insert(AuthorDTO $author) : AuthorDTO {
        $author->validateForDbCreation();
        $query =
            "INSERT INTO " . AuthorDTO::TABLE_NAME . " (`first_name`, `last_name`) VALUES (:firstName, :lastName);";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":firstName", $author->getFirstName(), PDO::PARAM_STR);
        $statement->bindValue(":lastName", $author->getLastName(), PDO::PARAM_STR);
        $statement->execute();
        $new_id = (int) $connection->lastInsertId();
        return $this->getById($new_id);
    }
    
    /**
     * TODO: Function documentation
     *
     * @param AuthorDTO $author
     * @return AuthorDTO
     *
     * @author Marc-Eric Boury
     * @since  2024-04-01
     */
    public function update(AuthorDTO $author) : AuthorDTO {
        $author->validateForDbUpdate();
        $query =
            "UPDATE " . AuthorDTO::TABLE_NAME .
            " SET `first_name` = :firstName, `last_name` = :lastName WHERE `id` = :id ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":firstName", $author->getFirstName(), PDO::PARAM_STR);
        $statement->bindValue(":lastName", $author->getLastName(), PDO::PARAM_STR);
        $statement->bindValue(":id", $author->getId(), PDO::PARAM_INT);
        $statement->execute();
        return $this->getById($author->getId());
    }
    
    /**
     * TODO: Function documentation
     *
     * @param AuthorDTO $author
     * @return void
     *
     * @author Marc-Eric Boury
     * @since  2024-04-01
     */
    public function delete(AuthorDTO $author) : void {
        $author->validateForDbDelete();
        $query =
            "DELETE FROM " . AuthorDTO::TABLE_NAME . " WHERE `id` = :id ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":id", $author->getId(), PDO::PARAM_INT);
        $statement->execute();
    }
    
    /**
     * TODO: Function documentation
     *
     * @param AuthorDTO $author
     * @return BookDTO[]
     * @throws ValidationException
     * @throws RuntimeException
     *
     * @author Marc-Eric Boury
     * @since  2024-04-01
     */
    public function getBooksByAuthor(AuthorDTO $author) : array {
        if (empty($author->getId())) {
            throw new ValidationException("Cannot get the book records for an author with no set [id] property value.");
        }
        return $this->getBooksByAuthorId($author->getId());
        
    }
    
    /**
     * TODO: Function documentation
     *
     * @param int $id
     * @return BookDTO[]
     * @throws ValidationException
     * @throws RuntimeException
     *
     * @author Marc-Eric Boury
     * @since  2024-04-01
     */
    public function getBooksByAuthorId(int $id) : array {
        $query = "SELECT b.* FROM " . AuthorDTO::TABLE_NAME . " a JOIN " . AuthorBookDAO::TABLE_NAME .
            " ab ON a.id = ab.author_id JOIN " . BookDTO::TABLE_NAME . " b ON ab.book_id = b.id WHERE a.id = :authorId ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":authorId", $id, PDO::PARAM_INT);
        $statement->execute();
        
        $result_set = $statement->fetchAll(PDO::FETCH_ASSOC);
        $books_array = [];
        foreach ($result_set as $book_record_array) {
            $books_array[] = BookDTO::fromDbArray($book_record_array);
        }
        return $books_array;
        
    }
}