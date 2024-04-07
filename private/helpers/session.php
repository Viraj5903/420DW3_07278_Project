<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project session.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-26
 * (c) Copyright 2024 Marc-Eric Boury 
 */

if (empty($_COOKIE["testCookie"])) {
    if (!empty($_REQUEST["isCookieTest"])) {
        // Test cookie not present and this is a reload from this testing => cookies not enabled.
        echo "This website requires cookies. Please enable them.";
        exit(0);
    } else {
        // Test cookie not present but this is NOT a reload/redirection from the test, we need to
        // try to create the test cookie and trigger the reload/redirection.
        // Try to create a domain-wide test cookie; any value is okay as long as it is not empty
        $host = (empty($_SERVER["HTTP_HOST"]) ? "localhost" : $_SERVER["HTTP_HOST"]);
        setcookie("testCookie", "true", time() + (60 * 60 * 24), "/", $host);
        // Set the response headers to trigger the reload, note the added URL parameter
        // Note the 307 redirection HTTP status that triggers explicitly an equivalent request
        $request_path = $_SERVER["REQUEST_URI"] ?? "";
        $separator = "?";
        if (str_contains($request_path, "?")) {
            $separator = "&";
        }
        header("Location: " . $request_path . $separator . "isCookieTest=true", true, 307);
        // Terminate the script immediately to send the reload response back to the browser.
        exit(0);
    }
}

session_start();
if (!empty($_SESSION["REQUEST_URI"])) {
    $_SESSION["PREVIOUS_REQUEST_URI"] = $_SESSION["REQUEST_URI"];
}
$_SESSION["REQUEST_URI"] = $_SERVER["REQUEST_URI"];
