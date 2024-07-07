<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('reviews')]
class Review
{
    #[Id]
    #[GeneratedValue('AUTO')]
    #[Column]
    private int $id;

    #[Column('hotel_id')]
    #[JoinColumn(referencedColumnName: 'id')]
    private int $hotelId;

    #[Column(name: 'review_plus', type: Types::TEXT, nullable: true)]
    private ?string $reviewPlus;

    #[Column(name: 'review_minus', type: Types::TEXT, nullable: true)]
    private ?string $reviewMinus;

    #[Column(name: 'created_at', nullable: true)]
    private ?string $createdAt;

    #[Column(nullable: true)]
    private ?string $author;

    #[Column(nullable: true)]
    private ?int $adults;

    #[Column(nullable: true)]
    private ?int $children;

    #[Column(nullable: true)]
    private ?string $roomName;

    #[Column(nullable: true)]
    private ?int $nights;

    #[Column(name: 'traveller_type', nullable: true)]
    private ?string $travellerType;

    #[Column(name: 'trip_type', nullable: true)]
    private ?string $tripType;

    #[Column(type: Types::DECIMAL, precision: 5, scale: 1, nullable: true)]
    private ?float $rating;

    #[Column(name: 'cleanness_rating', nullable: true)]
    private ?int $cleannessRating;

    #[Column(name: 'location_rating', nullable: true)]
    private ?int $locationRating;

    #[Column(name: 'price_rating', nullable: true)]
    private ?int $priceRating;

    #[Column(name: 'services_rating', nullable: true)]
    private ?int $servicesRating;

    #[Column(name: 'room_rating', nullable: true)]
    private ?int $roomRating;

    #[Column(name: 'meal_rating', nullable: true)]
    private ?int $mealRating;

    #[Column(name: 'wifi_rating', nullable: true)]
    private ?string $wifiRating;

    #[Column(name: 'hygiene_rating', nullable: true)]
    private ?string $hygieneRating;

    #[ManyToOne(targetEntity: Hotel::class, cascade: ['persist'], inversedBy: 'reviews')]
    #[JoinColumn(referencedColumnName: 'id')]
    private Hotel $hotel;

    #[OneToMany(mappedBy: 'review', targetEntity: ReviewImage::class, cascade: ['persist', 'remove'])]
    private Collection $images;


    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getImages(): Collection
    {
        return $this->images;
    }


    public function addImage(ReviewImage $image): Review
    {
        $image->setReview($this);
        $this->images->add($image);

        return $this;
    }

    public function getHotel(): Hotel
    {
        return $this->hotel;
    }

    public function setHotel(Hotel $hotel): Review
    {
        $this->hotel = $hotel;
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

    public function setHotelId(int $hotelId): Review
    {
        $this->hotelId = $hotelId;
        return $this;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): Review
    {
        $this->author = $author;
        return $this;
    }

    public function getReviewPlus(): ?string
    {
        return $this->reviewPlus;
    }


    public function setReviewPlus(?string $reviewPlus): Review
    {
        $this->reviewPlus = $reviewPlus;
        return $this;
    }

    public function getReviewMinus(): ?string
    {
        return $this->reviewMinus;
    }

    public function setReviewMinus(?string $reviewMinus): Review
    {
        $this->reviewMinus = $reviewMinus;
        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): Review
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getAdults(): ?int
    {
        return $this->adults;
    }

    public function setAdults(?int $adults): Review
    {
        $this->adults = $adults;
        return $this;
    }

    public function getChildren(): ?int
    {
        return $this->children;
    }

    public function setChildren(?int $children): Review
    {
        $this->children = $children;
        return $this;
    }

    public function getRoomName(): ?string
    {
        return $this->roomName;
    }

    public function setRoomName(?string $roomName): Review
    {
        $this->roomName = $roomName;
        return $this;
    }

    public function getNights(): ?int
    {
        return $this->nights;
    }

    public function setNights(?int $nights): Review
    {
        $this->nights = $nights;
        return $this;
    }

    public function getTravellerType(): ?string
    {
        return $this->travellerType;
    }

    public function setTravellerType(?string $travellerType): Review
    {
        $this->travellerType = $travellerType;
        return $this;
    }

    public function getTripType(): ?string
    {
        return $this->tripType;
    }

    public function setTripType(?string $tripType): Review
    {
        $this->tripType = $tripType;
        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): Review
    {
        $this->rating = $rating;
        return $this;
    }

    public function getCleannessRating(): ?int
    {
        return $this->cleannessRating;
    }

    public function setCleannessRating(?int $cleannessRating): Review
    {
        $this->cleannessRating = $cleannessRating;
        return $this;
    }

    public function getLocationRating(): ?int
    {
        return $this->locationRating;
    }

    public function setLocationRating(?int $locationRating): Review
    {
        $this->locationRating = $locationRating;
        return $this;
    }

    public function getPriceRating(): ?int
    {
        return $this->priceRating;
    }

    public function setPriceRating(?int $priceRating): Review
    {
        $this->priceRating = $priceRating;
        return $this;
    }

    public function getServicesRating(): ?int
    {
        return $this->servicesRating;
    }

    public function setServicesRating(?int $servicesRating): Review
    {
        $this->servicesRating = $servicesRating;
        return $this;
    }

    public function getRoomRating(): ?int
    {
        return $this->roomRating;
    }

    public function setRoomRating(?int $roomRating): Review
    {
        $this->roomRating = $roomRating;
        return $this;
    }

    public function getMealRating(): ?int
    {
        return $this->mealRating;
    }

    public function setMealRating(?int $mealRating): Review
    {
        $this->mealRating = $mealRating;
        return $this;
    }

    public function getWifiRating(): ?string
    {
        return $this->wifiRating;
    }

    public function setWifiRating(?string $wifiRating): Review
    {
        $this->wifiRating = $wifiRating;
        return $this;
    }

    public function getHygieneRating(): ?string
    {
        return $this->hygieneRating;
    }

    public function setHygieneRating(?string $hygieneRating): Review
    {
        $this->hygieneRating = $hygieneRating;
        return $this;
    }

}
