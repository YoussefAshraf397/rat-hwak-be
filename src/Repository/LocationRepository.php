<?php

namespace App\Repository;

use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;

class LocationRepository
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function insertRegion(array $regionData): void
    {
        if (empty($regionData)) {
            return;
        }

        $locationName = $regionData['name']['en'] ?? $regionData['name']['en'];
        $locationRegion = $regionData['country_name']['en'] ?? $regionData['country_name']['en'] ?? $locationName;
        $location = (new Location())
            ->setRateHawkId($regionData['id'])
            ->setLatitude($regionData['center']['latitude'])
            ->setLongitude($regionData['center']['longitude'])
            ->setCountryName($locationRegion)
            ->setCountryCode($regionData['country_code'] ?? '')
            ->setType($regionData['type'])
            ->setTitle($locationName);


        $this->entityManager->persist($location);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}

