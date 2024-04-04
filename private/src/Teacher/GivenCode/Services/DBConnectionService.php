<?php
declare(strict_types=1);
/*
* DBConnectionService.php
* 420DW3_07278_Project
* (c) 2024 Marc-Eric Boury All rights reserved
*/

namespace Teacher\GivenCode\Services;

use PDO;
use PDOException;
use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Exceptions\RuntimeException;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-16
 */
class DBConnectionService implements IService {
    
    private const DB_NAME = "420dw3_07278_project";
    private static ?PDO $CONNECTION = null;
    
    /**
     * TODO: Function documentation
     *
     * @static
     * @return PDO
     *
     * @throws RuntimeException
     * @author Marc-Eric Boury
     * @since  2024-03-16
     */
    public static function getConnection() : PDO {
        try {
            if (!(self::$CONNECTION instanceof PDO)) {
                
                self::$CONNECTION = new PDO("mysql:dbname=" . self::DB_NAME . ";host=" . $_SERVER["HTTP_HOST"], "root",
                                            "");
                self::$CONNECTION->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return self::$CONNECTION;
        } catch (PDOException $exception) {
            throw new RuntimeException("Failure to connect to the database: " . $exception->getMessage());
        }
    }
    
}