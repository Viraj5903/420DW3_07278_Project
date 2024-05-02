<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project AuthorController.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-04-03
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\Examples\Controllers;

use Teacher\Examples\Services\AuthorService;
use Teacher\Examples\Services\LoginService;
use Teacher\GivenCode\Abstracts\AbstractController;
use Teacher\GivenCode\Exceptions\RequestException;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-04-03
 */
class AuthorController extends AbstractController {
    
    private AuthorService $authorService;
    
    public function __construct() {
        parent::__construct();
        $this->authorService = new AuthorService();
    }
    
    public function get() : void {
        
        // Login required to use this API functionality
        if (!LoginService::isAuthorLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        if (empty($_REQUEST["authorId"])) {
            throw new RequestException("Bad request: required parameter [authorId] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["authorId"])) {
            throw new RequestException("Bad request: parameter [authorId] value [" . $_REQUEST["authorId"] .
                                       "] is not numeric.", 400);
        }
        $int_id = (int) $_REQUEST["authorId"];
        $instance = $this->authorService->getAuthorById($int_id);
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($instance->toArray());
    }
    
    public function post() : void {
        
        // Login required to use this API functionality
        if (!LoginService::isAuthorLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        if (empty($_REQUEST["firstName"])) {
            throw new RequestException("Bad request: required parameter [firstName] not found in the request.", 400);
        }
        if (empty($_REQUEST["lastName"])) {
            throw new RequestException("Bad request: required parameter [lastName] not found in the request.", 400);
        }
        
        // NOTE: no need for validation of the string lengths here, as that is done by the setter methods of the
        // ExampleDTO class used when creating an ExampleDTO instance in the creation method of ExampleService.
        
        $instance = $this->authorService->createAuthor($_REQUEST["firstName"], $_REQUEST["lastName"]);
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($instance->toArray());
    }
    
    public function put() : void {
        
        // Login required to use this API functionality
        if (!LoginService::isAuthorLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        $request_contents = file_get_contents("php://input");
        parse_str($request_contents, $_REQUEST);
        
        if (empty($_REQUEST["id"])) {
            throw new RequestException("Bad request: required parameter [id] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["id"])) {
            throw new RequestException("Bad request: ivalid parameter [id] value: non-numeric value found [" .
                                       $_REQUEST["id"] . "].", 400);
        }
        if (empty($_REQUEST["firstName"])) {
            throw new RequestException("Bad request: required parameter [firstName] not found in the request.", 400);
        }
        if (empty($_REQUEST["lastName"])) {
            throw new RequestException("Bad request: required parameter [lastName] not found in the request.", 400);
        }
        
        // NOTE: no need for validation of the string lengths here, as that is done by the setter methods of the
        // ExampleDTO class used when creating an ExampleDTO instance in the creation method of ExampleService.
        
        $int_id = (int) $_REQUEST["id"];
        
        $instance = $this->authorService->updateAuthor($int_id, $_REQUEST["firstName"], $_REQUEST["lastName"]);
        $instance->loadBooks();
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($instance->toArray());
    }
    
    public function delete() : void {
        
        // Login required to use this API functionality
        if (!LoginService::isAuthorLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        $request_contents = file_get_contents("php://input");
        parse_str($request_contents, $_REQUEST);
        
        if (empty($_REQUEST["id"])) {
            throw new RequestException("Bad request: required parameter [id] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["id"])) {
            throw new RequestException("Bad request: parameter [id] value [" . $_REQUEST["id"] .
                                       "] is not numeric.", 400);
        }
        $int_id = (int) $_REQUEST["id"];
        $this->authorService->deleteAuthorById($int_id);
        header("Content-Type: application/json;charset=UTF-8");
        http_response_code(204);
    }
}