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
#[Table('room_images')]
class RoomImage
{
    #[Id]
    #[GeneratedValue('AUTO')]
    #[Column]
    private int $id;

    #[Column('room_id')]
    #[JoinColumn(referencedColumnName: 'id')]
    private int $roomId;

    #[Column('image_sort')]
    private int $imageSort;

    #[Column]
    private string $image;

    #[Column]
    private string $alt;

    #[ManyToOne(targetEntity: Room::class, cascade: ['persist'], inversedBy: 'images')]
    #[JoinColumn(referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Room $room;

    public function getId(): int
    {
        return $this->id;
    }

    public function getRoomId(): int
    {
        return $this->roomId;
    }

    public function setRoomId(int $roomId): RoomImage
    {
        $this->roomId = $roomId;
        return $this;
    }

    public function getImageSort(): int
    {
        return $this->imageSort;
    }

    public function setImageSort(int $imageSort): RoomImage
    {
        $this->imageSort = $imageSort;
        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): RoomImage
    {
        $this->image = $image;
        return $this;
    }

    public function getAlt(): string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): RoomImage
    {
        $this->alt = $alt;
        return $this;
    }

    public function getRoom(): Room
    {
        return $this->room;
    }

    public function setRoom(Room $room): RoomImage
    {
        $this->room = $room;
        return $this;
    }

}
