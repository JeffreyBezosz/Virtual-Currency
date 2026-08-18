<?php

class Transaction
{
    private PDO $connection;
    private ?int $id = null;
    private ?int $senderId = null;
    private ?int $receiverId = null;
    private int $amount = 0;
    private string $reason = '';
    private ?string $createdAt = null;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function transfer(): bool
    {
        $this->validateTransferData();
        $this->connection->beginTransaction();

        try {
            $senderBalance = $this->lockUsersAndGetSenderBalance();

            if ($this->amount > $senderBalance) {
                throw new InvalidArgumentException('Je hebt niet genoeg tokens.');
            }

            $this->updateBalances();
            $this->saveTransaction();
            $this->connection->commit();

            return true;
        } catch (Throwable $exception) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }

            throw $exception;
        }
    }

    private function validateTransferData(): void
    {
        if ($this->senderId === null || $this->receiverId === null) {
            throw new InvalidArgumentException('Kies een geldige ontvanger.');
        }

        if ($this->senderId === $this->receiverId) {
            throw new InvalidArgumentException('Je kan geen tokens naar jezelf sturen.');
        }

        if ($this->amount < 1) {
            throw new InvalidArgumentException('Stuur minstens 1 token.');
        }

        $this->reason = trim($this->reason);

        if ($this->reason === '') {
            throw new InvalidArgumentException('Vul een reden in.');
        }

        if (strlen($this->reason) > 255) {
            throw new InvalidArgumentException('De reden mag maximaal 255 tekens lang zijn.');
        }
    }

    private function lockUsersAndGetSenderBalance(): int
    {
        $statement = $this->connection->prepare(
            'SELECT id, balance
             FROM users
             WHERE id IN (:sender_id, :receiver_id)
             FOR UPDATE'
        );

        $statement->execute([
            'sender_id' => $this->senderId,
            'receiver_id' => $this->receiverId,
        ]);

        $users = $statement->fetchAll();
        $senderBalance = null;
        $receiverExists = false;

        foreach ($users as $user) {
            $userId = (int) $user['id'];

            if ($userId === $this->senderId) {
                $senderBalance = (int) $user['balance'];
            }

            if ($userId === $this->receiverId) {
                $receiverExists = true;
            }
        }

        if ($senderBalance === null) {
            throw new InvalidArgumentException('De afzender bestaat niet.');
        }

        if (!$receiverExists) {
            throw new InvalidArgumentException('De ontvanger bestaat niet.');
        }

        return $senderBalance;
    }

    private function updateBalances(): void
    {
        $removeTokens = $this->connection->prepare(
            'UPDATE users
             SET balance = balance - :amount
             WHERE id = :sender_id'
        );

        $removeTokens->execute([
            'amount' => $this->amount,
            'sender_id' => $this->senderId,
        ]);

        $addTokens = $this->connection->prepare(
            'UPDATE users
             SET balance = balance + :amount
             WHERE id = :receiver_id'
        );

        $addTokens->execute([
            'amount' => $this->amount,
            'receiver_id' => $this->receiverId,
        ]);
    }

    private function saveTransaction(): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO transactions (sender_id, receiver_id, amount, reason)
             VALUES (:sender_id, :receiver_id, :amount, :reason)'
        );

        $statement->execute([
            'sender_id' => $this->senderId,
            'receiver_id' => $this->receiverId,
            'amount' => $this->amount,
            'reason' => $this->reason,
        ]);

        $this->id = (int) $this->connection->lastInsertId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getSenderId(): ?int
    {
        return $this->senderId;
    }

    public function setSenderId(?int $senderId): void
    {
        $this->senderId = $senderId;
    }

    public function getReceiverId(): ?int
    {
        return $this->receiverId;
    }

    public function setReceiverId(?int $receiverId): void
    {
        $this->receiverId = $receiverId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function setReason(string $reason): void
    {
        $this->reason = $reason;
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
