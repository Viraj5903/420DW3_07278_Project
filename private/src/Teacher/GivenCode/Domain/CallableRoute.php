<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project CallableRoute.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-28
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\GivenCode\Domain;

use Teacher\GivenCode\Exceptions\ValidationException;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-28
 */
class CallableRoute extends AbstractRoute {
    
    private string $callable_string;
    
    public function __construct(string $uri, callable $closure) {
        $is_callable = is_callable($closure, false, $out_callable_name);
        if (!$is_callable) {
            throw new ValidationException("Callable route value for route [$uri] is not a callable value.");
        }
        $this->callable_string = $out_callable_name;
        parent::__construct($uri);
    }
    
    /**
     * {@inheritDoc}
     *
     * @return void
     *
     * @author Marc-Eric Boury
     * @since  2024-03-28
     */
    public function route() : void {
        call_user_func($this->callable_string);
    }
}