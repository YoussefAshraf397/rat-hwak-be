<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('delta_updates')]
class Delta
{
    #[Id]
    #[Column]
    #[GeneratedValue('AUTO')]
    private int $id;

    #[Column('last_update')]
    private string $lastUpdate;

    public function getId(): int
    {
        return $this->id;
    }

    public function getLastUpdate(): string
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(string $lastUpdate): Delta
    {
        $this->lastUpdate = $lastUpdate;
        return $this;
    }
}
