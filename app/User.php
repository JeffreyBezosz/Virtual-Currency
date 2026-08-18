<?php

namespace App;

use PDO;

class User
{
    private PDO $connection;
    private ?int $id = null;
    private string $firstName = '';
    private string $lastName = '';
    private string $email = '';
    private string $passwordHash = '';
    private int $balance = 10;
    private ?string $createdAt = null;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function emailExists(string $email): bool
    {
        $statement = $this->connection->prepare(
            'SELECT id FROM users WHERE email = :email LIMIT 1'
        );

        $statement->execute([
            'email' => $email,
        ]);

        return $statement->fetchColumn() !== false;
    }

    public function findByEmail(string $email): bool
    {
        $statement = $this->connection->prepare(
            'SELECT id, first_name, last_name, email, password, balance, created_at
             FROM users
             WHERE email = :email
             LIMIT 1'
        );

        $statement->execute([
            'email' => $email,
        ]);

        $userData = $statement->fetch();

        if ($userData === false) {
            return false;
        }

        $this->id = (int) $userData['id'];
        $this->firstName = $userData['first_name'];
        $this->lastName = $userData['last_name'];
        $this->email = $userData['email'];
        $this->passwordHash = $userData['password'];
        $this->balance = (int) $userData['balance'];
        $this->createdAt = $userData['created_at'];

        return true;
    }

    public function findById(int $id): bool
    {
        $statement = $this->connection->prepare(
            'SELECT id, first_name, last_name, email, password, balance, created_at
             FROM users
             WHERE id = :id
             LIMIT 1'
        );

        $statement->execute([
            'id' => $id,
        ]);

        $userData = $statement->fetch();

        if ($userData === false) {
            return false;
        }

        $this->id = (int) $userData['id'];
        $this->firstName = $userData['first_name'];
        $this->lastName = $userData['last_name'];
        $this->email = $userData['email'];
        $this->passwordHash = $userData['password'];
        $this->balance = (int) $userData['balance'];
        $this->createdAt = $userData['created_at'];

        return true;
    }

    public function search(string $query, int $excludedUserId): array
    {
        if (strlen($query) < 2) {
            return [];
        }

        $searchTerm = '%' . $query . '%';
        $statement = $this->connection->prepare(
            'SELECT id, first_name, last_name, email
             FROM users
             WHERE id != :excluded_user_id
               AND (
                    first_name LIKE :first_name
                    OR last_name LIKE :last_name
                    OR email LIKE :email
               )
             ORDER BY first_name, last_name
             LIMIT 10'
        );

        $statement->execute([
            'excluded_user_id' => $excludedUserId,
            'first_name' => $searchTerm,
            'last_name' => $searchTerm,
            'email' => $searchTerm,
        ]);

        return $statement->fetchAll();
    }

    public function create(): bool
    {
        $statement = $this->connection->prepare(
            'INSERT INTO users (first_name, last_name, email, password, balance)
             VALUES (:first_name, :last_name, :email, :password, :balance)'
        );

        $created = $statement->execute([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'password' => $this->passwordHash,
            'balance' => $this->balance,
        ]);

        if ($created) {
            $this->id = (int) $this->connection->lastInsertId();
        }

        return $created;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): void
    {
        $this->balance = $balance;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
