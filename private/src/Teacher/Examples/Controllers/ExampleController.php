<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project ExampleController.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-16
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\Examples\Controllers;

use JsonException;
use Teacher\Examples\Enumerations\DaysOfWeekEnum;
use Teacher\Examples\Services\ExampleService;
use Teacher\GivenCode\Abstracts\AbstractController;
use Teacher\GivenCode\Exceptions\RequestException;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-16
 */
class ExampleController extends AbstractController {
    private ExampleService $exampleService;
    
    public function __construct() {
        parent::__construct();
        $this->exampleService = new ExampleService();
    }
    
    public function get() : void {
        /*
         * This example GET request handler is designed to retrieve a record of an example entity from the database
         * and return it to the client for handling client-side.
         * It expects an exampleId request parameter (so most probably a x-www-form-urlencoded request content type)
         * and returns the data as JSON.
         */
        
        if (empty($_REQUEST["exampleId"])) {
            throw new RequestException("Bad request: required parameter [exampleID] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["exampleId"])) {
            throw new RequestException("Bad request: parameter [exampleID] value [" . $_REQUEST["exampleId"] .
                                       "] is not numeric.", 400);
        }
        $int_id = (int) $_REQUEST["exampleId"];
        $instance = $this->exampleService->getById($int_id);
        header("Content-Type: application/json;charset=UTF-8");
        echo $instance->toJson();
    }
    
    public function post() : void {
        /*
         * This example POST request handler is designed to create a new example entity record in the database
         * and return it to the client for handling client-side.
         * NOTE: I'm using POST for creation instead of PUT because PUT requests are supposed to be idempotent (would
         * only affect a single resource by creating if not existing or modifying if existing), while POST is not
         * idempotent (would create new entities each time) which is the current behavior of this application.
         *
         * It expects the required data attributes for an example entity as x-www-form-urlencoded request data (parsed
         * automatically by PHP) and returns the created record data as JSON.
         */
        
        if (empty($_REQUEST["dayOfTheWeek"])) {
            throw new RequestException("Bad request: required parameter [dayOfTheWeek] not found in the request.", 400);
        }
        if (empty($_REQUEST["description"])) {
            throw new RequestException("Bad request: required parameter [description] not found in the request.", 400);
        }
        $day_of_week = DaysOfWeekEnum::getFromStringValue($_REQUEST["dayOfTheWeek"]);
        
        // NOTE: no need for validation of the string lengths here, as that is done by the setter methods of the
        // ExampleDTO class used when creating an ExampleDTO instance in the creation method of ExampleService.
        
        $instance = $this->exampleService->create($day_of_week, $_REQUEST["description"]);
        header("Content-Type: application/json;charset=UTF-8");
        echo $instance->toJson();
    }
    
    public function put() : void {
        /*
         * This example PUT request handler is designed to update an example entity record in the database
         * and return it to the client for handling client-side.
         *
         * NOTE: PHP does not always parse PUT and DELETE requests. It must be done manually by reading
         * the PHP://input data stream.
         *
         * It expects the required data attributes for an example entity as well as the ID as JSON request data  and
         * returns the updated record data also as JSON.
         */
        
        // As stated, we need to manually parse the input content of PUT and DELETE requests.
        // For this PUT update example, that is application/json content so we use json_decode()
        $request_contents = file_get_contents('php://input');
        try {
            $_REQUEST = json_decode($request_contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $json_excep) {
            throw new RequestException("Invalid request contents format. Valid JSON is required.", 400, [], 400,
                                       $json_excep);
        }
        
        
        if (empty($_REQUEST["id"])) {
            throw new RequestException("Bad request: required parameter [id] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["id"])) {
            throw new RequestException("Bad request: parameter [id] value [" . $_REQUEST["id"] .
                                       "] is not numeric.", 400);
        }
        if (empty($_REQUEST["dayOfTheWeek"])) {
            throw new RequestException("Bad request: required parameter [dayOfTheWeek] not found in the request.", 400);
        }
        if (empty($_REQUEST["description"])) {
            throw new RequestException("Bad request: required parameter [description] not found in the request.", 400);
        }
        $day_of_week = DaysOfWeekEnum::getFromStringValue($_REQUEST["dayOfTheWeek"]);
        
        // NOTE: no need for validation of the string lengths here, as that is done by the setter methods of the
        // ExampleDTO class used when creating an ExampleDTO instance in the creation method of ExampleService.
        
        $int_id = (int) $_REQUEST["id"];
        $instance = $this->exampleService->update($int_id, $day_of_week, $_REQUEST["description"]);
        header("Content-Type: application/json;charset=UTF-8");
        echo $instance->toJson();
    }
    
    public function delete() : void {
        /*
         * This example DELETE request handler is designed to delete an example entity record in the database..
         * NOTE: PHP does not always parse PUT and DELETE requests. It must be done manually by reading
         * the PHP://input data stream.
         *
         * It expects the ID of an example entity as urlencoded request data and returns nothing in the response.
         */
        
        // As stated, we need to manually parse the input content of PUT and DELETE requests.
        // For this DELETE deletion example, that is application/x-www-form-urlencoded content.
        // We need to use parse_str() function to decode urlencoded string data instead of the json_decode() used
        // for JSON data.
        $request_contents = file_get_contents('php://input');
        parse_str($request_contents, $_REQUEST);
        
        
        if (empty($_REQUEST["id"])) {
            throw new RequestException("Bad request: required parameter [id] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["id"])) {
            throw new RequestException("Bad request: parameter [id] value [" . $_REQUEST["id"] .
                                       "] is not numeric.", 400);
        }
        $int_id = (int) $_REQUEST["id"];
        $this->exampleService->delete($int_id);
        header("Content-Type: application/json;charset=UTF-8");
        http_response_code(204);
    }
}