<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project PageNavigator.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-26
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\Examples\Services;

use Teacher\GivenCode\Abstracts\IService;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-26
 */
class PageNavigator implements IService {
    
    
    public static function loginPage() : void {
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_FRAGMENTS_DIR . "Teacher/Exemples/page.login.php";
    }
    
    public static function booksManagementPage() : void {
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_FRAGMENTS_DIR . "Teacher/Exemples/page.management.books.php";
    }
    
    public static function authorsManagementPage() : void {
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_FRAGMENTS_DIR . "Teacher/Exemples/page.management.authors.php";
    }
}