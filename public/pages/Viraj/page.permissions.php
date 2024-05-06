<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project page.permissions.php
 *
 * @author Viraj Patel
 * @since 2024-05-03
 */

use Viraj\Project\DTOs\PermissionDTO;
use Viraj\Project\Services\LoginService;
use Viraj\Project\Services\PermissionCheckService;
use Viraj\Project\Services\PermissionsService;

if (!LoginService::isUserLoggedIn()) {
    LoginService::redirectToLogin();
}

$permission_service = new PermissionsService();
try {
    
    if (!PermissionCheckService::checkPermission("MANAGE_PERMISSIONS")) {
        header("Location: " . WEB_ROOT_DIR . "pages/accessdenied");
        // http_response_code(403);
        exit();
    }
    
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
    <title>Pemission Management</title>
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "bootstrap.min.css" ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "viraj.standard.css" ?>">
</head>
<body>
<?php include PRJ_FRAGMENTS_DIR . "Viraj" . DIRECTORY_SEPARATOR . "standard.page.header.php"; ?>

<main id="main">
    <div class="container">
        <div class="row justify-content-center">
            <h3 class="fullwidth text-center">Pemission Management</h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-4 row align-items-end align-items-md-center justify-content-center justify-content-md-end">
                <label class="col-12 text-start text-md-end align-items-md-center" for="permission-selector">Select a permission:</label>
            </div>
            <div class="col-12 col-md-4 row justify-content-center">
                <select id="permission-selector" class="">
                    <option value="" selected disabled>Select one...</option>
                    <?php foreach ($all_permissions as $permission) : ?>
                        <option value="<?= $permission->getId() ?>"><?= $permission->getUniquePermission() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-4 row justify-content-center justify-content-md-start py-2 py-md-0 px-4">
                <button id="view-permission-button" class="btn btn-primary col-9 col-sm-5 col-md-9 col-lg-7 text-uppercase"
                        type="button">Load Pemission
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
            <form id="permission-form" class="row">
                <div class="col-12">
                    <label class="form-label" for="id">Id: </label>
                    <input id="id" class="form-control form-control-sm" type="number" name="id" readonly disabled>
                </div>
                <div class="col-12">
                    <label class="form-label" for="unique_permission">Unique Permission:</label>
                    <input id="unique_permission" class="form-control" type="text" name="unique_permission"
                           maxlength="<?= PermissionDTO::UNIQUE_PERMISSION_MAX_LENGTH ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label" for="permission_name">Permission Name:</label>
                    <input id="permission_name" class="form-control" type="text" name="permission_name"
                           maxlength="<?= PermissionDTO::PERMISSION_NAME_MAX_LENGTH ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label" for="description">Description:</label>
                    <textarea class="form-control" name="description" id="description" maxlength="<?= PermissionDTO::DESCRIPTION_MAX_LENGTH ?>"></textarea>
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
    
    const API_PERMISSION_URL = "<?= WEB_ROOT_DIR . "api/permissions" ?>";

</script>
<script type="text/javascript" src="<?= WEB_JS_DIR . "jquery-3.7.1.min.js" ?>" defer></script>
<script src="<?= WEB_JS_DIR . "viraj.standard.js" ?>" defer></script>
<script type="text/javascript" src="<?= WEB_JS_DIR . "viraj.page.permissions.js" ?>" defer></script>
</body>
</html>
