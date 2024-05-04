<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project LoginController.php
 *
 * @author Viraj Patel
 * @since 2024-04-011
 */

namespace Viraj\Project\Controllers;

use Exception;
use JetBrains\PhpStorm\NoReturn;
use Teacher\GivenCode\Abstracts\AbstractController;
use Viraj\Project\Exceptions\RequestException;
use Viraj\Project\Services\LoginService;

/**
 *
 */
class LoginController extends AbstractController {
    
    private LoginService $loginService;
    
    public function __construct() {
        parent::__construct();
        $this->loginService = new LoginService();
    }
    
    /**
     * @return void
     * @throws RequestException
     */
    public function get() : void {
        // Voluntary exception throw: no GET operation supported for login system
        throw new RequestException("NOT IMPLEMENTED.", 501);
    }
    
    /**
     * Method to login.
     *
     * @return void
     * @throws Exception
     */
    public function post() : void {
        
        try {
            if (empty($_REQUEST["username"])) {
                throw new RequestException("Missing required parameter [username] in request.", 400, [], 400);
            }
            
            if (empty($_REQUEST["password"])) {
                throw new RequestException("Missing required parameter [password] in request.", 400, [], 400);
            }
            
            $this->loginService->doLogin($_REQUEST["username"], $_REQUEST["password"]);
            
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
            throw new Exception("Failure to log user in.", $excep->getCode(), $excep);
        }
    }
    
    /**
     * @return void
     * @throws RequestException
     */
    public function put() : void {
        // Voluntary exception throw: no PUT operation supported for login system
        throw new RequestException("NOT IMPLEMENTED.", 501);
    }
    
    /**
     * Method to logout.
     *
     * @return void
     */
    #[NoReturn] public function delete() : void {
        $this->loginService->doLogout();
        
        $response = [
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