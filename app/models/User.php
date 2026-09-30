<?php
class User extends Model
{
    public function __construct() { parent::__construct('users'); }

    public function findByUsername(string $username): ?array
    {
        foreach ($this->readAll() as $row) {
            if ($row['username'] === $username) return $row;
        }
        return null;
    }

    public function verifyPassword(string $plain, string $hashed): bool
    {
        return password_verify($plain, $hashed);
    }
}
