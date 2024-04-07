<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project debug.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-14
 * (c) Copyright 2024 Marc-Eric Boury 
 */


require_once "constants.php";

class Debug {
    public static bool $DEBUG_MODE = false;
    
    /**
     * TODO: Function documentation
     *
     * @param string $message
     * @param bool   $doDie
     * @return void
     *
     * @author Marc-Eric Boury
     * @since  2024-03-26
     */
    public static function log(string $message, bool $doDie = false) : void {
        if (self::$DEBUG_MODE) {
            echo $message . "<br/>";
        }
        $file_handle = fopen(PRJ_PRIVATE_DIR . "log.txt", "a");
        if ($file_handle) {
            fwrite($file_handle, (new DateTime())->format(DB_DATETIME_FORMAT) . ": " . $message . PHP_EOL);
        }
        fclose($file_handle);
        if ($doDie) {
            die(0);
        }
    }
    
    /**
     * Basic debug helper function. Generates an HTML table string for whatever value is provided in <code>$input</code>.
     *  The table will contain the type data of the <code>$input</code> value, and its value(s). For container-types values
     *  (arrays, objects...), the function is recursive and will display each element or property of the container-type.
     *
     *  By default, the string is echoed before the function returns it.
     *
     * @param mixed $input   The value to debug
     * @param bool  $doEcho  OPTIONAL: Whether to echo the generated HTML table string before returning it or not.
     *                       Defaults to <code>true</code>
     * @param bool  $doDie   OPTIONAL: Wheter to stop execution after echoing the HTML table. Defaults to <code>false</code>
     *
     * @return string|null
     *
     * @author Marc-Eric Boury
     * @since  2023-01-05
     */
    public static function debugToHtmlTable(mixed $input, bool $doEcho = true, bool $doDie = false) : ?string {
        $return_value = null;
        if (self::$DEBUG_MODE) {
            $return_value = "<table style='border: 1px solid black; border-collapse: collapse; max-width: 100%;'>";
            $input_type = gettype($input);
            switch ($input_type) {
                case "boolean":
                    $return_value .= "<tr><td style='border: 1px solid black;'>$input_type</td><td style='border: 1px solid black;'>" .
                        ($input ? "true" : "false") . "</td></tr>";
                    break;
                case "integer":
                case "double":
                    $return_value .= "<tr><td style='border: 1px solid black;'>$input_type</td><td style='border: 1px solid black;'>$input</td></tr>";
                    break;
                case "string":
                    $return_value .= "<tr><td style='border: 1px solid black;'>$input_type</td><td style='border: 1px solid black;'><pre>\"$input\"</pre></td></tr>";
                    break;
                case "NULL":
                    $return_value .= "<tr><td style='border: 1px solid black;'>null</td></tr>";
                    break;
                case "array":
                    $return_value .= "<tr><td style='border: 1px solid black;'>$input_type</td><td style='border: 1px solid black;'><table style='border: 1px solid black; border-collapse: collapse;'>";
                    foreach ($input as $key => $value) {
                        $key_name = $key;
                        if (!is_numeric($key)) {
                            $key_name = "\"$key\"";
                        }
                        $return_value .= "<tr><td style='border: 1px solid black;'>$key_name</td><td style='border: 1px solid black;'>" .
                            self::debugToHtmlTable($value, false) . "</td></tr>";
                    }
                    $return_value .= "</table></td></tr>";
                    break;
                case "object":
                    try {
                        $reflection_class = new ReflectionClass($input);
                        $return_value .= "<tr><td style='border: 1px solid black;'>" . $reflection_class->getShortName() .
                            "</td><td style='border: 1px solid black;'><table style='border: 1px solid black; border-collapse: collapse;'>";
                        $properties = $reflection_class->getProperties();
                        foreach ($properties as $property) {
                            $return_value .= "<tr><td style='border: 1px solid black;'>\"" . $property->getName() .
                                "\"</td><td style='border: 1px solid black;'>" .
                                self::debugToHtmlTable($property->getValue($input), false) . "</td></tr>";
                        }
                        $return_value .= "</table></td></tr>";
                    } catch (ReflectionException $refl_ex) {
                        $return_value .= "<tr><td style='border: 1px solid black;'>ReflectionException thrown: " .
                            $refl_ex->getMessage() . "</td></tr>";
                    }
                    break;
                case "resource":
                case "resource (closed)":
                case "unknown type":
                default:
                    try {
                        $return_value .= "<tr><td style='border: 1px solid black;'>$input_type</td><td style='border: 1px solid black;'>$input</td></tr>";
                    } catch (Exception $exception) {
                        $return_value .= "<tr><td style='border: 1px solid black;'>unstringifyable $input_type</td></tr>";
                    }
                    break;
            }
            $return_value .= "</table>";
            if ($doEcho) {
                echo $return_value;
            }
            if ($doDie) {
                die(0);
            }
        }
        return $return_value;
    }
    
