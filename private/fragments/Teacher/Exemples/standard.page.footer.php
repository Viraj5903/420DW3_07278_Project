<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project standard.page.footer.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-26
 * (c) Copyright 2024 Marc-Eric Boury 
 */

?>
<div class="container">
    <div class="flex-column justify-content-start">
        <div class="row links-container">
            <div class="flex-column links-block">
                <a class="d-flex col-12" href="<?= WEB_ROOT_DIR ?>">Example page</a>
                <a class="d-flex col-12" href="<?= WEB_ROOT_DIR . "pages/books" ?>">Book management page</a>
                <a class="d-flex col-12" href="<?= WEB_ROOT_DIR . "pages/authors" ?>">Author management page</a>
            </div>
        </div>
        <div class="row copyright-container">
            <div class="flex-column">
                <span class="copyright-notice">Copyright (c) <?= (new DateTime())->format('Y')?> Marc-Eric Boury - All rights reserved.</span>
            </div>
        </div>
    </div>
</div>
