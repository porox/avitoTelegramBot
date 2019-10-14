<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TelegramUserRepository")
 */
class TelegramUser
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $telegramChatId;
    /**
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    protected $telegramInfo;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default":false})
     */
    protected $blocked;

    /**
     * Many Groups have Many Users.
     *
     * @ORM\ManyToMany(targetEntity="SearchQuery", mappedBy="users")
     */
    protected $searchQuery;

    public function __construct()
    {
        $this->searchQuery = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTelegramChatId(): string
    {
        return $this->telegramChatId;
    }

    /**
     * @param string $telegramChatId
     */
    public function setTelegramChatId(string $telegramChatId): void
    {
        $this->telegramChatId = $telegramChatId;
    }

    /**
     * @return array
     */
    public function getTelegramInfo(): array
    {
        return $this->telegramInfo;
    }

    /**
     * @param array $telegramInfo
     */
    public function setTelegramInfo(array $telegramInfo): void
    {
        $this->telegramInfo = $telegramInfo;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    /**
     * @param bool $blocked
     */
    public function setBlocked(bool $blocked): void
    {
        $this->blocked = $blocked;
    }

    /**
     * @return mixed
     */
    public function getSearchQuery()
    {
        return $this->searchQuery;
    }

    /**
     * @param mixed $searchQuery
     */
    public function setSearchQuery($searchQuery): void
    {
        $this->searchQuery = $searchQuery;
    }
}
