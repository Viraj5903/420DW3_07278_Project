<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project ApplicationExample.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-14
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\Examples;

use Debug;
use ErrorException;
use Exception;
use Teacher\GivenCode\Services\InternalRouter;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-14
 */
class ApplicationExample {
    private InternalRouter $router;
    
    public function __construct() {
        $this->router = new InternalRouter();
    }
    
    /**
     * TODO: Function documentation
     *
     * @return void
     *
     * @author Marc-Eric Boury
     * @since  2024-03-16
     */
    public function run() : void {
        // start the output buffering
        ob_start();
        try {
            // route the request
            $this->router->route();
            
            $error = error_get_last();
            if ($error === null) {
                // flush the output buffer
                ob_end_flush();
                return;
            }
            throw new ErrorException($error['message'], 500, $error['type'], $error['file'], $error['line']);
            
        } catch (Exception $exception) {
            // empty the output buffer (without flushing)
            ob_end_clean();
            // handle the exception and generate an error response.
            Debug::logException($exception);
            Debug::outputException($exception);
            die();
        }
    }
}