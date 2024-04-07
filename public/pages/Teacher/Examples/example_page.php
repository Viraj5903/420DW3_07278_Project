<?php
declare(strict_types=1);

require_once __DIR__ . "/../../../../private/helpers/init.php";

use Teacher\Examples\Enumerations\DaysOfWeekEnum;
use Teacher\Examples\Services\ExampleService;

/*
 * 420DW3_07278_Project example_page.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-16
 * (c) Copyright 2024 Marc-Eric Boury 
 */

$example_service = new ExampleService();
$all_example_instances = $example_service->getAllExamples();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Example Page</title>
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "bootstrap.min.css" ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "teacher.standard.css" ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "examples.css" ?>">
    <script type="text/javascript">
        
        const EXAMPLE_API_URL = "<?= WEB_ROOT_DIR . "api/ExampleDto" ?>";
    
    </script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "jquery-3.7.1.min.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "teacher.standard.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "teacher.page.example.js" ?>" defer></script>
</head>
<body>
<header id="header" class="header">
    <?php
    include PRJ_FRAGMENTS_DIR . "Teacher" . DIRECTORY_SEPARATOR . "Exemples" . DIRECTORY_SEPARATOR .
        "standard.page.header.php";
    ?>
</header>
<main id="main" class="main">
    <div class="container">
        <div class="row justify-content-center">
            <h3 class="fullwidth text-center">Tests for ExampleDTOs</h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-4 row align-items-end align-items-md-center justify-content-center justify-content-md-end">
                <label class="col-12 text-start text-md-end align-items-md-center"
                       for="example-selector">Select an example record:</label>
            </div>
            <div class="col-12 col-md-4 row justify-content-center">
                <select id="example-selector" class="">
                    <option value="" selected disabled>Select one...</option>
                    <option value='999999'>FAIL TEST (id# 999999)</option>
                    <?php
                    foreach ($all_example_instances as $instance) {
                        echo "<option value='" . $instance->getId() . "'>" . $instance->getDayOfTheWeek()->value . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-12 col-md-4 row justify-content-center justify-content-md-start py-2 py-md-0 px-4">
                <button id="view-instance-button" class="btn btn-primary col-9 col-sm-5 col-md-9 col-lg-7 text-uppercase"
                        type="button">Load example
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
    </div>
    <br/>
    <div class="container">
        <form id="example-form" class="row">
            <div class="col-12">
                <label class="form-label" for="example-id">Id: </label>
                <input class="form-control form-control-sm" id="example-id" type="number" name="id" readonly disabled>
            </div>
            <div class="col-12">
                <label class="form-label" for="example-day-of-week">Day of the week:</label>
                <select class="form-select form-select-sm" id="example-day-of-week" name="dayOfTheWeek" required>
                    <option value="" selected disabled>Select one...</option>
                    <?php
                    foreach (DaysOfWeekEnum::cases() as $enum_case) {
                        echo "<option value='" . $enum_case->value . "'>" . $enum_case->name . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label" for="example-description">Description: </label>
                <textarea class="form-control form-control-sm" id="example-description" name="description" rows="6" maxlength="256"></textarea>
            </div>
            <div class="col-12">
                <label class="form-label" for="example-creation-date">Creation date: </label>
                <input class="form-control form-control-sm" id="example-creation-date" type="datetime-local" name="creationDate" readonly disabled>
            </div>
            <div class="col-12">
                <label class="form-label" for="example-modification-date">Last modification date: </label>
                <input class="form-control form-control-sm" id="example-modification-date" type="datetime-local" name="lastModificationDate"
                       readonly disabled>
            </div>
            <div class="col-12">
                <label class="form-label" for="example-deletion-date">Deletion date: </label>
                <input class="form-control form-control-sm" id="example-deletion-date" type="datetime-local" name="deletionDate" readonly disabled>
            </div>
        </form>
        <div class="col-12 d-flex flex-wrap justify-content-around button-row">
            <button id="create-button" type="button" class="btn btn-primary col-12 col-md-2 my-1 my-md-0 text-uppercase">Create</button>
            <button id="clear-button" type="button" class="btn btn-info col-12 col-md-2 my-1 my-md-0 text-uppercase" disabled>Clear Form</button>
            <button id="update-button" type="button" class="btn btn-success col-12 col-md-2 my-1 my-md-0 text-uppercase" disabled>Update</button>
            <button id="delete-button" type="button" class="btn btn-danger col-12 col-md-2 my-1 my-md-0 text-uppercase" disabled>Delete</button>
        </div>
    </div>
</main>
<footer>
    <?php
    include PRJ_FRAGMENTS_DIR . "Teacher" . DIRECTORY_SEPARATOR . "Exemples" . DIRECTORY_SEPARATOR .
        "standard.page.footer.php";
    ?>
</footer>
</body>
</html>
