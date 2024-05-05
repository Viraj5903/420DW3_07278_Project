<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project standard.page.footer.php
 *
 * @author Viraj Patel
 * @since 2024-04-30
 */

use Viraj\Project\Services\LoginService;

?>
<header class="container-fluid bg-dark py-2">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?= WEB_ROOT_DIR ?>">Viraj's Website</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= WEB_ROOT_DIR . "pages/users" ?>">Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= WEB_ROOT_DIR . "pages/permissions" ?>">Permissions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= WEB_ROOT_DIR . "pages/usergroups" ?>">User Groups</a>
                        </li>
                        <?php
                        if (LoginService::isUserLoggedIn()) {
                            $api_login_url = WEB_ROOT_DIR . "api/login";
                            $method = "delete";
                            echo <<<HTDOC
                        <li class="nav-item nav-bar-entry" data-url="$api_login_url" data-method="$method" data-type="api">
                            <span class="nav-link">Logout</span>
                        </li>
HTDOC;
                        } else {
                            $login_page_url = WEB_ROOT_DIR . "pages/login";
                            echo <<<HTDOC
                        <li class="nav-item nav-bar-entry" data-url="$login_page_url">
                            <span class="nav-link">Login</span>
                        </li>
HTDOC;
                        }
                        
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
