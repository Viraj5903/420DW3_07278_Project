<?php
declare(strict_types=1);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "bootstrap.min.css" ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "viraj.standard.css" ?>">
</head>
<body>
<header id="header">
    <?php include PRJ_FRAGMENTS_DIR . "Viraj" . DIRECTORY_SEPARATOR . "standard.page.header.php"; ?>
</header>

<main class="container my-5">
    <div class="row">
        <div class="col-md-8 offset-md-2 text-center">
            <h1>ACCESS DENIDED FOR THIS PAGE.</h1>
        </div>
    </div>
</main>

<footer id="footer">
    <?php include PRJ_FRAGMENTS_DIR . "Viraj" . DIRECTORY_SEPARATOR . "standard.page.footer.php"; ?>
</footer>

<script src="<?= WEB_JS_DIR . "jquery-3.7.1.min.js" ?>"></script>
<script src="<?= WEB_JS_DIR . "viraj.standard.js" ?>" defer></script>
</body>
</html>