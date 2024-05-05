<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project page.login.php
 *
 * @author Viraj Patel
 * @since 2024-05-03
 */

use Viraj\Project\Services\LoginService;


if (LoginService::isUserLoggedIn()) {
    header("Location: " . WEB_ROOT_DIR);
    http_response_code(302);
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "bootstrap.min.css" ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "viraj.standard.css" ?>">
    <style>
        body {
            background-color: #f8f9fa;
        }

        #main {
            padding-top: 80px;
            padding-bottom: 80px;
        }
    </style>
</head>
<body>
<?php include PRJ_FRAGMENTS_DIR . "Viraj" . DIRECTORY_SEPARATOR . "standard.page.header.php"; ?>

<main id="main">
    <div class="container">
        <div class="error-display hidden">
            <h1 id="error-class" class="col-12 error-text"></h1>
            <h3 id="error-message" class="col-12 error-text"></h3>
            <div id="error-previous" class="col-12"></div>
            <pre id="error-stacktrace" class="col-12"></pre>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Login</h3>
                        <form id="loginForm">
                            <?php
                            $from = $_REQUEST["from"] ?? "";
                            ?>
                            <input type="hidden" name="from" value="<?= $from ?>">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-primary" id="loginButton">Log-In</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include PRJ_FRAGMENTS_DIR . "Viraj" . DIRECTORY_SEPARATOR . "standard.page.footer.php"; ?>

<script type="text/javascript">
    
    const API_LOGIN_URL = "<?= WEB_ROOT_DIR . "api/login" ?>";

</script>
<script src="<?= WEB_JS_DIR . "jquery-3.7.1.min.js" ?>" defer></script>
<script src="<?= WEB_JS_DIR . "viraj.standard.js" ?>" defer></script>
<script src="<?= WEB_JS_DIR . "viraj.page.login.js" ?>" defer></script>
</body>
</html>
