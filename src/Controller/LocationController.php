<?php

namespace App\Controller;

use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class LocationController extends AbstractController
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/countries', methods: ['GET'])]
    public function getCountries(): JsonResponse
    {
        $query = $this->entityManager
            ->createQueryBuilder()
            ->select('DISTINCT l.countryName, l.countryCode, l.rateHawkId')
            ->from('App\Entity\Location', 'l')
            ->where('l.countryCode IS NOT NULL')
            ->andWhere('l.countryCode != \'\'')
            ->getQuery();


        $data = array_filter($query->getResult(), static function ($item) {
            return !empty($item['countryCode']);
        });


        return $this->json([
            'success' => true,
            'data' => $data,
        ]);
    }


    // #[Route('/locations/{countryName}', name: 'app_location')]
    // public function index(string $countryName): JsonResponse
    // {
    //     $locationRepository = $this->entityManager->getRepository(Location::class);

    //     $query = $locationRepository->findBy([
    //         'countryCode' => $countryName,
    //         'type' => 'City',
    //     ]);

    //     $data = array_map(static function (Location $item) {
    //         return [
    //             'title' => $item->getTitle() ,
    //             'rat_hawk_id' => $item->getRatHawkId()
    //         ];
    //     }, $query);

    //     return $this->json([
    //         'success' => true,
    //         'data' => $data,
    //     ]);
    // }

    // #[Route('/locations/search/{searchTerm}', name: 'app_location_search')]
    // public function searchTerm(string $searchTerm): JsonResponse
    // {
    //     $locationRepository = $this->entityManager->getRepository(Location::class);

    //     // 1. Query to check for an exact match for Country
    //     $exactMatchQueryBuilderCountry = $locationRepository->createQueryBuilder('l');
    //     $exactMatchQueryBuilderCountry
    //         ->where('l.title = :searchTerm')
    //         ->andWhere('l.type = :countryType')
    //         ->setParameter('searchTerm', $searchTerm)
    //         ->setParameter('countryType', 'Country')
    //         ->setMaxResults(1); // Limit to one exact match

    //     $exactMatchCountry = $exactMatchQueryBuilderCountry->getQuery()->getOneOrNullResult(); // Get one exact match or null

    //     // 2. Query to check for an exact match for City
    //     $exactMatchQueryBuilderCity = $locationRepository->createQueryBuilder('l');
    //     $exactMatchQueryBuilderCity
    //         ->where('l.title = :searchTerm')
    //         ->andWhere('l.type = :cityType')
    //         ->setParameter('searchTerm', $searchTerm)
    //         ->setParameter('cityType', 'City')
    //         ->setMaxResults(1); // Limit to one exact match

    //     $exactMatchCity = $exactMatchQueryBuilderCity->getQuery()->getOneOrNullResult(); // Get one exact match or null

    //     // 3. Query for partial matches excluding the exact matches
    //     $likeMatchQueryBuilder = $locationRepository->createQueryBuilder('l');
    //     $likeMatchQueryBuilder
    //         ->where('(l.countryName LIKE :searchTerm OR l.title LIKE :searchTerm)')
    //         ->andWhere('l.type IN (:types)')
    //         ->setParameter('searchTerm', $searchTerm . '%')
    //         ->setParameter('types', ['City', 'Country'])
    //         ->setMaxResults(5); // Limit to 5 partial matches

    //     $likeMatches = $likeMatchQueryBuilder->getQuery()->getResult();

    //     // 4. Filter out exact matches from partial matches
    //     if ($exactMatchCountry) {
    //         $likeMatches = array_filter($likeMatches, function (Location $location) use ($exactMatchCountry) {
    //             return $location->getId() !== $exactMatchCountry->getId();
    //         });
    //     }

    //     if ($exactMatchCity) {
    //         $likeMatches = array_filter($likeMatches, function (Location $location) use ($exactMatchCity) {
    //             return $location->getId() !== $exactMatchCity->getId();
    //         });
    //     }

    //     // Combine results: exact matches first (Country, then City), followed by partial matches
    //     $locations = array_merge(
    //         $exactMatchCountry ? [$exactMatchCountry] : [],
    //         $exactMatchCity ? [$exactMatchCity] : [],
    //         $likeMatches
    //     );

    //     // Format the data
    //     $data = array_map(static function (Location $item) {
    //         return [
    //             'type' => $item->getType(),
    //             'name' => $item->getTitle() . ', ' . $item->getCountryName() . ' ( ' . $item->getType() . ' )',
    //             'title' => $item->getTitle(),
    //             'countryName' => $item->getType() === 'City' ? $item->getCountryName() : null, // Country name for cities, null for countries
    //             'rate_hawk_id' => $item->getRateHawkId(),
    //         ];
    //     }, $locations);

    //     // Return a JSON response
    //     return $this->json([
    //         'success' => true,
    //         'data' => $data,
    //     ]);
    // }

    #[Route('/locations/search', name: 'app_location_search_new', methods: ['POST'])]
    public function search(Request $request): JsonResponse
    {
        // Retrieve the searchTerm from the request body
        $searchTerm = $request->request->get('searchTerm');
    
        // If searchTerm is empty, return a 400 response
        if (empty($searchTerm)) {
            return $this->json(['success' => false, 'message' => 'Search term is required.'], 400);
        }
        $locationRepository = $this->entityManager->getRepository(Location::class);

        // 1. Query to check for an exact match for Country
        $exactMatchQueryBuilderCountry = $locationRepository->createQueryBuilder('l');
        $exactMatchQueryBuilderCountry
            ->where('l.title = :searchTerm')
            ->andWhere('l.type = :countryType')
            ->setParameter('searchTerm', $searchTerm)
            ->setParameter('countryType', 'Country')
            ->setMaxResults(1); // Limit to one exact match

        $exactMatchCountry = $exactMatchQueryBuilderCountry->getQuery()->getOneOrNullResult(); // Get one exact match or null

        // 2. Query to check for an exact match for City
        $exactMatchQueryBuilderCity = $locationRepository->createQueryBuilder('l');
        $exactMatchQueryBuilderCity
            ->where('l.title = :searchTerm')
            ->andWhere('l.type = :cityType')
            ->setParameter('searchTerm', $searchTerm)
            ->setParameter('cityType', 'City')
            ->setMaxResults(1); // Limit to one exact match

        $exactMatchCity = $exactMatchQueryBuilderCity->getQuery()->getOneOrNullResult(); // Get one exact match or null

        // 3. Query for partial matches excluding the exact matches
        $likeMatchQueryBuilder = $locationRepository->createQueryBuilder('l');
        $likeMatchQueryBuilder
            ->where('(l.countryName LIKE :searchTerm OR l.title LIKE :searchTerm)')
            ->andWhere('l.type IN (:types)')
            ->setParameter('searchTerm', $searchTerm . '%')
            ->setParameter('types', ['City', 'Country'])
            ->setMaxResults(5); // Limit to 5 partial matches

        $likeMatches = $likeMatchQueryBuilder->getQuery()->getResult();

        // 4. Filter out exact matches from partial matches
        if ($exactMatchCountry) {
            $likeMatches = array_filter($likeMatches, function (Location $location) use ($exactMatchCountry) {
                return $location->getId() !== $exactMatchCountry->getId();
            });
        }

        if ($exactMatchCity) {
            $likeMatches = array_filter($likeMatches, function (Location $location) use ($exactMatchCity) {
                return $location->getId() !== $exactMatchCity->getId();
            });
        }

        // Combine results: exact matches first (Country, then City), followed by partial matches
        $locations = array_merge(
            $exactMatchCountry ? [$exactMatchCountry] : [],
            $exactMatchCity ? [$exactMatchCity] : [],
            $likeMatches
        );

        // Format the data
        $data = array_map(static function (Location $item) {
            return [
                'type' => $item->getType(),
                'name' => $item->getTitle() . ', ' . $item->getCountryName() . ' ( ' . $item->getType() . ' )',
                'title' => $item->getTitle(),
                'countryName' => $item->getType() === 'City' ? $item->getCountryName() : null, // Country name for cities, null for countries
                'rate_hawk_id' => $item->getRateHawkId(),
            ];
        }, $locations);

        // Return a JSON response
        return $this->json([
            'success' => true,
            'data' => $data,
        ]);
    }

}
