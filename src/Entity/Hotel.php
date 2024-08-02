<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[Entity]
#[Table('hotels')]
#[UniqueConstraint(columns: ["uri"])]
#[Index(columns: ["location_id", "star_rating", "id"], name: 'hotels_star_rating_search')]
class Hotel
{
    #[Id]
    #[GeneratedValue('AUTO')]
    #[Column]
    private int $id;

    #[Column(name: 'location_id', nullable: true)]
    #[JoinColumn(referencedColumnName: 'id')]
    private ?int $locationId;

    #[Column]
    private string $uri;

    #[Column]
    private string $title;

    #[Column]
    private string $phone;

    #[Column]
    private string $email;

    #[Column('check_in')]
    private string $checkIn;

    #[Column('check_out')]
    private string $checkOut;

    #[Column('star_rating')]
    private int $starRating;

    #[Column(type: Types::STRING, length: 450)]
    private string $address;

    #[Column]
    private string $latitude;

    #[Column]
    private string $longitude;

    #[Column(name: 'additional_information', type: Types::TEXT)]
    private string $additionalInformation;

    #[Column(name: 'client_rating', type: Types::DECIMAL, precision: 5, scale: 1, nullable: true)]
    private ?float $clientRating;

    #[Column(name: 'cleanness_rating', type: Types::DECIMAL, precision: 5, scale: 1, nullable: true)]
    private ?float $cleannessRating;

    #[Column(name: 'location_rating', type: Types::DECIMAL, precision: 5, scale: 1, nullable: true)]
    private ?float $locationRating;

    #[Column(name: 'price_rating', type: Types::DECIMAL, precision: 5, scale: 1, nullable: true)]
    private ?float $priceRating;

    #[Column(name: 'services_rating', type: Types::DECIMAL, precision: 5, scale: 1, nullable: true)]
    private ?float $servicesRating;

    #[Column(name: 'room_rating', type: Types::DECIMAL, precision: 5, scale: 1, nullable: true)]
    private ?float $roomRating;

    #[Column(name: 'meal_rating', type: Types::DECIMAL, precision: 5, scale: 1, nullable: true)]
    private ?float $mealRating;

    #[Column(name: 'wifi_rating', type: Types::DECIMAL, precision: 5, scale: 1, nullable: true)]
    private ?float $wifiRating;

    #[Column(name: 'hygiene_rating', type: Types::DECIMAL, precision: 5, scale: 1, nullable: true)]
    private ?float $hygieneRating;

    #[OneToMany(mappedBy: 'hotel', targetEntity: HotelImage::class, cascade: ['persist', 'remove'])]
    private Collection $images;

    #[OneToMany(mappedBy: 'hotel', targetEntity: Review::class, cascade: ['persist', 'remove'])]
    private Collection $reviews;

    #[ManyToOne(targetEntity: Location::class, cascade: ['persist'], inversedBy: 'hotels')]
    #[JoinColumn(referencedColumnName: 'id')]
    private ?Location $location;

    #[OneToMany(mappedBy: 'hotel', targetEntity: HotelDescription::class, cascade: ['persist', 'remove'])]
    private Collection $descriptions;

    #[ManyToMany(targetEntity: HotelAmenities::class, inversedBy: 'hotels')]
    #[JoinTable(name: 'hotels_amenities')]
    #[JoinColumn(name: 'hotel_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: 'hotel_amenities_id', referencedColumnName: 'id')]
    private Collection $amenities;

