<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project ExampleService.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-16
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\Examples\Services;

use Teacher\Examples\DAOs\ExampleDAO;
use Teacher\Examples\DTOs\ExampleDTO;
use Teacher\Examples\Enumerations\DaysOfWeekEnum;
use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Teacher\GivenCode\Services\DBConnectionService;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-16
 */
class ExampleService implements IService {
    private ExampleDAO $exampleDao;
    
    public function __construct() {
        $this->exampleDao = new ExampleDAO();
    }
    
    /**
     * TODO: Function documentation
     *
     * @return ExampleDTO[]
     *
     * @throws RuntimeException
     * @throws ValidationException
     * @author Marc-Eric Boury
     * @since  2024-03-26
     */
    public function getAllExamples() : array {
        return $this->exampleDao->getAll();
    }
    
    /**
     * TODO: Function documentation
     *
     * @param int $id
     * @return ExampleDTO|null
     * @throws RuntimeException
     * @throws ValidationException
     *
     * @author Marc-Eric Boury
     * @since  2024-03-28
     */
    public function getById(int $id) : ?ExampleDTO {
        return $this->exampleDao->getById($id);
    }
    
    /**
     * TODO: Function documentation
     *
     * @param DaysOfWeekEnum $dayOfTheWeek
     * @param string         $description
     * @return ExampleDTO
     * @throws RuntimeException
     * @throws ValidationException
     *
     * @author Marc-Eric Boury
     * @since  2024-03-28
     */
    public function create(DaysOfWeekEnum $dayOfTheWeek, string $description) : ExampleDTO {
        $instance = new ExampleDTO();
        $instance->setDayOfTheWeek($dayOfTheWeek);
        $instance->setDescription($description);
        //$instance = ExampleDTO::fromValues($dayOfTheWeek, $description);
        return $this->exampleDao->create($instance);
    }
    
    /**
     * TODO: Function documentation
     *
     * @param int            $id
     * @param DaysOfWeekEnum $dayOfTheWeek
     * @param string         $description
     * @return ExampleDTO
     * @throws RuntimeException
     * @throws ValidationException
     *
     * @author Marc-Eric Boury
     * @since  2024-03-28
     */
    public function update(int $id, DaysOfWeekEnum $dayOfTheWeek, string $description) : ExampleDTO {
        $connection = DBConnectionService::getConnection();
        $connection->beginTransaction();
        $instance = $this->exampleDao->getById($id);
        $instance->setDayOfTheWeek($dayOfTheWeek);
        $instance->setDescription($description);
        $instance = $this->exampleDao->update($instance);
        $connection->commit();
        return $instance;
    }
    
    /**
     * TODO: Function documentation
     *
     * @param int  $id
     * @param bool $optHardDelete
     * @return void
     * @throws RuntimeException
     * @author Marc-Eric Boury
     * @since  2024-03-28
     */
    public function delete(int $id, bool $optHardDelete = false) : void {
        $this->exampleDao->deleteById($id, $optHardDelete);
    }
    
}