<?php
declare(strict_types=1);
/*
* AbstractController.php
* 420DW3_07278_Project
* (c) 2024 Marc-Eric Boury All rights reserved
*/

namespace Teacher\GivenCode\Abstracts;

use Debug;
use Teacher\GivenCode\Enumerations\HTTPMethodsEnum;
use Teacher\GivenCode\Exceptions\RequestException;

abstract class AbstractController implements IController {
    
    public function __construct() {}
    
    abstract public function get() : void;
    
    abstract public function post() : void;
    
    abstract public function put() : void;
    
    abstract public function delete() : void;
    
    public function getAllowedMethods() : array {
        $allowed_methods_array = [];
        foreach (HTTPMethodsEnum::cases() as $http_enum_case) {
            if (method_exists(static::class, strtolower($http_enum_case->value))) {
                $allowed_methods_array[] = $http_enum_case->value;
            }
        }
        return $allowed_methods_array;
    }
    
    /**
     * TODO: Function documentation
     *
     * @param HTTPMethodsEnum $method
     * @return void
     * @throws RequestException
     *
     * @author Marc-Eric Boury
     * @since  2024-03-28
     */
    public function callHttpMethod(HTTPMethodsEnum $method) : void {
        $class_method = strtolower($method->value);
        if (!method_exists(static::class, $class_method)) {
            throw new RequestException("HTTP method [$method->value] not supported.", 405, [
                "Allow" => implode(", ", $this->getAllowedMethods())
            ]);
        }
        Debug::log("Controller: calling method " . static::class . "::$class_method().");
        static::$class_method();
    }
}