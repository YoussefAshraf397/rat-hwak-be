<?php

namespace App\Repository;

use App\Entity\Hotel;
use App\Entity\Review;
use App\Entity\ReviewImage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ReviewRepository
{
    protected EntityManagerInterface $entityManager;

    protected EntityRepository $hotelRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->hotelRepository = $this->entityManager->getRepository(Hotel::class);
    }

    public function insertReviews(array $reviewsData, string $hotelUri): void
    {
        $hotel = $this->hotelRepository->findOneBy(['uri' => $hotelUri]);
        if ($hotel === null) {
            return;
        }
        $hotel
            ->setClientRating($reviewsData['rating'])
            ->setCleannessRating($reviewsData['detailed_ratings']['cleanness'])
            ->setLocationRating($reviewsData['detailed_ratings']['location'])
            ->setPriceRating($reviewsData['detailed_ratings']['price'])
            ->setServicesRating($reviewsData['detailed_ratings']['services'])
            ->setRoomRating($reviewsData['detailed_ratings']['room'])
            ->setMealRating($reviewsData['detailed_ratings']['meal'])
            ->setWifiRating($reviewsData['detailed_ratings']['wifi'])
            ->setHygieneRating($reviewsData['detailed_ratings']['hygiene']);

        foreach ($reviewsData['reviews'] as $review) {
            $reviewEntity = (new Review())
                ->setHotel($hotel)
                ->setReviewPlus($review['review_plus'])
                ->setReviewMinus($review['review_minus'])
                ->setCreatedAt($review['created'])
                ->setAuthor($review['author'])
                ->setAdults($review['adults'])
                ->setChildren($review['children'])
                ->setRoomName($review['room_name'])
                ->setNights($review['nights'])
                ->setTravellerType($review['traveller_type'])
                ->setTripType($review['trip_type'])
                ->setRating($review['rating'])
                ->setCleannessRating($review['detailed']['cleanness'])
                ->setLocationRating($review['detailed']['location'])
                ->setPriceRating($review['detailed']['price'])
                ->setServicesRating($review['detailed']['services'])
                ->setRoomRating($review['detailed']['room'])
                ->setMealRating($review['detailed']['meal'])
                ->setWifiRating($review['detailed']['wifi'])
                ->setHygieneRating($review['detailed']['hygiene']);
            foreach ($review['images'] as $idx => $image) {
                $reviewImage = (new ReviewImage())
                    ->setReview($reviewEntity)
                    ->setImage($image)
                    ->setImageSort($idx + 1);

                $reviewEntity->addImage($reviewImage);
            }

            $this->entityManager->persist($reviewEntity);
        }
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}

