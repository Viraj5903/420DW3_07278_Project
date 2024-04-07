<?php
declare(strict_types=1);
/*
* RouteCollection.php
* 420DW3_07278_Project
* (c) 2024 Marc-Eric Boury All rights reserved
*/

namespace Teacher\GivenCode\Domain;

use ArrayIterator;
use Debug;
use IteratorAggregate;
use Teacher\GivenCode\Exceptions\ValidationException;
use Traversable;

/**
 * TODO: Class documentation
 *
 *
 * @author Marc-Eric Boury
 * @since  2024-03-16
 */
class RouteCollection implements IteratorAggregate {
    
    /**
     * @var AbstractRoute[]
     */
    private array $routes = [];
    
    /**
     * Constructor for {@see RouteCollection}.
     *
     * @param array $routeArray An optional array of initial {@see AbstractRoute} elements.
     * @throws ValidationException If the initial route array contains invalid elements.
     */
    public function __construct(array $routeArray = []) {
        // Validate the initial route array.
        foreach ($routeArray as $index => $route) {
            if (!($route instanceof AbstractRoute)) {
                throw new ValidationException("Invalid route in RouteCollection initial array: element at index [$index] is not an instance of " .
                                              APIRoute::class . ".");
            }
        }
        $this->routes = $routeArray;
    }
    
    /**
     * @inheritDoc
     */
    public function getIterator() : Traversable {
        return new ArrayIterator($this->routes);
    }
    
    /**
     * TODO: Function documentation
     *
     *
     * @param AbstractRoute $route           The route to add to the collection.
     * @param bool          $optNoDuplicates [OPTIONAL] Whether to forbid duplicate routes or not. Defaults to <code>true</code>.
     * @return void
     * @throws ValidationException If an equivalent route exist in the collection and duplicates are forbidden.
     *
     * @author Marc-Eric Boury
     * @since  2024-03-16
     */
    public function addRoute(AbstractRoute $route, bool $optNoDuplicates = true) : void {
        if ($optNoDuplicates) {
            foreach ($this->routes as $existing_route) {
                if ($route == $existing_route) {
                    // object values comparison
                    throw new ValidationException("Equivalent route already present in RouteCollection.");
                }
            }
        }
        $this->routes[] = $route;
    }
    
    /**
     * Removes a route from the collection.
     * Returns <code>true</code> if the specified route is found and removed, and <code>false</code>
     * if the route was not found and thus not removed.
     *
     * @param AbstractRoute $route The route to remove.
     * @return bool <code>true</code> if a route was removed, <code>false</code> otherwise.
     *
     * @author Marc-Eric Boury
     * @since  2024-03-16
     */
    public function removeRoute(AbstractRoute $route) : bool {
        if (in_array($route, $this->routes)) {
            $key = array_search($route, $this->routes);
            array_splice($this->routes, $key, 1);
            return true;
        }
        return false;
    }
    
    /**
     * TODO: Function documentation
     *
     * @param string $uri_path
     * @return AbstractRoute|null
     *
     * @author Marc-Eric Boury
     * @since  2024-03-16
     */
    public function match(string $uri_path) : ?AbstractRoute {
        foreach ($this->routes as $route) {
            $route_path = strtolower(rtrim($route->getRoutePath(), "/"));
            $sanitized_uri_path = strtolower(rtrim($uri_path, "/"));
            if (($route instanceof AbstractRoute) && ($sanitized_uri_path == $route_path)) {
                Debug::log("Route found: matched [$uri_path] with route [" . $route->getRoutePath() . "]");
                return $route;
            }
        }
        Debug::log("No route matching [$uri_path] found.");
        return null;
    }
    
}