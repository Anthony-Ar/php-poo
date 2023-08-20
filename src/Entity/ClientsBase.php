<?php
namespace App\Entity;

use App\Repository\Database;

class ClientsBase extends Database
{
    private int $id;
    private string $name;
    private bool $seller = false;
    private int $discordTag;
    private int $discordUid;

    public function __construct()
    {
        parent::__construct('clients');
    }

    public function getId(): int 
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $value): void
    {
        $this->name = $value;
    }

    public function getDiscordTag(): int
    {
        return $this->discordTag;
    }

    public function setDiscordTag(int $value): void
    {
        $this->discordTag = $value;
    }

    public function getDiscordUid(): int
    {
        return $this->discordUid;
    }

    public function setDiscordUid(int $value): void
    {
        $this->discordUid = $value;
    }
    public function isSeller(): bool
    {
        return $this->seller;
    }

    public function setSeller(bool $value): void
    {
        $this->seller = $value;
    }
}