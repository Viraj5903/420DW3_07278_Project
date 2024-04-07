<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project APIRoute.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-14
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\GivenCode\Domain;

use Exception;
use Teacher\GivenCode\Abstracts\AbstractController;
use Teacher\GivenCode\Enumerations\HTTPMethodsEnum;
use Teacher\GivenCode\Exceptions\RequestException;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-14
 */
class APIRoute extends AbstractRoute {
    private string $controllerClass;
    
    /**
     * TODO: documentation
     *
     * @param string $uri
     * @param string $controllerClass
     * @throws ValidationException
     */
    public function __construct(string $uri, string $controllerClass) {
        if (!class_exists($controllerClass)) {
            throw new ValidationException("APIRoute specified controller class [$controllerClass] does not exists.");
        }
        if (!is_a($controllerClass, AbstractController::class, true)) {
            throw new ValidationException("APIRoute specified controller class [$controllerClass] does not extend [" .
                                          AbstractController::class . "].");
        }
        parent::__construct($uri);
        $this->controllerClass = $controllerClass;
    }
    
    /**
     * TODO: documentation
     *
     * @return string
     */
    public function getControllerClass() : string {
        return $this->controllerClass;
    }
    
    /**
     * {@inheritDoc}
     *
     * @return void
     * @throws RequestException
     *
     * @author Marc-Eric Boury
     * @since  2024-03-28
     */
    public function route() : void {
        $method = HTTPMethodsEnum::getValue($_SERVER["REQUEST_METHOD"]);
        $controller_class = $this->getControllerClass();
        $controller = (new $controller_class());
        if (!($controller instanceof AbstractController)) {
            // this should not happen ever as it is validated inside the APIRoute constructor.
            throw new Exception("APIRoute specified controller class [$controller_class] does not extend [" .
                                AbstractController::class . "].");
        }
        $controller->callHttpMethod($method);
        
    }
}