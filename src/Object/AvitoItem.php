<?php
/**
 * Created by PhpStorm.
 * User: yury
 * Date: 2019-10-08
 * Time: 23:41
 */

namespace App\Object;

/**
 * Class AvitoItem
 *
 * @package App\Object
 */
class AvitoItem
{

    /**
     * @var integer
     */
    protected $id;
    /**
     * @var string
     */
    protected $title;
    /**
     * @var float
     */
    protected $price;
    /**
     * @var string
     */
    protected $link;
    /**
     * @var string
     */
    protected $descr;

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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
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
     * @return string
     */
    public function getDescr(): string
    {
        return $this->descr;
    }

    /**
     * @param string $descr
     */
    public function setDescr(string $descr): void
    {
        $this->descr = $descr;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->title . PHP_EOL . "Описание: " . $this->descr . PHP_EOL . "Цена: " . $this->price . PHP_EOL .
            "Ссылка: " . $this->link . PHP_EOL;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return md5($this->id . '_' . $this->title . '_' . $this->price);
    }

}