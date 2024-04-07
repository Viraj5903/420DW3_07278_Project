<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project AuthorService.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-04-03
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\Examples\Services;

use Exception;
use Teacher\Examples\DAOs\AuthorDAO;
use Teacher\Examples\DTOs\AuthorDTO;
use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Services\DBConnectionService;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-04-03
 */
class AuthorService implements IService {
    
    private AuthorDAO $dao;
    
    public function __construct() {
        $this->dao = new AuthorDAO();
    }
    
    /**
     * TODO: Function documentation
     *
     * @return AuthorDTO[]
     * @throws RuntimeException
     *
     * @author Marc-Eric Boury
     * @since  2024-04-06
     */
    public function getAllAuthors() : array {
        return $this->dao->getAll();
    }
    
    public function getAuthorById(int $id) : ?AuthorDTO {
        $author = $this->dao->getById($id);
        $author?->loadBooks();
        return $author;
    }
    
    public function createAuthor(string $firstName, string $lastName) : AuthorDTO {
        try {
            $author = AuthorDTO::fromValues($firstName, $lastName);
            return $this->dao->insert($author);
            
        } catch (Exception $excep) {
            throw new RuntimeException("Failure to create author [$firstName, $lastName].", $excep->getCode(), $excep);
        }
    }
    
    public function updateAuthor(int $id, string $firstName, string $lastName) : AuthorDTO {
        try {
            $connection = DBConnectionService::getConnection();
            $connection->beginTransaction();
            
            try {
                $author = $this->dao->getById($id);
                if (is_null($author)) {
                    throw new Exception("Author id# [$id] not found in the database.");
                }
                $author->setFirstName($firstName);
                $author->setLastName($lastName);
                $result = $this->dao->update($author);
                $connection->commit();
                return $result;
                
            } catch (Exception $inner_excep) {
                $connection->rollBack();
                throw $inner_excep;
            }
            
        } catch (Exception $excep) {
            throw new RuntimeException("Failure to update author id# [$id].", $excep->getCode(), $excep);
        }
    }
    
    public function deleteAuthorById(int $id) : void {
        try {
            
            $connection = DBConnectionService::getConnection();
            $connection->beginTransaction();
            
            try {
                $author = $this->dao->getById($id);
                if (is_null($author)) {
                    throw new Exception("Author id# [$id] not found in the database.");
                }
                $this->dao->delete($author);
                $connection->commit();
                
            } catch (Exception $inner_excep) {
                $connection->rollBack();
                throw $inner_excep;
            }
            
        } catch (Exception $excep) {
            throw new RuntimeException("Failure to delete author id# [$id].", $excep->getCode(), $excep);
        }
    }
    
    public function getAuthorBooks(AuthorDTO $author) : array {
        return $this->getAuthorBooksByAuthorId($author->getId());
    }
    
    public function getAuthorBooksByAuthorId(int $id) : array {
        return $this->dao->getBooksByAuthorId($id);
    }
    
}