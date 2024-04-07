<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project InternalRouter.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-14
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\GivenCode\Services;

use Teacher\Examples\Controllers\AuthorController;
use Teacher\Examples\Controllers\BookController;
use Teacher\Examples\Controllers\ExampleController;
use Teacher\Examples\Controllers\LoginController;
use Teacher\Examples\Services\PageNavigator;
use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Domain\APIRoute;
use Teacher\GivenCode\Domain\CallableRoute;
use Teacher\GivenCode\Domain\RouteCollection;
use Teacher\GivenCode\Domain\WebpageRoute;
use Teacher\GivenCode\Exceptions\RequestException;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-14
 */
class InternalRouter implements IService {
    
    private string $uriBaseDirectory;
    private RouteCollection $routes;
    
    /**
     * @param string $uri_base_directory
     * @throws ValidationException
     */
    public function __construct(string $uri_base_directory = "") {
        $this->uriBaseDirectory = $uri_base_directory;
        $this->routes = new RouteCollection();
        $this->routes->addRoute(new APIRoute("/api/exampleDTO", ExampleController::class));
        $this->routes->addRoute(new APIRoute("/api/login", LoginController::class));
        $this->routes->addRoute(new APIRoute("/api/books", BookController::class));
        $this->routes->addRoute(new APIRoute("/api/authors", AuthorController::class));
        $this->routes->addRoute(new WebpageRoute("/index.php", "Teacher/Examples/example_page.php"));
        $this->routes->addRoute(new WebpageRoute("/", "Teacher/Examples/example_page.php"));
        $this->routes->addRoute(new CallableRoute("/pages/login", [PageNavigator::class, "loginPage"]));
        $this->routes->addRoute(new CallableRoute("/pages/books", [PageNavigator::class, "booksManagementPage"]));
        $this->routes->addRoute(new CallableRoute("/pages/authors", [PageNavigator::class, "authorsManagementPage"]));
    }
    
    /**
     * TODO: Function documentation
     *
     * @return void
     * @throws RequestException
     *
     * @author Marc-Eric Boury
     * @since  2024-03-16
     */
    public function route() : void {
        $path = REQUEST_PATH;
        $route = $this->routes->match($path);
        
        if (is_null($route)) {
            // route not found
            throw new RequestException("Route [$path] not found.", 404);
        }
        
        $route->route();
        
    }
}