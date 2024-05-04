<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project RequestException.php
 *
 * @author Viraj Patel
 * @since 2024-04-04
 */

namespace Viraj\Project\Exceptions;

use Throwable;

/**
 *
 */
class RequestException extends RuntimeException {
    
    // Class properties.
    private int $httpResponseCode;
    private array $httpHeaders;
    
    /**
     * Construct the exception. Note: The message is NOT binary safe.
     * @link https://php.net/manual/en/exception.construct.php
     * @param string         $message  [optional] The Exception message to throw.
     * @param int            $code     [optional] The Exception code.
     * @param null|Throwable $previous [optional] The previous throwable used for the exception chaining.
     */
    public function __construct(string $message = "", int $httpResponseCode = 500, array $httpHeaders = [], int $code = 0, ?Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->httpResponseCode = $httpResponseCode;
        $this->httpHeaders = $httpHeaders;
    }
    
    /**
     * @return int
     */
    public function getHttpResponseCode() : int {
        return $this->httpResponseCode;
    }
    
    /**
     * @return array
     */
    public function getHttpHeaders() : array {
        return $this->httpHeaders;
    }
    
    /**
     * @param string $headerKey
     * @param string $headerValue
     * @return void
     */
    public function addHeader(string $headerKey, string $headerValue) : void {
        $this->httpHeaders[$headerKey] = $headerValue;
    }
}