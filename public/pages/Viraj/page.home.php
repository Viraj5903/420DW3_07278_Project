<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project page.home.php
 *
 * @author Viraj Patel
 * @since 2024-05-03
 */

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modular ERP Software</title>
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
            <h1>Welcome to Modular ERP Software</h1>
            <p class="lead">Your platform for e-commerce transactional web applications</p>
            <p>This system provides robust management functionalities for users, user groups, and permissions.</p>
            <p>It allows you to efficiently manage access rights and permissions within your organization.</p>
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