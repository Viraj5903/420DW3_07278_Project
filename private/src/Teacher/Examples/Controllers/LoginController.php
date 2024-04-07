<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project LoginController.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-04-06
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\Examples\Controllers;

use Exception;
use Teacher\Examples\Services\LoginService;
use Teacher\GivenCode\Abstracts\AbstractController;
use Teacher\GivenCode\Exceptions\RequestException;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-04-06
 */
class LoginController extends AbstractController {
    
    private LoginService $loginService;
    
    public function __construct() {
        parent::__construct();
        $this->loginService = new LoginService();
    }
    
    public function get() : void {
        // Voluntary exception throw: no GET operation supported for login system
        throw new RequestException("NOT IMPLEMENTED.", 501);
    }
    
    public function post() : void {
        /*
         * NOTE: I use the POST method to trigger the login
         */
        
        try {
            if (empty($_REQUEST["authorId"])) {
                throw new RequestException("Missing required parameter [authorId] in request.", 400, [], 400);
            }
            if (!is_numeric($_REQUEST["authorId"])) {
                throw new RequestException("Invalid parameter [authorId] in request: non-numeric value [" .
                                           $_REQUEST["authorId"] . "] received.",
                                           400, [], 400);
            }
            
            $int_id = (int) $_REQUEST["authorId"];
            $this->loginService->doLogin($int_id);
            
            // if the user came to the login page by being redirected from another page that required to be logged in
            // redirect to that originally requested page after login.
            $response = [
                "navigateTo" => WEB_ROOT_DIR
            ];
            if (!empty($_REQUEST["from"])) {
                $response["navigateTo"] = $_REQUEST["from"];
            }
            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode($response);
            exit();
            
        } catch (Exception $excep) {
            throw new Exception("Failure to log author in.", $excep->getCode(), $excep);
        }
    }
    
    public function put() : void {
        // Voluntary exception throw: no PUT operation supported for login system
        throw new RequestException("NOT IMPLEMENTED.", 501);
    }
    
    public function delete() : void {
        /*
         * NOTE: I use the DELETE method to trigger the logout
         */
        
        $this->loginService->doLogout();$response = [
            "navigateTo" => WEB_ROOT_DIR . "pages/login"
        ];
        if (!empty($_REQUEST["from"])) {
            $response["navigateTo"] = $_REQUEST["from"];
        }
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($response);
        exit();
    }
}