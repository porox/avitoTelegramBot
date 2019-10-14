<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdvertisementRepository")
 */
class Advertisement
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $price;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $link;

    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(type="datetime", nullable=true, options={"comment":"Дата и время обновления"})
     */
    protected $createdAt;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $hash;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="SearchQuery", inversedBy="advertisment")
     * @ORM\JoinTable(name="adv_sq")
     */
    protected $searchQuery;

    public function __construct()
    {
        $this->searchQuery = new ArrayCollection();
        $this->createdAt = new \DateTime();
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
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * @param string $price
     */
    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
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
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    /**
     * @return Collection
     */
    public function getSearchQuery(): Collection
    {
        return $this->searchQuery;
    }

    /**
     * @param Collection $searchQuery
     */
    public function setSearchQuery(Collection $searchQuery): void
    {
        $this->searchQuery = $searchQuery;
    }



}
