<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project LoginService.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-04-06
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\Examples\Services;

use Debug;
use Exception;
use Teacher\Examples\DTOs\AuthorDTO;
use Teacher\GivenCode\Abstracts\IService;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-04-06
 */
class LoginService implements IService {
    
    private AuthorService $authorService;
    
    public function __construct() {
        $this->authorService = new AuthorService();
    }
    
    public static function isAuthorLoggedIn() : bool {
        $return_val = false;
        if (!empty($_SESSION["LOGGED_IN_AUTHOR"]) && ($_SESSION["LOGGED_IN_AUTHOR"] instanceof AuthorDTO)) {
            $return_val = true;
        }
        Debug::log(("Is logged in author check result: [" . $return_val)
                        ? "true"
                        : ("false" . "]" .
                ($return_val ? (" id# [" . $_SESSION["LOGGED_IN_AUTHOR"]->getId() . "].") : ".")));
        return $return_val;
    }
    
    public static function redirectToLogin() : void {
        header("Location: " . WEB_ROOT_DIR . "pages/login?from=" . $_SERVER["REQUEST_URI"]);
        http_response_code(303);
        exit();
    }
    
    public static function requireLoggedInAuthor() : void {
        if (!self::isAuthorLoggedIn()) {
            // not logged in, do a redirection to the login page.
            // Note that I am adding a 'from' URL parameter that will be used to send the user to the right page after login
            self::redirectToLogin();
        }
    }
    
    public function doLogout() : void {
        $_SESSION["LOGGED_IN_AUTHOR"] = null;
        Debug::debugToHtmlTable($_SESSION);
    }
    
    public function doLogin(int $authorId) : void {
        $author = $this->authorService->getAuthorById($authorId);
        if (is_null($author)) {
            throw new Exception("Author id# [$authorId] not found.", 404);
        }
        $_SESSION["LOGGED_IN_AUTHOR"] = $author;
    }
    
}