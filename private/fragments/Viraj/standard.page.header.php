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
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
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
                            // The data-* global attributes form a class of attributes called custom data attributes, that allow proprietary information to be exchanged between the HTML and its DOM representation by scripts.
                            
                            /* The data-* attribute is used to store custom data private to the page or application.
                            The data-* attribute gives us the ability to embed custom data attributes on all HTML elements.
                            The stored (custom) data can then be used in the page's JavaScript to create a more engaging user experience (without any Ajax calls or server-side database queries).
                            The data-* attribute consist of two parts:
                            The attribute name should not contain any uppercase letters, and must be at least one character long after the prefix "data-"
                            The attribute value can be any string
                            Note: Custom attributes prefixed with "data-" will be completely ignored by the user agent.
                            */
                            
                            echo <<<HTDOC
                        <li class="nav-item nav-bar-entry" data-url="$api_login_url" data-method="$method" data-type="api">
                            <span class="nav-link">Logout</span>
                        </li>
HTDOC;
                        } else {
                            $login_page_url = WEB_ROOT_DIR . "pages/login";
                            // The data-* global attributes form a class of attributes called custom data attributes, that allow proprietary information to be exchanged between the HTML and its DOM representation by scripts.
                            /* The data-* attribute is used to store custom data private to the page or application.
                            The data-* attribute gives us the ability to embed custom data attributes on all HTML elements.
                            The stored (custom) data can then be used in the page's JavaScript to create a more engaging user experience (without any Ajax calls or server-side database queries).
                            The data-* attribute consist of two parts:
                            The attribute name should not contain any uppercase letters, and must be at least one character long after the prefix "data-"
                            The attribute value can be any string
                            Note: Custom attributes prefixed with "data-" will be completely ignored by the user agent.
                            */
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

<script>
    // Add an event listener to the navbar-toggler button
    document.querySelector('.navbar-toggler').addEventListener('click', function() {
        // Toggle the 'show' class on the navbar-collapse element
        document.querySelector('.navbar-collapse').classList.toggle('show');
    });
</script>

