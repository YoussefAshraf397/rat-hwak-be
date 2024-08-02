<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[Entity]
#[Table('hotel_amenities')]
#[UniqueConstraint(columns: ['group_id', 'name'])]
class HotelAmenities
{
    #[Id]
    #[GeneratedValue('AUTO')]
    #[Column]
    private int $id;

    #[Column]
    private string $name;

    #[Column('group_id')]
    #[JoinColumn('id')]
    private int $groupId;

    #[ManyToMany(targetEntity: Hotel::class, mappedBy: 'amenities')]
    private Collection $hotels;

    #[ManyToOne(targetEntity: HotelAmenitiesGroups::class, cascade: ['persist'], inversedBy: 'amenities')]
    #[JoinColumn(referencedColumnName: 'id', onDelete: 'CASCADE')]
    private HotelAmenitiesGroups $group;

    public function __construct()
    {
        $this->hotels = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): HotelAmenities
    {
        $this->name = $name;
        return $this;
    }

    public function getGroupId(): int
    {
        return $this->groupId;
    }

    public function setGroupId(int $groupId): HotelAmenities
    {
        $this->groupId = $groupId;
        return $this;
    }

    public function getHotels(): Collection
    {
        return $this->hotels;
    }

    public function setHotel(Hotel $hotel): HotelAmenities
    {
        $this->hotels->add($hotel);

        return $this;
    }

    public function getGroup(): HotelAmenitiesGroups
    {
        return $this->group;
    }

    public function setGroup(HotelAmenitiesGroups $group): HotelAmenities
    {
        $this->group = $group;
        return $this;
    }
}
