<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('rooms')]
class Room
{
    #[Id]
    #[GeneratedValue('AUTO')]
    #[Column]
    private int $id;

    #[Column('hotel_id')]
    #[JoinColumn(referencedColumnName: 'id')]
    private int $hotelId;

    #[Column]
    private string $title;

    #[Column(type: Types::TEXT)]
    private string $description;

    #[Column]
    private string $uri;

    #[Column('ratehawk_room_group')]
    private int $roomGroup;

    #[ManyToOne(targetEntity: Hotel::class, cascade: ['persist'], inversedBy: 'rooms')]
    #[JoinColumn(referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Hotel $hotel;

    #[OneToMany(mappedBy: 'room', targetEntity: RoomImage::class, cascade: ['persist', 'remove'])]
    private Collection $images;

    #[ManyToMany(targetEntity: RoomAmenities::class, inversedBy: 'amenities', cascade: ['persist'])]
    #[JoinTable('rooms_amenities')]
    #[JoinColumn(name: 'room_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: 'room_amenities_id', referencedColumnName: 'id')]
    private Collection $amenities;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->amenities = new ArrayCollection();
    }

    public function getAmenities(): Collection
    {
        return $this->amenities;
    }

    public function addAmenities(RoomAmenities $amenities): Room
    {
        $amenities->setRoom($this);

        $this->amenities->add($amenities);

        return $this;
    }


    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Room
    {
        $this->description = $description;
        return $this;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(RoomImage $image): Room
    {
        $image->setRoom($this);

        $this->images->add($image);

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getHotelId(): int
    {
        return $this->hotelId;
    }

    public function setHotelId(int $hotelId): Room
    {
        $this->hotelId = $hotelId;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Room
    {
        $this->title = $title;
        return $this;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function setUri(string $uri): Room
    {
        $this->uri = $uri;
        return $this;
    }

    public function getRoomGroup(): int
    {
        return $this->roomGroup;
    }

    public function setRoomGroup(int $roomGroup): Room
    {
        $this->roomGroup = $roomGroup;
        return $this;
    }

    public function getHotel(): Hotel
    {
        return $this->hotel;
    }

    public function setHotel(Hotel $hotel): Room
    {
        $this->hotel = $hotel;
        return $this;
    }
}
