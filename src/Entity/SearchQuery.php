<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SearchQueryRepository")
 */
class SearchQuery
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
    protected $query;
    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(type="datetime", nullable=true, options={"comment":"Дата и время обновления"})
     */
    protected $createdAt;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="TelegramUser", inversedBy="searchQuery")
     * @ORM\JoinTable(name="sq_usr")
     */
    protected $users;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default":false})
     */
    protected $blocked;

    /**
     * Many Groups have Many Users.
     *
     * @ORM\ManyToMany(targetEntity="Advertisement", mappedBy="searchQuery")
     */
    protected $advertisment;

    public function __construct()
    {
        $this->advertisment = new ArrayCollection();
        $this->users        = new ArrayCollection();
        $this->createdAt    = new \DateTime();

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
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @param string $query
     */
    public function setQuery(string $query): void
    {
        $this->query = $query;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return Collection
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @param Collection $users
     */
    public function setUsers(Collection $users): void
    {
        $this->users = $users;
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
    public function getAdvertisment()
    {
        return $this->advertisment;
    }

    /**
     * @param mixed $advertisment
     */
    public function setAdvertisment($advertisment): void
    {
        $this->advertisment = $advertisment;
    }


}