    #[OneToMany(mappedBy: 'hotel', targetEntity: Room::class, cascade: ['persist', 'remove'])]
    private Collection $rooms;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->descriptions = new ArrayCollection();
        $this->amenities = new ArrayCollection();
        $this->rooms = new ArrayCollection();
    }

    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): Hotel
    {
        $room->setHotel($this);
        $this->rooms->add($room);

        return $this;
    }

    public function dropAmenities(): Hotel
    {
        foreach ($this->amenities->getIterator() as $amenity) {
            $this->amenities->removeElement($amenity);
        }

        return $this;
    }


    public function getAmenities(): Collection
    {
        return $this->amenities;
    }


    public function addAmenities(HotelAmenities $amenities): Hotel
    {
        $amenities->setHotel($this);

        $this->amenities->add($amenities);

        return $this;
    }

    public function getDescriptions(): Collection
    {
        return $this->descriptions;
    }

    public function setDescriptions(HotelDescription $description): Hotel
    {
        $description->setHotel($this);
        $this->descriptions->add($description);
        return $this;
    }


    public function getLocationId(): int
    {
        return $this->locationId;
    }

    public function setLocationId(int $locationId): Hotel
    {
        $this->locationId = $locationId;
        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): Hotel
    {
        $this->location = $location;
        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): Hotel
    {
        $this->address = $address;
        return $this;
    }


    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): Hotel
    {
        $review->setHotel($this);
        $this->reviews->add($review);
        return $this;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(HotelImage $image): Hotel
    {
        $image->setHotel($this);

        $this->images->add($image);

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): Hotel
    {
        $this->phone = $phone;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Hotel
    {
        $this->email = $email;
        return $this;
    }

    public function getStarRating(): int
    {
        return $this->starRating;
    }

    public function setStarRating(int $starRating): Hotel
    {
        $this->starRating = $starRating;
        return $this;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function setUri(string $uri): Hotel
    {
        $this->uri = $uri;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Hotel
    {
        $this->title = $title;
        return $this;
    }

    public function getCheckIn(): string
    {
        return $this->checkIn;
    }

    public function setCheckIn(string $checkIn): Hotel
    {
        $this->checkIn = $checkIn;
        return $this;
    }

    public function getCheckOut(): string
    {
        return $this->checkOut;
    }

    public function setCheckOut(string $checkOut): Hotel
    {
        $this->checkOut = $checkOut;
        return $this;
    }

    public function getLatitude(): string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): Hotel
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): Hotel
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Hotel
    {
        $this->description = $description;
        return $this;
    }

    public function getAdditionalInformation(): string
    {
        return $this->additionalInformation;
    }

    public function setAdditionalInformation(string $additionalInformation): Hotel
    {
        $this->additionalInformation = $additionalInformation;
        return $this;
    }

    public function getClientRating(): ?float
    {
        return $this->clientRating;
    }

    public function setClientRating(?float $clientRating): Hotel
    {
        $this->clientRating = $clientRating;
        return $this;
    }

    public function getCleannessRating(): ?float
    {
        return $this->cleannessRating;
    }

    public function setCleannessRating(?float $cleannessRating): Hotel
    {
        $this->cleannessRating = $cleannessRating;
        return $this;
    }

    public function getLocationRating(): ?float
    {
        return $this->locationRating;
    }

    public function setLocationRating(?float $locationRating): Hotel
    {
        $this->locationRating = $locationRating;
        return $this;
    }

    public function getPriceRating(): ?float
    {
        return $this->priceRating;
    }

    public function setPriceRating(?float $priceRating): Hotel
    {
        $this->priceRating = $priceRating;
        return $this;
    }

    public function getServicesRating(): ?float
    {
        return $this->servicesRating;
    }

    public function setServicesRating(?float $servicesRating): Hotel
    {
        $this->servicesRating = $servicesRating;
        return $this;
    }

    public function getRoomRating(): ?float
    {
        return $this->roomRating;
    }

    public function setRoomRating(?float $roomRating): Hotel
    {
        $this->roomRating = $roomRating;
        return $this;
    }

    public function getMealRating(): ?float
    {
        return $this->mealRating;
    }

    public function setMealRating(?float $mealRating): Hotel
    {
        $this->mealRating = $mealRating;
        return $this;
    }

    public function getWifiRating(): ?float
    {
        return $this->wifiRating;
    }

    public function setWifiRating(?float $wifiRating): Hotel
    {
        $this->wifiRating = $wifiRating;
        return $this;
    }

    public function getHygieneRating(): ?float
    {
        return $this->hygieneRating;
    }

    public function setHygieneRating(?float $hygieneRating): Hotel
    {
        $this->hygieneRating = $hygieneRating;
        return $this;
    }


}
