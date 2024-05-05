<?php
declare(strict_types=1);

namespace Viraj\Project\Controllers;

/**
 * 420DW3_07278_Project PageNavigator.php
 *
 * @author Viraj Patel
 * @since  2024-04-11
 */
class PageNavigator {
    
    /**
     * Function to navigate to login page.
     *
     * @return void
     */
    public static function loginPage() : void {
        // echo PRJ_PAGES_DIR . "Viraj/page.login.php";
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_PAGES_DIR . "Viraj" . DIRECTORY_SEPARATOR . "page.login.php";
    }
    
    /**
     * Function to navigate to users page.
     *
     * @return void
     */
    public static function usersManagementPage() : void {
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_PAGES_DIR . "Viraj" . DIRECTORY_SEPARATOR . "page.users.php";
    }
    
    /**
     * Function to navigate to permission page.
     *
     * @return void
     */
    public static function permissionsManagementPage() : void {
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_PAGES_DIR . "Viraj" . DIRECTORY_SEPARATOR . "page.permissions.php";
    }
    
    /**
     * Function to navigate to user groups page.
     *
     * @return void
     */
    public static function userGroupsManagementPage() : void {
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_PAGES_DIR . "Viraj" . DIRECTORY_SEPARATOR . "page.usergroups.php";
    }
}