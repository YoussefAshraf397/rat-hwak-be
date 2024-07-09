<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Entity\HotelAmenities;
use App\Entity\HotelDescription;
use App\Entity\HotelImage;
use App\Entity\Location;
use App\Entity\Review;
use App\Entity\ReviewImage;
use App\Entity\Room;
use App\Entity\RoomAmenities;
use App\Helper\StringHelper;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HotelsController extends AbstractController
{
    protected Logger $logger;

    protected EntityManagerInterface $entityManager;

    protected const HOTELS_PER_PAGE = 30;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/hotels2loc/{countryCode}/{location}', name: 'app_hotels_by_oc', methods: ["GET"])]
    public function hotelsByLocations(Request $request, string $countryCode, string $location): JsonResponse
    {
        $locationRep = $this->entityManager->getRepository(Location::class);
        $locations = $locationRep->findBy([
            'countryCode' => $countryCode,
            'title' => $location,
            'type' => 'City'
        ]);

        // Handle the case where no locations are found
        if (empty($locations)) {
            return $this->json([
                'success' => false,
                'message' => 'Location not found.'
            ], 404);
        }

        $currentLocation = $locations[0];
        $locationId = $currentLocation->getId();

        // Pagination parameters
        $page = $request->query->getInt('page', 1);
        $perPage = $request->query->getInt('per_page', static::HOTELS_PER_PAGE);

        // Star rating filter
        $starRating = $request->query->get('star_rating');


        // Fetch hotel IDs ordered by star rating
        $queryBuilder = $this->entityManager
            ->createQueryBuilder()
            ->select('h.id')
            ->from('App\Entity\Hotel', 'h')
            ->where('h.locationId = :locationId')
            ->orderBy('h.starRating', 'DESC')
            ->setParameter('locationId', $locationId)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        if ($starRating !== null) {
            $queryBuilder->andWhere('h.starRating = :starRating')
                ->setParameter('starRating', $starRating);
        }
        $query = $queryBuilder->getQuery();

        $ids = array_map(static function ($item) {
            return $item['id'];
        }, $query->getResult());

        if (empty($ids)) {
            return $this->json([
                'success' => true,
                'data' => [
                    'region_id' => $locationId,
                    'total' => 0,
                    'pages' => 0,
                    'lng' => $currentLocation->getLongitude(),
                    'lat' => $currentLocation->getLatitude(),
                    'hotels' => [],
                ],
            ]);
        }

        // Fetch hotels by IDs
        $hotelsRepository = $this->entityManager->getRepository(Hotel::class);
        $hotelEntities = $hotelsRepository->findBy(['id' => $ids]);

        // Sort hotels by the order of IDs
        $hotelMap = [];
        foreach ($hotelEntities as $hotelEntity) {
            $hotelMap[$hotelEntity->getId()] = $hotelEntity;
        }
        $sortedHotels = array_map(static function ($id) use ($hotelMap) {
            return $hotelMap[$id];
        }, $ids);

        // Count total hotels
        $countQueryBuilder = $this->entityManager
            ->createQueryBuilder()
            ->select('count(h.id)')
            ->from('App\Entity\Hotel', 'h')
            ->where('h.locationId = :locationId')
            ->setParameter('locationId', $locationId);

        if ($starRating !== null) {
            $countQueryBuilder->andWhere('h.starRating = :starRating')
                ->setParameter('starRating', $starRating);
        }

        $countQuery = $countQueryBuilder->getQuery();

        $totalHotels = (int)$countQuery->getSingleScalarResult();

        // Prepare hotel data
        $hotels = [];
        foreach ($sortedHotels as $hotelItem) {
            /** @var Hotel $hotelItem */
            $amenities = [];
            foreach ($hotelItem->getAmenities() as $amenity) {
                /** @var HotelAmenities $amenity */
                $amenities[] = $amenity->getGroup()->getName();
            }

            $image = $hotelItem->getImages()->first() ? $hotelItem->getImages()->first()->getImage() : null;
            $amenities = array_values(array_unique($amenities));

            $hotels[] = [
                'uri' => $hotelItem->getUri(),
                'title' => $hotelItem->getTitle(),
                'address' => $hotelItem->getAddress(),
                'star_rating' => $hotelItem->getStarRating(),
                'lng' => $hotelItem->getLongitude(),
                'lat' => $hotelItem->getLatitude(),
                'amenities' => $amenities,
                'image' => StringHelper::replaceWithinBracers($image ?? '', 'size', '1024x768'),
                'reviews' => [
                    'rating' => (float)$hotelItem->getClientRating(),
                    'reviews_quantity' => count($hotelItem->getReviews())
                ]
            ];
        }

        return $this->json([
            'success' => true,
            'data' => [
                'region_id' => $locationId,
                'total' => $totalHotels,
                'pages' => ceil($totalHotels / $perPage),
                'lng' => $currentLocation->getLongitude(),
                'lat' => $currentLocation->getLatitude(),
                'hotels' => $hotels,
            ],
        ]);

    }


    #[Route('/hotel/{uri}', name: 'app_hotels', methods: ["GET"])]
    public function hotelInfo($uri): JsonResponse
    {
        $hotelRepository = $this->entityManager->getRepository(Hotel::class);
        $hotel = ($hotelRepository->findOneBy(['uri' => $uri]));
        if ($hotel === null) {
            return $this->json([
                'success' => false,
                'message' => 'not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $rooms = [];
        foreach ($hotel?->getRooms()->getIterator() as $room) {
            /**
             * @var $room Room
             */
            $roomImages = [];
            foreach ($room->getImages()->getIterator() as $roomImage) {
                $roomImages[] = StringHelper::replaceWithinBracers($roomImage->getImage(), 'size', '1024x768');
            }
            $roomAmenities = [];
            foreach ($room->getAmenities()->getIterator() as $amenity) {
                /**
                 * @var $amenity RoomAmenities
                 */
                $roomAmenities[] = $amenity->getName();

            }
            $rooms[] = [
                'title' => $room->getTitle(),
                'images' => $roomImages,
                'amenities' => $roomAmenities,
                'ratehawk_room_group' => $room->getRoomGroup()
            ];

        }
        $hotelImages = [];
        foreach ($hotel?->getImages()->getIterator() as $hotelImage) {
            /**
             * @var $hotelImage HotelImage
             */
            $hotelImages[] = StringHelper::replaceWithinBracers($hotelImage->getImage(), 'size', '1024x768');

        }

        $hotelAmenities = [];
        foreach ($hotel?->getAmenities()->getIterator() as $hotelAmenity) {
            /**
             * @var $hotelAmenity HotelAmenities
             */
            $hotelAmenities[$hotelAmenity->getGroup()->getName()][] = $hotelAmenity->getName();

        }
        $hotelDescriptions = [];
        foreach ($hotel?->getDescriptions()->getIterator() as $hotelDescription) {
            /**
             * @var $hotelDescription HotelDescription
             */
            $hotelDescriptions[$hotelDescription->getDescriptionGroup()->getTitle()] = $hotelDescription->getText();
        }

        $reviews = [];


        //$hotel = $hotelRepository->findOneBy(['uri' => 'alaturca_house']);

        foreach ($hotel?->getReviews()->getIterator() as $review) {
            /**
             * @var $review Review
             */
            $reviews['rating'] = $hotel?->getClientRating();
            $reviews['detailed_ratings']['cleanness'] = $hotel?->getCleannessRating();
            $reviews['detailed_ratings']['location'] = $hotel?->getLocationRating();
            $reviews['detailed_ratings']['price'] = $hotel?->getPriceRating();
            $reviews['detailed_ratings']['services'] = $hotel?->getServicesRating();
            $reviews['detailed_ratings']['room'] = $hotel?->getRoomRating();
            $reviews['detailed_ratings']['meal'] = $hotel?->getMealRating();
            $reviews['detailed_ratings']['wifi'] = $hotel?->getWifiRating();
            $reviews['detailed_ratings']['hygiene'] = $hotel?->getHygieneRating();

            $images = [];
            foreach ($review->getImages()->getIterator() as $reviewImage) {
                /**
                 * @var $reviewImage ReviewImage
                 */
                $images[] = StringHelper::replaceWithinBracers($reviewImage->getImage(), 'size', '1024x768');
            }

            $reviews['reviews'][] = [
                'review_plus' => $review->getReviewPlus(),
                'review_minus' => $review->getReviewMinus(),
                'created' => $review->getCreatedAt(),
                'author' => $review->getAuthor(),
                'adults' => $review->getAdults(),
                'children' => $review->getChildren(),
                'room_name' => $review->getRoomName(),
                'nights' => $review->getNights(),
                'images' => $images,
                'detailed' => [
                    'cleanness' => $review->getCleannessRating(),
                    'location' => $review->getLocationRating(),
                    'price' => $review->getPriceRating(),
                    'services' => $review->getServicesRating(),
                    'room' => $review->getRoomRating(),
                    'meal' => $review->getMealRating(),
                    'wifi' => $review->getWifiRating(),
                    'hygiene' => $review->getHygieneRating(),
                ],
                'traveller_type' => $review->getTravellerType(),
                'trip_type' => $review->getTripType(),
                'rating' => $review->getRating(),
            ];

        }

        $hotelData = [
            'id' => $hotel->getUri(),
            'title' => $hotel->getTitle(),
            'address' => $hotel->getAddress(),
//            'region_id' => $hotel->getLocation()->getRateHawkId(),
//            'location' => $hotel->getLocation()->getTitle(),
            'phone' => $hotel->getPhone(),
            'email' => $hotel->getEmail(),
            'check_in' => $hotel->getCheckIn(),
            'check_out' => $hotel->getCheckOut(),
            'star_rating' => $hotel->getStarRating(),
            'latitude' => $hotel->getLatitude(),
            'longitude' => $hotel->getLongitude(),
            'additional_information' => $hotel->getAdditionalInformation(),
            'reviews' => $reviews,
            'images' => $hotelImages,
            'amenities' => $hotelAmenities,
            'descriptions' => $hotelDescriptions,
            'rooms' => $rooms,
        ];


        return $this->json([
            'success' => true,
            'data' => $hotelData
        ]);
    }
}
