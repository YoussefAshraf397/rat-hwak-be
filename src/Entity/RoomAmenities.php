<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Common\Collections\Collection;


#[Entity]
#[Table('room_amenities')]
class RoomAmenities
{
    #[Id]
    #[GeneratedValue('AUTO')]
    #[Column]
    private int $id;

    #[Column]
    private string $name;

    #[Column]
    private string $icon;

    #[ManyToMany(targetEntity: Room::class, mappedBy: 'amenities', cascade: ['persist'])]
    private Collection $rooms;

    public function __construct(){
        $this->rooms = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): RoomAmenities
    {
        $this->name = $name;
        return $this;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): RoomAmenities
    {
        $this->icon = $icon;
        return $this;
    }

    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function setRoom(Room $room): RoomAmenities
    {
        $this->rooms->add($room);

        return $this;
    }
}