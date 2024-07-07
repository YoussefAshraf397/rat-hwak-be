<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[Entity]
#[Table('locations')]
#[UniqueConstraint(columns: ['rate_hawk_id'])]
class Location
{
    #[Id]
    #[GeneratedValue('AUTO')]
    #[Column]
    private int $id;

    #[Column('rate_hawk_id')]
    private int $rateHawkId;

    #[Column]
    private string $title;

    #[Column('country_name')]
    private string $countryName;

    #[Column]
    private string $type;

    #[Column]
    private string $longitude;

    #[Column]
    private string $latitude;

    #[Column(name: 'country_code', nullable: true)]
    private ?string $countryCode;

    #[OneToMany(mappedBy: 'location', targetEntity: Hotel::class, cascade: ['persist', 'remove'])]
    private Collection $hotels;

    public function __construct()
    {
        $this->hotels = new ArrayCollection();
    }


    public function getLongitude(): string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): Location
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getLatitude(): string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): Location
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getHotels(): Collection
    {
        return $this->hotels;
    }

    public function setHotels(Hotel $hotel): Location
    {
        $hotel->setLocation($this);
        $this->hotels->add($hotel);

        return $this;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getRateHawkId(): int
    {
        return $this->rateHawkId;
    }

    public function setRateHawkId(int $rateHawkId): Location
    {
        $this->rateHawkId = $rateHawkId;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Location
    {
        $this->title = $title;
        return $this;
    }

    public function getCountryName(): string
    {
        return $this->countryName;
    }

    public function setCountryName(string $countryName): Location
    {
        $this->countryName = $countryName;
        return $this;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): Location
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): Location
    {
        $this->type = $type;
        return $this;
    }


}
