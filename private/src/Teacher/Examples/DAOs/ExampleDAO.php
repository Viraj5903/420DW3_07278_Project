<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project ExampleDAO.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-16
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\Examples\DAOs;

use PDO;
use Teacher\Examples\DTOs\ExampleDTO;
use Teacher\GivenCode\Abstracts\IDAO;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Teacher\GivenCode\Services\DBConnectionService;

/**
 * Example DAO-type class.
 *
 * @author Marc-Eric Boury
 * @since  2024-03-16
 */
class ExampleDAO implements IDAO {
    
    // <editor-fold defaultstate="collapsed" desc="QUERY STRING CONSTANTS">
    
    private const GET_QUERY = "SELECT * FROM `" . ExampleDTO::TABLE_NAME .
    "` WHERE `id` = :id AND `deleted_at` IS NULL ;";
    private const GET_QUERY_WITH_DELETED = "SELECT * FROM `" . ExampleDTO::TABLE_NAME . "` WHERE `id` = :id ;";
    private const CREATE_QUERY = "INSERT INTO `" . ExampleDTO::TABLE_NAME .
    "` (`dayOfTheWeek`, `description`) VALUES (:dayOfWeek, :desc) ;";
    private const UPDATE_QUERY = "UPDATE `" . ExampleDTO::TABLE_NAME .
    "` SET `dayOfTheWeek` = :dayOfWeek, `description` = :desc WHERE `id` = :id ;";
    private const PSEUDO_DELETE_QUERY = "UPDATE `" . ExampleDTO::TABLE_NAME .
    "` SET `deleted_at` = :dateDeleted WHERE `id` = :id ;";
    private const REAL_DELETE_QUERY = "DELETE FROM `" . ExampleDTO::TABLE_NAME . "` WHERE `id` = :id ;";
    
    // </editor-fold>
    
    /**
     * Constructor
     */
    public function __construct() {}
    
    /**
     * TODO: Function documentation
     *
     * @param bool $includeDeleted
     * @return ExampleDTO[]
     * @throws RuntimeException
     * @throws ValidationException
     *
     * @author Marc-Eric Boury
     * @since  2024-03-21
     */
    public function getAll(bool $includeDeleted = false) : array {
        $connection = DBConnectionService::getConnection();
        if ($includeDeleted) {
            $statement = $connection->prepare("SELECT * FROM " . ExampleDTO::TABLE_NAME);
        } else {
            $statement = $connection->prepare("SELECT * FROM " . ExampleDTO::TABLE_NAME . " WHERE `deleted_at` IS NULL ;");
        }
        $statement->execute();
        $results_array = $statement->fetchAll(PDO::FETCH_ASSOC);
        $object_array = [];
        foreach ($results_array as $result) {
            $object_array[] = ExampleDTO::fromDbArray($result);
        }
        return $object_array;
    }
    
