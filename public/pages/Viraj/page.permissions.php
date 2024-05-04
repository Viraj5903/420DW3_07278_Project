<?php
declare(strict_types=1);

use Viraj\Project\DTOs\UserDTO;
use Viraj\Project\Services\LoginService;

if (!LoginService::isUserLoggedIn()) {
    LoginService::redirectToLogin();
}

$user = $_SESSION["LOGGED_IN_USER"];

?>