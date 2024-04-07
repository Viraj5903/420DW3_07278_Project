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
use Teacher\GivenCode\Abstracts\AbstractController;
use Teacher\GivenCode\Exceptions\RequestException;

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
        // TODO: Implement post() method.
        throw new RequestException("NOT IMPLEMENTED.", 501);
    }
    
    public function put() : void {
        // TODO: Implement put() method.
        throw new RequestException("NOT IMPLEMENTED.", 501);
    }
    
    public function delete() : void {
        // TODO: Implement delete() method.
        throw new RequestException("NOT IMPLEMENTED.", 501);
    }
}