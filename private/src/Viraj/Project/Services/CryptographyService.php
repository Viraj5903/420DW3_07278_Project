<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project CryptographyService.php
 *
 * @author Viraj Patel
 * @since 2024-04-02
 */

namespace Viraj\Project\Services;

use Teacher\GivenCode\Exceptions\ValidationException;

/**
 * Service class for the hashing and validation of user passwords.
 */
class CryptographyService {
    
    private const ALGORITHM = PASSWORD_BCRYPT;
    private const BLOWFISH_MAX_PW_LENGTH = 72;
    
    public function __construct() {}
    
    /**
     * Encrypts a password through the one-way hash Blowfish algorithm.
     * Provided passwords to hash must have a maximum length of 72 characters.
     * The returned password hash string can have a length of up to 255 characters.
     *
     * @param string $cleanPassword The password to hash.
     * @return string The hashed password.
     *
     * @throws ValidationException if the password to hash is too long.
     */
    public function hashPassword(string $cleanPassword) : string {
        if (mb_strlen($cleanPassword) > self::BLOWFISH_MAX_PW_LENGTH) {
            throw new ValidationException("Provided password is too long for hashing.");
        }
        return password_hash($cleanPassword, self::ALGORITHM);
    }
    
    /**
     * Compares an unencrypted password to a hashed password value.
     * Provided passwords to validate must have a maximum length of 72 characters.
     * If the unencrypted password matches the hashed one, the method returns <code>true</code>.
     * If the unencrypted password does NOT match the hashed one,the method returns <code>false</code>.
     *
     * @param string $cleanPassword  The unencrypted password to verify.
     * @param string $hashedPassword The password hash to compare against.
     * @return bool if the unencrypted and hashed passwords match, <code>true</code>,otherwise <code>false</code>.
     *
     * @throws ValidationException if the password to hash is too long.
     */
    public function comparePassword(string $cleanPassword, string $hashedPassword) : bool {
        if (mb_strlen($cleanPassword) > self::BLOWFISH_MAX_PW_LENGTH) {
            throw new ValidationException("Provided password is too long for hashing.");
        }
        return password_verify($cleanPassword, $hashedPassword);
    }
}