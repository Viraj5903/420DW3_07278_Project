<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project BookService.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-04-03
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\Examples\Services;

use Teacher\Examples\DAOs\AuthorBookDAO;
use Teacher\Examples\DAOs\BookDAO;
use Teacher\Examples\DTOs\AuthorDTO;
use Teacher\Examples\DTOs\BookDTO;
use Teacher\GivenCode\Abstracts\IService;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-04-03
 */
class BookService implements IService {
    
    private BookDAO $dao;
    private AuthorBookDAO $authorBookDao;
    
    public function __construct() {
        $this->dao = new BookDAO();
        $this->authorBookDao = new AuthorBookDAO();
    }
    
    /**
     * TODO: Function documentation
     *
     * @return BookDTO[]
     *
     * @author Marc-Eric Boury
     * @since  2024-04-04
     */
    public function getAllBooks() : array {
        return $this->dao->getAll();
    }
    
    public function getById(int $id) : ?BookDTO {
        return $this->dao->getById($id);
    }
    
    public function create(string $title, string $isbn, int $publication_year, ?string $description = null) : BookDTO {
        $instance = BookDTO::fromValues($title, $isbn, $publication_year, $description);
        return $this->dao->insert($instance);
    }
    
    public function update(int $id, string $title, string $isbn, int $publication_year, ?string $description = null) : BookDTO {
        // No transaction this time, contrary to the Example stack
        $instance = $this->dao->getById($id);
        $instance->setTitle($title);
        $instance->setIsbn($isbn);
        $instance->setPublicationYear($publication_year);
        $instance->setDescription($description);
        return $this->dao->update($instance);
    }
    
    public function delete(int $id) : void {
        $this->dao->deleteById($id);
    }
    
    /**
     * TODO: Function documentation
     *
     * @param BookDTO $book
     * @return AuthorDTO[]
     *
     * @author Marc-Eric Boury
     * @since  2024-04-04
     */
    public function getBookAuthors(BookDTO $book) : array {
        return $this->getBookAuthorsByBookId($book->getId());
    }
    
    /**
     * TODO: Function documentation
     *
     * @param int $id
     * @return AuthorDTO[]
     *
     * @author Marc-Eric Boury
     * @since  2024-04-04
     */
    public function getBookAuthorsByBookId(int $id) : array {
        return $this->dao->getAuthorsByBookId($id);
    }
    
    public function deleteAllBookAuthorAssociationsForBook(BookDTO $book) : void {
        $this->deleteAllBookAuthorAssociationsForBookId($book->getId());
    }
    
    public function deleteAllBookAuthorAssociationsForBookId(int $bookId) : void {
        $this->authorBookDao->deleteAllByBookId($bookId);
    }
    
    public function associateBookWithAuthor(int $bookId, int $authorId) : void {
        $this->authorBookDao->createForAuthorAndBook($authorId, $bookId);
    }
    
}