<?php
declare(strict_types=1);

use Teacher\Examples\Services\AuthorService;
use Teacher\Examples\Services\LoginService;

/*
 * 420DW3_07278_Project page.login.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-04-06
 * (c) Copyright 2024 Marc-Eric Boury 
 */

if (LoginService::isAuthorLoggedIn()) {
    header("Location: " . WEB_ROOT_DIR);
    http_response_code(302);
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Example Page</title>
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "bootstrap.min.css" ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "teacher.standard.css" ?>">
    <script type="text/javascript">
        
        const API_LOGIN_URL = "<?= WEB_ROOT_DIR . "api/login" ?>";
    
    </script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "jquery-3.7.1.min.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "teacher.standard.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "teacher.page.login.js" ?>" defer></script>
</head>
<body>
<header id="header">
    <?php
    include "standard.page.header.php";
    ?>
</header>
<main id="main">
    <div class="container">
        <div class="row justify-content-center">
            <h3 class="fullwidth text-center">Tests for ExampleDTOs</h3>
        </div>
        <form id="loginForm" class="row">
            <div class="row justify-content-center">
                <h3 class="fullwidth text-center">Select an author to log-in with</h3>
            </div>
            <?php
            $from = "";
            if (!empty($_REQUEST["from"])) {
                $from = $_REQUEST["from"];
            }
            ?>
            <input type="hidden" name="from" value="<?= $from ?>">
            <?php
            $author_service = new AuthorService();
            $all_authors = $author_service->getAllAuthors();
            
            foreach ($all_authors as $author) {
                $author_id = $author->getId();
                $author_name = $author->getFirstName() . " " . $author->getLastName();
                $element_id = $author_id . "_id";
                echo <<< HTDOC
            <div class="form-check">
                <input type="radio" id="$element_id" class="form-check-input" name="authorId" value="$author_id" required>
                <label for="$element_id" class="form-check-label" >$author_name</label>
            </div>
HTDOC;
            }
            ?>
            <div class="row d-flex justify-content-center">
                <button id="loginButton" type="button" class="btn btn-primary col-12 col-md-4">Log-In!</button>
            </div>
        </form>
    </div>
</main>
<footer id="footer">
    <?php
    include "standard.page.footer.php";
    ?>
</footer>
</body>
</html>