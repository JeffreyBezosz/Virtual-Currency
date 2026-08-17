<?php

class Database
{
    private string $host;
    private string $databaseName;
    private string $username;
    private string $password;
    private ?PDO $connection = null;

    public function __construct(
        string $host,
        string $databaseName,
        string $username,
        string $password
    ) {
        $this->host = $host;
        $this->databaseName = $databaseName;
        $this->username = $username;
        $this->password = $password;
    }

    public function connect(): PDO
    {
        if ($this->connection === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->databaseName};charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                $this->connection = new PDO(
                    $dsn,
                    $this->username,
                    $this->password,
                    $options
                );
            } catch (PDOException $exception) {
                throw new RuntimeException('Kan geen verbinding maken met de database.');
            }
        }

        return $this->connection;
    }
}
