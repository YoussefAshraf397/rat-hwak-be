<?php

namespace App\Controller;

use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

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
            ->select('DISTINCT l.countryName, l.countryCode')
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


    #[Route('/locations/{countryName}', name: 'app_location')]
    public function index(string $countryName): JsonResponse
    {
        $locationRepository = $this->entityManager->getRepository(Location::class);

        $query = $locationRepository->findBy([
            'countryCode' => $countryName,
            'type' => 'City',
        ]);

        $data = array_map(static function (Location $item) {
            return $item->getTitle();
        }, $query);

        return $this->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
