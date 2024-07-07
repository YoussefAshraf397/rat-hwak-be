<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('hotel_images')]
class HotelImage
{
    #[Id]
    #[GeneratedValue('AUTO')]
    #[Column]
    private int $id;

    #[Column('hotel_id')]
    #[JoinColumn(referencedColumnName: 'id')]
    private int $hotelId;

    #[Column('image_sort')]
    private int $imageSort;

    #[Column]
    private string $image;

    #[Column]
    private string $alt;

    #[ManyToOne(targetEntity: Hotel::class, cascade: ['persist'], inversedBy: 'images')]
    #[JoinColumn(referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Hotel $hotel;

    public function getId(): int
    {
        return $this->id;
    }

    public function getHotelId(): int
    {
        return $this->hotelId;
    }

    public function setHotelId(int $hotelId): HotelImage
    {
        $this->hotelId = $hotelId;
        return $this;
    }

    public function getImageSort(): int
    {
        return $this->imageSort;
    }

    public function setImageSort(int $imageSort): HotelImage
    {
        $this->imageSort = $imageSort;
        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): HotelImage
    {
        $this->image = $image;
        return $this;
    }

    public function getAlt(): string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): HotelImage
    {
        $this->alt = $alt;
        return $this;
    }

    public function getHotel(): Hotel
    {
        return $this->hotel;
    }

    public function setHotel(Hotel $hotel): HotelImage
    {
        $this->hotel = $hotel;
        return $this;
    }
}
