<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity]
#[Table('hotel_description_groups')]
class HotelDescriptionGroup
{
    #[Id]
    #[GeneratedValue('AUTO')]
    #[Column]
    private int $id;

    #[Column]
    private string $title;

    #[Column]
    private string $icon;

    #[OneToMany(mappedBy: 'descriptionGroup', targetEntity: HotelDescription::class, cascade: ['persist', 'remove'])]
    private Collection $descriptions;

    public function __construct()
    {
        $this->descriptions = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): HotelDescriptionGroup
    {
        $this->title = $title;
        return $this;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): HotelDescriptionGroup
    {
        $this->icon = $icon;
        return $this;
    }

    public function getDescriptions(): Collection
    {
        return $this->descriptions;
    }

    public function addDescription(HotelDescription $description): HotelDescriptionGroup
    {
        $description->setDescriptionGroup($this);
        $this->descriptions->add($description);
        return $this;
    }


}