    /**
     * {@inheritDoc}
     * Specialized for {@see ExampleDTO} DTO objects.
     *
     * @param int  $id             The identifier value of the {@see ExampleDTO} record to retrieve.
     * @param bool $includeDeleted [OPTIONAL] Whether to include pseudo-deleted records when obtaining one. Defaults to
     *                             false.
     * @return ExampleDTO|null The created {@see ExampleDTO} instance or null if no record was found for the specified
     *                             id.
     *
     * @throws ValidationException If a {@see ValidationException} is thrown when creating the updated instance.
     * @throws RuntimeException If no record is found for the specified id.
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function getById(int $id, bool $includeDeleted = false) : ?ExampleDTO {
        $connection = DBConnectionService::getConnection();
        if ($includeDeleted) {
            $statement = $connection->prepare(self::GET_QUERY_WITH_DELETED);
        } else {
            $statement = $connection->prepare(self::GET_QUERY);
        }
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();
        
        $array = $statement->fetch(PDO::FETCH_ASSOC);
        if (is_bool($array) && !$array) {
            // failed fetch
            throw new RuntimeException("No record found for id# [$id].");
        }
        return ExampleDTO::fromDbArray($array);
    }
    
    /**
     * {@inheritDoc}
     * Specialized for {@see ExampleDTO} DTO objects.
     *
     * @param ExampleDTO $dto The {@see ExampleDTO} instance to create the record of.
     * @return ExampleDTO The updated {@see ExampleDTO} instance.
     * @throws ValidationException If the <code>$dto</code> object parameter is not an {@see ExampleDTO} instance or if
     *                        a {@see ValidationException} is thrown when creating the updated instance.
     * @throws RuntimeException If no record of the created instance is found to create the updated instance.
     *
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function create(object $dto) : ExampleDTO {
        if (!($dto instanceof ExampleDTO)) {
            throw new ValidationException("Passed dto object is not an instance of [" . ExampleDTO::class . "].");
        }
        $dto->validateForDbCreation();
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":dayOfWeek", $dto->getDayOfTheWeek()->value, PDO::PARAM_STR);
        $statement->bindValue(":desc", $dto->getDescription(), PDO::PARAM_STR);
        $statement->execute();
        $new_id = (int) $connection->lastInsertId();
        
        // TODO: do something in the case that getById returns null. It shouldn't happen, but its a case to handle.
        return $this->getById($new_id);
    }
    
    /**
     * {@inheritDoc}
     * Specialized for {@see ExampleDTO} DTO objects.
     *
     * @param ExampleDTO $dto  The {@see ExampleDTO} instance to update the record of.
     * @return ExampleDTO The updated {@see ExampleDTO} instance.
     * @throws RuntimeException If no record of the updated instance is found to create the updated instance.
     * @throws ValidationException If the <code>$dto</code> object parameter is not an {@see ExampleDTO} instance or if
     *                         a {@see ValidationException} is thrown when creating the updated instance.
     *
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function update(object $dto) : ExampleDTO {
        if (!($dto instanceof ExampleDTO)) {
            throw new ValidationException("Passed dto object is not an instance of [" . ExampleDTO::class . "].");
        }
        $dto->validateForDbUpdate();
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":dayOfWeek", $dto->getDayOfTheWeek()->value, PDO::PARAM_STR);
        $statement->bindValue(":desc", $dto->getDescription(), PDO::PARAM_STR);
        $statement->bindValue(":id", $dto->getPrimaryKeyValue(), PDO::PARAM_INT);
        $statement->execute();
        
        // TODO: do something in the case that getById returns null. It shouldn't happen, but its a case to handle.
        return $this->getById($dto->getPrimaryKeyValue());
    }
    
    /**
     * {@inheritDoc}
     * Specialized for {@see ExampleDTO} DTO objects.
     *
     * @param ExampleDTO $dto         The {@see ExampleDTO} instance to delete the record of.
     * @param bool       $realDeletes [OPTIONAL] whether to perform a real record deletion or just mark it with a deletion date. Defaults to <code>false</code>.
     * @return void
     * @throws ValidationException If the <code>$dto</code> object parameter is not an {@see ExampleDTO} instance.
     *
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function delete(object $dto, bool $realDeletes = false) : void {
        if (!($dto instanceof ExampleDTO)) {
            throw new ValidationException("Passed dto object is not an instance of [" . ExampleDTO::class . "].");
        }
        $dto->validateForDbDelete();
        $this->deleteById($dto->getPrimaryKeyValue());
    }
    
    /**
     * {@inheritDoc}
     * Specialized for {@see ExampleDTO} DTO objects.
     *
     * @param int  $id          The identifier value of the {@see ExampleDTO} entity to delete
     * @param bool $realDeletes [OPTIONAL] whether to perform a real record deletion or just mark it with a deletion
     *                          date. Defaults to <code>false</code>.
     * @return void
     *
     * @throws RuntimeException
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function deleteById(int $id, bool $realDeletes = false) : void {
        $connection = DBConnectionService::getConnection();
        if ($realDeletes) {
            $statement = $connection->prepare(self::REAL_DELETE_QUERY);
        } else {
            $statement = $connection->prepare(self::PSEUDO_DELETE_QUERY);
            $statement->bindValue(":dateDeleted", (new \DateTime())->format(DB_DATETIME_FORMAT), PDO::PARAM_STR);
        }
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();
    }
}