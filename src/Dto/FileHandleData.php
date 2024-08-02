<?php

namespace App\Dto;

class FileHandleData
{
    private int $lastRegion = 0;

    private int $lastHotel = 0;

    private bool $needToSliceHotels = false;

    private bool $hotelsDumpDone = false;

    private int $currentHotelIncrement = 0;

    private string $lastReviewHotelName = '';

    public function incrementLastRegion(): FileHandleData
    {
        $this->lastRegion++;
        return $this;
    }

    public function incrementCurrentHotel(): FileHandleData
    {
        $this->currentHotelIncrement++;
        return $this;
    }

    public function getLastRegion(): int
    {
        return $this->lastRegion;
    }

    public function setLastRegion(int $lastRegion): FileHandleData
    {
        $this->lastRegion = $lastRegion;
        return $this;
    }

    public function getLastHotel(): int
    {
        return $this->lastHotel;
    }

    public function setLastHotel(int $lastHotel): FileHandleData
    {
        $this->lastHotel = $lastHotel;
        return $this;
    }

    public function isNeedToSliceHotels(): bool
    {
        return $this->needToSliceHotels;
    }

    public function setNeedToSliceHotels(bool $needToSliceHotels): FileHandleData
    {
        $this->needToSliceHotels = $needToSliceHotels;
        return $this;
    }

    public function isHotelsDumpDone(): bool
    {
        return $this->hotelsDumpDone;
    }

    public function setHotelsDumpDone(bool $hotelsDumpDone): FileHandleData
    {
        $this->hotelsDumpDone = $hotelsDumpDone;
        return $this;
    }

    public function getCurrentHotelIncrement(): int
    {
        return $this->currentHotelIncrement;
    }


    public function setCurrentHotelIncrement(int $currentHotelIncrement): FileHandleData
    {
        $this->currentHotelIncrement = $currentHotelIncrement;
        return $this;
    }

    public function getLastReviewHotelName(): string
    {
        return $this->lastReviewHotelName;
    }

    public function setLastReviewHotelName(string $lastReviewHotelName): FileHandleData
    {
        $this->lastReviewHotelName = $lastReviewHotelName;

        return $this;
    }
}
