<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project BookController.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-04-03
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\Examples\Controllers;

use Teacher\Examples\Services\BookService;
use Teacher\Examples\Services\LoginService;
use Teacher\GivenCode\Abstracts\AbstractController;
use Teacher\GivenCode\Exceptions\RequestException;
use Teacher\GivenCode\Services\DBConnectionService;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-04-03
 */
class BookController extends AbstractController {
    
    private BookService $bookService;
    
    public function __construct() {
        parent::__construct();
        $this->bookService = new BookService();
    }
    
    public function get() : void {
        // Login required to use this API functionality
        if (!LoginService::isAuthorLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        if (empty($_REQUEST["bookId"])) {
            throw new RequestException("Bad request: required parameter [book] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["bookId"])) {
            throw new RequestException("Bad request: parameter [bookId] value [" . $_REQUEST["bookId"] .
                                       "] is not numeric.", 400);
        }
        $int_id = (int) $_REQUEST["bookId"];
        $instance = $this->bookService->getById($int_id);
        $instance->loadAuthors();
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($instance->toArray());
    }
    
    public function post() : void {
        // Login required to use this API functionality
        if (!LoginService::isAuthorLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        
        // I create the new book first then deal with the book-author associations.
        // I have to do this in that order in the create operation because the book does not already exists
        // in the database so the foreign key checks would fail if i tried creating associations first.
        
        if (empty($_REQUEST["title"])) {
            throw new RequestException("Bad request: required parameter [title] not found in the request.", 400);
        }
        if (empty($_REQUEST["isbn"])) {
            throw new RequestException("Bad request: required parameter [isbn] not found in the request.", 400);
        }
        if (empty($_REQUEST["publicationYear"])) {
            throw new RequestException("Bad request: required parameter [publicationYear] not found in the request.",
                                       400);
        }
        if (!is_numeric($_REQUEST["publicationYear"])) {
            throw new RequestException("Bad request: ivalid parameter [publicationYear] value: non-numeric value found [" .
                                       $_REQUEST["publicationYear"] . "].", 400);
        }
        
        $int_pub_year = (int) $_REQUEST["publicationYear"];
        
        // create a transaction as i will be making many operations in the datatbase
        $connection = DBConnectionService::getConnection();
        $connection->beginTransaction();
        
        try {
            
            // create the book first
            $instance = $this->bookService->create($_REQUEST["title"], $_REQUEST["isbn"], $int_pub_year, $_REQUEST["description"]);
            
            // then create the book-author associations
            if (!empty($_REQUEST["authors"]) || is_array($_REQUEST["authors"])) {
                
                // create the selected associations
                foreach ($_REQUEST["authors"] as $author_id => $is_checked) {
                    // only if checkbox value was checked.
                    // NOTE: unchecked checkbox pass the value 'false' as a string ao they still exist in the request
                    // and make the following == "true" check necessary.
                    if (strtolower($is_checked) == "true") {
                        $int_author_id = (int) $author_id;
                        $this->bookService->associateBookWithAuthor($instance->getId(), $int_author_id);
                    }
                }
            }
            
            // load the created associations
            $instance->loadAuthors();
            // commit all DB operations
            $connection->commit();
            
            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode($instance->toArray());
            
        } catch (\Exception $excep) {
            $connection->rollBack();
            throw $excep;
        }
    }
    
    public function put() : void {
        
        // Login required to use this API functionality
        if (!LoginService::isAuthorLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        $raw_request_string = file_get_contents("php://input");
        parse_str($raw_request_string, $_REQUEST);
        
        
        if (empty($_REQUEST["id"])) {
            throw new RequestException("Bad request: required parameter [id] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["id"])) {
            throw new RequestException("Bad request: ivalid parameter [id] value: non-numeric value found [" .
                                       $_REQUEST["id"] . "].", 400);
        }
        if (empty($_REQUEST["title"])) {
            throw new RequestException("Bad request: required parameter [title] not found in the request.", 400);
        }
        if (empty($_REQUEST["isbn"])) {
            throw new RequestException("Bad request: required parameter [isbn] not found in the request.", 400);
        }
        if (empty($_REQUEST["publicationYear"])) {
            throw new RequestException("Bad request: required parameter [publicationYear] not found in the request.",
                                       400);
        }
        if (!is_numeric($_REQUEST["publicationYear"])) {
            throw new RequestException("Bad request: ivalid parameter [publicationYear] value: non-numeric value found [" .
                                       $_REQUEST["publicationYear"] . "].", 400);
        }
        
        $int_book_id = (int) $_REQUEST["id"];
        $int_pub_year = (int) $_REQUEST["publicationYear"];
        
        $connection = DBConnectionService::getConnection();
        $connection->beginTransaction();
        
        try {
            
            // I handle dealing with the book-author associations first then do the book entity update.
            // I can do this in the update because the book already exists so the foreign key checks will not fail.
            
            // My approach is simply to delete all existing associations between the updating book and any authors
            // and then to re-create only those set from the checkbox inputs values from the request
            
            if (!empty($_REQUEST["authors"]) || is_array($_REQUEST["authors"])) {
                // delete all previous author associations for the book
                $this->bookService->deleteAllBookAuthorAssociationsForBookId($int_book_id);
                
                // re-create the selected associations
                foreach ($_REQUEST["authors"] as $author_id => $is_checked) {
                    // only if checkbox value was checked.
                    // NOTE: unchecked checkbox pass the value 'false' as a string ao they still exist in the request
                    // and make the following == "true" check necessary.
                    if (strtolower($is_checked) == "true") {
                        $int_author_id = (int) $author_id;
                        $this->bookService->associateBookWithAuthor($int_book_id, $int_author_id);
                    }
                }
            }
            
            // then update the main object
            $instance = $this->bookService->update($int_book_id, $_REQUEST["title"], $_REQUEST["isbn"], $int_pub_year, $_REQUEST["description"]);
            $instance->loadAuthors();
            $connection->commit();
            
            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode($instance->toArray());
            
        } catch (\Exception $excep) {
            $connection->rollBack();
            throw $excep;
        }
        
        
    }
    
    public function delete() : void {
        
        // Login required to use this API functionality
        if (!LoginService::isAuthorLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        $raw_request_string = file_get_contents("php://input");
        parse_str($raw_request_string, $_REQUEST);
        
        
        if (empty($_REQUEST["id"])) {
            throw new RequestException("Bad request: required parameter [id] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["id"])) {
            throw new RequestException("Bad request: ivalid parameter [id] value: non-numeric value found [" .
                                       $_REQUEST["id"] . "].", 400);
        }
        
        $int_book_id = (int) $_REQUEST["id"];
        
        $connection = DBConnectionService::getConnection();
        $connection->beginTransaction();
        
        try {
            // I delete the book-author associations first then delete the book itself.
            // I have to do this in that order in the delete operation because the foreign key checks might block me
            // from deleting a book that still has existing associations (ON DELETE RESTRICT foreign key option).
            
            // delete all author associations for the book
            $this->bookService->deleteAllBookAuthorAssociationsForBookId($int_book_id);
            
            // delete the book itself
            $this->bookService->delete($int_book_id);
            
            // commit transaction operations
            $connection->commit();
            
            header("Content-Type: application/json;charset=UTF-8");
            // 204 NO CONTENT response code
            http_response_code(204);
            
        } catch (\Exception $excep) {
            $connection->rollBack();
            throw $excep;
        }
    }
}