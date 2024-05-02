<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project page.management.books.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-04-06
 * (c) Copyright 2024 Marc-Eric Boury 
 */

use Teacher\Examples\DTOs\BookDTO;
use Teacher\Examples\Services\AuthorService;
use Teacher\Examples\Services\BookService;
use Teacher\Examples\Services\LoginService;


if (!LoginService::isAuthorLoggedIn()) {
    LoginService::redirectToLogin();
}

$book_service = new BookService();
$author_service = new AuthorService();

$all_books = $book_service->getAllBooks();
$all_authors = $author_service->getAllAuthors();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Example Page</title>
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "bootstrap.min.css" ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "teacher.standard.css" ?>">
    <script type="text/javascript">
        
        const API_BOOK_URL = "<?= WEB_ROOT_DIR . "api/books" ?>";
    
    </script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "jquery-3.7.1.min.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "teacher.standard.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "teacher.page.books.js" ?>" defer></script>
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
            <h3 class="fullwidth text-center">Book Management</h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-4 row align-items-end align-items-md-center justify-content-center justify-content-md-end">
                <label class="col-12 text-start text-md-end align-items-md-center"
                       for="example-selector">Select a book:</label>
            </div>
            <div class="col-12 col-md-4 row justify-content-center">
                <select id="book-selector" class="">
                    <option value="" selected disabled>Select one...</option>
                    <option value='999999'>FAIL TEST (id# 999999)</option>
                    <?php
                    foreach ($all_books as $instance) {
                        $red_text = false;
                        if (!is_null($instance->getDateDeleted())) {
                            $red_text = true;
                        }
                        echo ("<option class='" . ($red_text
                                ? "text-red"
                                : "") . "' value='" . $instance->getId() . "'>" . $instance->getTitle() . "</option>");
                    }
                    ?>
                </select>
            </div>
            <div class="col-12 col-md-4 row justify-content-center justify-content-md-start py-2 py-md-0 px-4">
                <button id="view-instance-button"
                        class="btn btn-primary col-9 col-sm-5 col-md-9 col-lg-7 text-uppercase"
                        type="button">Load book
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
            <form id="book-form" class="row">
                <div class="col-12">
                    <label class="form-label" for="book-id">Id: </label>
                    <input id="book-id" class="form-control form-control-sm" type="number" name="id" readonly disabled>
                </div>
                <div class="col-12">
                    <label class="form-label" for="book-title">Title:</label>
                    <input id="book-title" class="form-control" type="text" name="title"
                           maxlength="<?= BookDTO::TITLE_MAX_LENGTH ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label" for="book-description">Description:</label>
                    <input id="book-description" class="form-control" type="text" name="description"
                           maxlength="<?= BookDTO::DESCRIPTION_MAX_LENGTH ?>" >
                </div>
                <div class="col-12">
                    <label class="form-label" for="book-isbn">ISBN:</label>
                    <input id="book-isbn" class="form-control" type="text" name="isbn"
                           maxlength="<?= BookDTO::ISBN_MAX_LENGTH ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label" for="book-publication-year">Publication year:</label>
                    <input id="book-publication-year" class="form-control" type="number" name="publicationYear"
                           min="<?= BookDTO::PUBLICATION_YEAR_LOWER_BOUND ?>" max="<?= (new DateTime())->format('Y') ?>"
                           step="1" required>
                </div>
                <div class="col-12 flex-column">
                    <label class="form-label col-12 text-start">Book authors:</label>
                    <?php
                    foreach ($all_authors as $author) {
                        $author_id = $author->getId();
                        $label_text = $author->getFirstName() . " " . $author->getLastName();
                        echo <<< HTDOC
                    <div class="col-12">
                        <input id="book-author-$author_id" class="form-check-input book-authors" type="checkbox" name="authors[$author_id]" data-author-id="$author_id" required>
                        <label class="form-check-label" for="book-author-$author_id">$label_text</label>
                    </div>
HTDOC;
                    }
                    ?>
                </div>
                <div class="col-12">
                    <label class="form-label" for="book-date-created">Date created: </label>
                    <input id="book-date-created" class="form-control form-control-sm" type="datetime-local" name="dateCreated"
                           readonly disabled>
                </div>
                <div class="col-12">
                    <label class="form-label" for="book-date-last-modified">Date last modified: </label>
                    <input id="book-date-last-modified" class="form-control form-control-sm" type="datetime-local"
                           name="dateLastModified"
                           readonly disabled>
                </div>
                <div class="col-12">
                    <label class="form-label" for="book-date-deleted">Date deleted: </label>
                    <input id="book-date-deleted" class="form-control form-control-sm" type="datetime-local"
                           name="dateDeleted"
                           readonly disabled>
                </div>
            </form>
            <div class="col-12 d-flex flex-wrap justify-content-around button-row">
                <button id="create-button" type="button" class="btn btn-primary col-12 col-md-2 my-1 my-md-0 text-uppercase">Create</button>
                <button id="clear-button" type="button" class="btn btn-info col-12 col-md-2 my-1 my-md-0 text-uppercase" disabled>Clear Form</button>
                <button id="update-button" type="button" class="btn btn-success col-12 col-md-2 my-1 my-md-0 text-uppercase" disabled>Update</button>
                <button id="delete-button" type="button" class="btn btn-danger col-12 col-md-2 my-1 my-md-0 text-uppercase" disabled>Delete</button>
            </div>
        </div>
    
    </div>
</main>
<footer id="footer">
    <?php
    include "standard.page.footer.php";
    ?>
</footer>
</body>
</html>