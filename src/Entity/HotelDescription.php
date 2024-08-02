<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('hotel_description')]
class HotelDescription
{
    #[Id]
    #[GeneratedValue('AUTO')]
    #[Column]
    private int $id;

    #[Column('description_group_id')]
    #[JoinColumn('id')]
    private int $descriptionGroupId;

    #[Column(type: Types::TEXT)]
    private string $text;

    #[ManyToOne(targetEntity: HotelDescriptionGroup::class, cascade: ['persist'], inversedBy: 'descriptions')]
    #[JoinColumn(name: 'description_group_id', referencedColumnName: 'id')]
    private HotelDescriptionGroup $descriptionGroup;

    #[ManyToOne(targetEntity: Hotel::class, cascade: ['persist'], inversedBy: 'descriptions')]
    #[JoinColumn(referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Hotel $hotel;


    public function getHotel(): Hotel
    {
        return $this->hotel;
    }

    public function setHotel(Hotel $hotel): HotelDescription
    {
        $this->hotel = $hotel;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function getDescriptionGroupId(): int
    {
        return $this->descriptionGroupId;
    }

    public function setDescriptionGroupId(int $descriptionGroupId): HotelDescription
    {
        $this->descriptionGroupId = $descriptionGroupId;
        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): HotelDescription
    {
        $this->text = $text;
        return $this;
    }

    public function getDescriptionGroup(): HotelDescriptionGroup
    {
        return $this->descriptionGroup;
    }

    public function setDescriptionGroup(HotelDescriptionGroup $descriptionGroup): HotelDescription
    {
        $this->descriptionGroup = $descriptionGroup;
        return $this;
    }

}
