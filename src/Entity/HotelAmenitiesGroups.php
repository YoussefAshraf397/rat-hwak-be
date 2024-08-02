<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[Entity]
#[Table('hotel_amenities_groups')]
#[UniqueConstraint(columns: ['name'])]
class HotelAmenitiesGroups
{
    #[Id]
    #[GeneratedValue('AUTO')]
    #[Column]
    private int $id;

    #[Column]
    private string $name;

    #[OneToMany(mappedBy: 'group', targetEntity: HotelAmenities::class, cascade: ['persist', 'remove'])]
    private Collection $amenities;

    public function __construct(){
        $this->amenities = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): HotelAmenitiesGroups
    {
        $this->name = $name;
        return $this;
    }

    public function getAmenities(): Collection
    {
        return $this->amenities;
    }

    public function setAmenities(HotelAmenities $amenities): HotelAmenitiesGroups
    {
        $amenities->setGroup($this);

        $this->amenities->add($amenities);


        return $this;
    }


}