    public static function logException(Throwable $thrown, bool $doDie = false) : void {
        $file_handle = fopen(PRJ_PRIVATE_DIR . "log.txt", "a");
        $reflect = new ReflectionClass($thrown);
        if ($file_handle) {
            fwrite($file_handle, (new DateTime())->format(DB_DATETIME_FORMAT) . ": " . $reflect->getShortName() . ": " .
                               $thrown->getMessage() . PHP_EOL);
        }
        $return_string = "<h1 style='color: red;'>" . $reflect->getShortName() . "</h1>";
        $return_string .= "<h3 style='color: red;'>" . $thrown->getMessage() . "</h3>";
        $stack_trace = $thrown->getTraceAsString();
        while ($thrown->getPrevious() instanceof Throwable) {
            $thrown = $thrown->getPrevious();
            $reflect = new ReflectionClass($thrown);
            $return_string .= "Caused by: " . $reflect->getShortName() . ": " . $thrown->getMessage() . "<br/>";
            if ($file_handle) {
                fwrite($file_handle,
                       "\tCaused by: " . $reflect->getShortName() . ": " . $thrown->getMessage() . PHP_EOL);
            }
        }
        if ($file_handle) {
            fwrite($file_handle, "\tStacktrace: " . str_replace("\n", "\n\t\t", $stack_trace) . PHP_EOL);
        }
        $return_string .= "<pre>" . $stack_trace . "</pre>";
        
        fclose($file_handle);
        if (self::$DEBUG_MODE) {
            echo $return_string;
        }
        if ($doDie) {
            die();
        }
    }
    
    public static function exceptionToArray(Throwable $thrown) : array {
        $array = [
            "exceptionClass" => $thrown::class,
            "message" => $thrown->getMessage(),
            "code" => $thrown->getCode()
        ];
        if ($thrown->getPrevious() instanceof Throwable) {
            $array["previous"] = self::exceptionToArray($thrown->getPrevious());
        }
        return $array;
    }
    
    public static function outputException(Throwable $thrown) : void {
        $headers = apache_request_headers();
        if (!empty($headers["Accept"]) && str_contains($headers["Accept"], "application/json")) {
            header("Content-Type: application/json;chrset=UTF-8");
            $array = [
                "error" => true,
                "exception" => self::exceptionToArray($thrown),
                "stacktrace" => $thrown->getTraceAsString()
            ];
            echo json_encode($array, JSON_PRETTY_PRINT);
        } else {
            echo generate_exception_html($thrown);
        }
        $status_code = 500;
        /**
         * Disabling qualified name to import warning because debug file might be loaded before autoloader.
         * @noinspection PhpFullyQualifiedNameUsageInspection
         */
        if ($thrown instanceof \Teacher\GivenCode\Exceptions\RequestException) {
            $status_code = ($thrown->getHttpResponseCode() == 0) ? 500 : $thrown->getHttpResponseCode();
            foreach ($thrown->getHttpHeaders() as $header_name => $header_value) {
                header("$header_name: $header_value");
            }
        }
        http_response_code($status_code);
    }
    
}

