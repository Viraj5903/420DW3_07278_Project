<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project AuthorBookDAO.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-04-04
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\Examples\DAOs;

use PDO;
use Teacher\GivenCode\Services\DBConnectionService;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-04-04
 */
class AuthorBookDAO {
    public const TABLE_NAME = "author_books";
    private const CREATE_QUERY = "INSERT INTO " . self::TABLE_NAME .
    " (`author_id`, `book_id`) VALUES (:authorId, :bookId);";
    
    public function __construct() {}
    
    public function createForAuthorAndBook(int $authorId, int $bookId) : void {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":authorId", $authorId, PDO::PARAM_INT);
        $statement->bindValue(":bookId", $bookId, PDO::PARAM_INT);
        $statement->execute();
    }
    
    public function createManyForAuthor(int $authorId, array $bookIds) : void {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":authorId", $authorId, PDO::PARAM_INT);
        foreach ($bookIds as $book_id) {
            $statement->bindValue(":bookId", $book_id, PDO::PARAM_INT);
            $statement->execute();
        }
    }
    
    public function createManyForBook(int $bookId, array $authorIds) : void {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":bookId", $bookId, PDO::PARAM_INT);
        foreach ($authorIds as $author_id) {
            $statement->bindParam(":authorId", $author_id, PDO::PARAM_INT);
            $statement->execute();
        }
    }
    
    public function deleteAllByBookId(int $bookId) : void {
        $query = "DELETE FROM " . self::TABLE_NAME . " WHERE `book_id` = :bookId ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":bookId", $bookId, PDO::PARAM_INT);
        $statement->execute();
    }
    
    public function deleteAllByAuthorId(int $authorId) : void {
        $query = "DELETE FROM " . self::TABLE_NAME . " WHERE `author_id` = :authorId ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":authorId", $authorId, PDO::PARAM_INT);
        $statement->execute();
    }
    
}