<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project page.users.php
 *
 * @author Viraj Patel
 * @since 2024-05-03
 */

use Viraj\Project\DTOs\UserDTO;
use Viraj\Project\Services\LoginService;
use Viraj\Project\Services\PermissionCheckService;
use Viraj\Project\Services\PermissionsService;
use Viraj\Project\Services\UsersService;

if (!LoginService::isUserLoggedIn()) {
    LoginService::redirectToLogin();
}

$user_service = new UsersService();
$permission_service = new PermissionsService();
try {
    
    PermissionCheckService::checkPermission("MANAGE_USERS");
    
    $all_users = $user_service->getAllUsers();
    $all_permissions = $permission_service->getAllPermissions();
} catch (Exception $exception) {
    var_export($exception->getMessage());
    var_export($exception->getTrace());
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Management</title>
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "bootstrap.min.css" ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "viraj.standard.css" ?>">
</head>
<body>
<?php include PRJ_FRAGMENTS_DIR . "Viraj" . DIRECTORY_SEPARATOR . "standard.page.header.php"; ?>

<main id="main">
    <div class="container">
        <div class="row justify-content-center">
            <h3 class="fullwidth text-center">User Management</h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-4 row align-items-end align-items-md-center justify-content-center justify-content-md-end">
                <label class="col-12 text-start text-md-end align-items-md-center" for="user-selector">Select a user:</label>
            </div>
            <div class="col-12 col-md-4 row justify-content-center">
                <select id="user-selector" class="">
                    <option value="" selected disabled>Select one...</option>
                    <?php foreach ($all_users as $user) : ?>
                        <option value="<?= $user->getId() ?>"><?= $user->getUsername() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-4 row justify-content-center justify-content-md-start py-2 py-md-0 px-4">
                <button id="view-user-button" class="btn btn-primary col-9 col-sm-5 col-md-9 col-lg-7 text-uppercase"
                        type="button">Load user
                </button>
            </div>
        </div>
        <div class="row">
        
        </div>
        <div class="error-display hidden">
            <h1 id="error-class" class="col-12 error-text"></h1>
            <h3 id="error-message" class="col-12 error-text"></h3>
            <div id="error-previous" class="col-12"></div>
            <pre id="error-stacktrace" class="col-12"></pre>
        </div>
        <br/>
        <div class="container">
            <form id="user-form" class="row">
                <div class="col-12">
                    <label class="form-label" for="id">Id: </label>
                    <input id="id" class="form-control form-control-sm" type="number" name="id" readonly disabled>
                </div>
                <div class="col-12">
                    <label class="form-label" for="username">Username:</label>
                    <input id="username" class="form-control" type="text" name="username"
                           maxlength="<?= UserDTO::USERNAME_MAX_LENGTH ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label" for="password">Password:</label>
                    <input id="password" class="form-control" type="text" name="password"
                           maxlength="255" required>
                </div>
                <div class="col-12">
                    <label class="form-label" for="email">Email:</label>
                    <input id="email" class="form-control" type="email" name="email"
                           maxlength="<?= UserDTO::EMAIL_MAX_LENGTH ?>" required>
                </div>
                <div class="col-12 flex-column">
                    <label class="form-label col-12 text-start">Permissions:</label>
                    <?php
                    foreach ($all_permissions as $permission) {
                        $permission_id = $permission->getId();
                        $label_text = $permission->getUniquePermission();
                        echo <<< HTDOC
                    <div class="col-12">
                        <input id="user-permission-$permission_id" class="form-check-input user-permissions" type="checkbox" name="permissions[$permission_id]" value="$permission_id">
                        <label class="form-check-label" for="user-permission-$permission_id">$label_text</label>
                    </div>
HTDOC;
                    }
                    ?>
                </div>
                <div class="col-12">
                    <label class="form-label" for="date_created">Date Created: </label>
                    <input id="date_created" class="form-control form-control-sm" type="datetime-local" name="date_created"
                           readonly disabled>
                </div>
                <div class="col-12">
                    <label class="form-label" for="date_modified">Date Last Modified: </label>
                    <input id="date_modified" class="form-control form-control-sm" type="datetime-local"
                           name="date_modified"
                           readonly disabled>
                </div>
            </form>
            <div class="col-12 d-flex flex-wrap justify-content-around button-row my-4">
                <button id="create-button" type="button"
                        class="btn btn-primary col-12 col-md-2 my-1 my-md-0 text-uppercase">Create
                </button>
                <button id="clear-button" type="button" class="btn btn-secondary col-12 col-md-2 my-1 my-md-0 text-uppercase"
                        disabled>Clear Form
                </button>
                <button id="update-button" type="button"
                        class="btn btn-success col-12 col-md-2 my-1 my-md-0 text-uppercase" disabled>Update
                </button>
                <button id="delete-button" type="button"
                        class="btn btn-danger col-12 col-md-2 my-1 my-md-0 text-uppercase" disabled>Delete
                </button>
            </div>
        </div>
    
    </div>
</main>
<?php include PRJ_FRAGMENTS_DIR . "Viraj" . DIRECTORY_SEPARATOR . "standard.page.footer.php"; ?>

<script type="text/javascript">
    
    const API_USER_URL = "<?= WEB_ROOT_DIR . "api/users" ?>";

</script>
<script type="text/javascript" src="<?= WEB_JS_DIR . "jquery-3.7.1.min.js" ?>" defer></script>
<script src="<?= WEB_JS_DIR . "viraj.standard.js" ?>" defer></script>
<script type="text/javascript" src="<?= WEB_JS_DIR . "viraj.page.users.js" ?>" defer></script>
</body>
</html>

