<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project constants.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-14
 * (c) Copyright 2024 Marc-Eric Boury 
 */

/**
 * The directory separator symbol in web/URL paths.
 * @const
 */
const WEB_DIRECTORY_SEPARATOR = "/";
const NAMESPACE_PATH_SEPARATOR = "/";
const DB_DATETIME_FORMAT = "Y-m-d H:i:s.u";
const HTML_DATETIME_FORMAT = "Y-m-d\TH:i:s.v";


// <editor-fold defaultstate="collapsed" desc="ABSOLUTE PATHS (FILESYSTEM PATHS)">

/**
 * Absolute path to the project's root directory
 * @const
 */
define("PRJ_ROOT_DIR",
       realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "..") . DIRECTORY_SEPARATOR);

/**
 * The name of the project's root directory
 * @const
 */
define("PRJ_ROOT_DIRNAME", basename(PRJ_ROOT_DIR));

/**
 * Absolute path to the 'public' directory of the project.
 * @const
 */
const PRJ_PUBLIC_DIR = PRJ_ROOT_DIR . "public" . DIRECTORY_SEPARATOR;

/**
 * Absolute path to the 'private' directory of the project.
 * @const
 */
const PRJ_PRIVATE_DIR = PRJ_ROOT_DIR . "private" . DIRECTORY_SEPARATOR;

/**
 * Absolute path to the 'src' directory of the project.
 * @const
 */
const PRJ_SRC_DIR = PRJ_PRIVATE_DIR . "src" . DIRECTORY_SEPARATOR;

/**
 * Absolute path to the 'fragments' directory of the project.
 * @const
 */
const PRJ_FRAGMENTS_DIR = PRJ_PRIVATE_DIR . "fragments" . DIRECTORY_SEPARATOR;

/**
 * Absolute path to the 'config' directory of the project.
 * @const
 */
const PRJ_CONFIG_DIR = PRJ_PRIVATE_DIR . "config" . DIRECTORY_SEPARATOR;

/**
 * Absolute path to the public 'images' directory of the project.
 * @const
 */
const PRJ_IMAGES_DIR = PRJ_PUBLIC_DIR . "images" . DIRECTORY_SEPARATOR;

/**
 * Absolute path to the public 'css' directory of the project.
 * @const
 */
const PRJ_CSS_DIR = PRJ_PUBLIC_DIR . "css" . DIRECTORY_SEPARATOR;

/**
 * Absolute path to the public 'js' directory of the prohject.
 * @const
 */
const PRJ_JS_DIR = PRJ_PUBLIC_DIR . "js" . DIRECTORY_SEPARATOR;

/**
 * Absolute path to the public 'pages' directory of the project.
 * @const
 */
const PRJ_PAGES_DIR = PRJ_PUBLIC_DIR . "pages" . DIRECTORY_SEPARATOR;

/**
 * @const
 */
define("REQUEST_HOSTNAME", ($_SERVER["HTTP_HOST"] ?? "localhost"));
define("REQUEST_METHOD", $_SERVER["REQUEST_METHOD"] ?? "GET");
define("REQUEST_URI", $_SERVER["REQUEST_URI"] ?? "/");
define("REQUEST_PATH", explode("?", REQUEST_URI)[0]);

// </editor-fold>


// <editor-fold defaultstate="collapsed" desc="WEB PATHS (URLs)">

/**
 * Web path to the root project directory with dynamic hostname.
 * Example: 'localhost/<rootDirectory>'
 * @const
 */
const WEB_ROOT_DIR = WEB_DIRECTORY_SEPARATOR . PRJ_ROOT_DIRNAME . WEB_DIRECTORY_SEPARATOR; // REQUEST_HOSTNAME . WEB_DIRECTORY_SEPARATOR . "420DW3_07278_Project" . WEB_DIRECTORY_SEPARATOR;

const WEB_PUBLIC_DIR = WEB_ROOT_DIR . "public" . WEB_DIRECTORY_SEPARATOR;

/**
 * Web path to the public 'css' directory.
 * @const
 */
const WEB_CSS_DIR = WEB_PUBLIC_DIR . "css" . WEB_DIRECTORY_SEPARATOR;

/**
 * Web path to the public 'js' directory.
 * @const
 */
const WEB_JS_DIR = WEB_PUBLIC_DIR . "js" . WEB_DIRECTORY_SEPARATOR;

/**
 * Web path to the public 'pages' directory.
 * @const
 */
const WEB_PAGES_DIR = WEB_PUBLIC_DIR . "pages" . WEB_DIRECTORY_SEPARATOR;

/**
 * Web path to the public 'images' directory.
 * @const
 */
const WEB_IMAGES_DIR = WEB_PUBLIC_DIR . "images" . WEB_DIRECTORY_SEPARATOR;

// </editor-fold>