function debug(mixed $input, bool $doEcho = true, bool $doDie = false) : ?string {
    $return_value = "<table style='border: 1px solid black; border-collapse: collapse; max-width: 100%;'>";
    $input_type = gettype($input);
    switch ($input_type) {
        case "boolean":
            $return_value .= "<tr><td style='border: 1px solid black;'>$input_type</td><td style='border: 1px solid black;'>" .
                ($input ? "true" : "false") . "</td></tr>";
            break;
        case "integer":
        case "double":
            $return_value .= "<tr><td style='border: 1px solid black;'>$input_type</td><td style='border: 1px solid black;'>$input</td></tr>";
            break;
        case "string":
            $return_value .= "<tr><td style='border: 1px solid black;'>$input_type</td><td style='border: 1px solid black;'><pre>\"$input\"</pre></td></tr>";
            break;
        case "NULL":
            $return_value .= "<tr><td style='border: 1px solid black;'>null</td></tr>";
            break;
        case "array":
            $return_value .= "<tr><td style='border: 1px solid black;'>$input_type</td><td style='border: 1px solid black;'><table style='border: 1px solid black; border-collapse: collapse;'>";
            foreach ($input as $key => $value) {
                $key_name = $key;
                if (!is_numeric($key)) {
                    $key_name = "\"$key\"";
                }
                $return_value .= "<tr><td style='border: 1px solid black;'>$key_name</td><td style='border: 1px solid black;'>" .
                    debug($value, false) . "</td></tr>";
            }
            $return_value .= "</table></td></tr>";
            break;
        case "object":
            try {
                $reflection_class = new ReflectionClass($input);
                $return_value .= "<tr><td style='border: 1px solid black;'>" . $reflection_class->getShortName() .
                    "</td><td style='border: 1px solid black;'><table style='border: 1px solid black; border-collapse: collapse;'>";
                $properties = $reflection_class->getProperties();
                foreach ($properties as $property) {
                    $return_value .= "<tr><td style='border: 1px solid black;'>\"" . $property->getName() .
                        "\"</td><td style='border: 1px solid black;'>" .
                        debug($property->getValue($input), false) . "</td></tr>";
                }
                $return_value .= "</table></td></tr>";
            } catch (ReflectionException $refl_ex) {
                $return_value .= "<tr><td style='border: 1px solid black;'>ReflectionException thrown: " .
                    $refl_ex->getMessage() . "</td></tr>";
            }
            break;
        case "resource":
        case "resource (closed)":
        case "unknown type":
        default:
            try {
                $return_value .= "<tr><td style='border: 1px solid black;'>$input_type</td><td style='border: 1px solid black;'>$input</td></tr>";
            } catch (Exception $exception) {
                $return_value .= "<tr><td style='border: 1px solid black;'>unstringifyable $input_type</td></tr>";
            }
            break;
    }
    $return_value .= "</table>";
    if ($doEcho) {
        echo $return_value;
    }
    if ($doDie) {
        die(0);
    }
    return $return_value;
}


/**
 * TODO: Function documentation
 *
 * @param Throwable $thrown
 * @return string
 *
 * @author Marc-Eric Boury
 * @since  2024-03-16
 */
function generate_exception_html(Throwable $thrown) : string {
    $html_string = "
<div class='error-display'>
<h1 style='color: darkred;'>" . $thrown::class . "</h1><br>
<h3 style='color: darkred;'>" . $thrown->getMessage() . "</h3><br>";
    $stack_trace = $thrown->getTraceAsString();
    while ($thrown->getPrevious() instanceof Throwable) {
        $thrown = $thrown->getPrevious();
        $html_string .= "Caused by: " . $thrown::class . ": " . $thrown->getMessage() . "<br/>";
    }
    
    $html_string .= "<pre>" . $stack_trace . "</pre>";
    $html_string .= "</div>";
    return $html_string;
}

/**
 * TODO: Function documentation
 *
 * @return void
 *
 * @author Marc-Eric Boury
 * @since  2024-03-19
 */
function get_debug_page() : void {
    include PRJ_PAGES_DIR . "debug_page.php";
    die();
}