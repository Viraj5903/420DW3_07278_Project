<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project InternalRouter.php
 *
 * @author Viraj Patel
 * @since 2024-04-11
 */

namespace Viraj\Project\Services;

use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Domain\APIRoute;
use Teacher\GivenCode\Domain\CallableRoute;
use Teacher\GivenCode\Domain\RouteCollection;
use Teacher\GivenCode\Domain\WebpageRoute;
use Teacher\GivenCode\Exceptions\RequestException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Viraj\Project\Controllers\LoginController;
use Viraj\Project\Controllers\PageNavigator;
use Viraj\Project\Controllers\PermissionsController;
use Viraj\Project\Controllers\UsergroupsController;
use Viraj\Project\Controllers\UsersController;

/**
 *
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
        $this->routes->addRoute(new APIRoute("/api/permissions", PermissionsController::class));
        $this->routes->addRoute(new APIRoute("/api/users", UsersController::class));
        $this->routes->addRoute(new APIRoute("/api/userGroups", UsergroupsController::class));
        $this->routes->addRoute(new APIRoute("/api/login", LoginController::class));
        $this->routes->addRoute(new WebpageRoute("/index.php", "Viraj/page.home.php"));
        $this->routes->addRoute(new WebpageRoute("/", "Viraj/page.home.php"));
        $this->routes->addRoute(new CallableRoute("/pages/login", [PageNavigator::class, "loginPage"]));
        $this->routes->addRoute(new CallableRoute("/pages/users", [PageNavigator::class, "usersManagementPage"]));
        $this->routes->addRoute(new CallableRoute("/pages/usergroups", [PageNavigator::class, "userGroupsManagementPage"]));
        $this->routes->addRoute(new CallableRoute("/pages/permissions", [PageNavigator::class, "permissionsManagementPage"]));
    }
    
    /**
     * @return void
     * @throws RequestException
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