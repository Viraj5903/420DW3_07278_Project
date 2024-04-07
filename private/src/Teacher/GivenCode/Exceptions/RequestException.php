<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project RequestException.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-16
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\GivenCode\Exceptions;

use Throwable;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-16
 */
class RequestException extends RuntimeException {
    private int $httpResponseCode;
    private array $httpHeaders;
    
    /**
     * @param string         $message
     * @param int            $httpResponseCode
     * @param array          $httpHeaders
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $httpResponseCode = 500, array $httpHeaders = [],
                                int    $code = 0, ?Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->httpResponseCode = $httpResponseCode;
        $this->httpHeaders = $httpHeaders;
    }
    
    /**
     * TODO: Function documentation
     *
     * @return int
     *
     * @author Marc-Eric Boury
     * @since  2024-03-16
     */
    public function getHttpResponseCode() : int {
        return $this->httpResponseCode;
    }
    
    /**
     * TODO: Function documentation
     *
     * @return array
     *
     * @author Marc-Eric Boury
     * @since  2024-03-16
     */
    public function getHttpHeaders() : array {
        return $this->httpHeaders;
    }
    
    /**
     * TODO: Function documentation
     *
     * @param string $headerKey
     * @param string $headerValue
     * @return void
     *
     * @author Marc-Eric Boury
     * @since  2024-03-16
     */
    public function addHeader(string $headerKey, string $headerValue) : void {
        $this->httpHeaders[$headerKey] = $headerValue;
    }
    
}