<?php

namespace App\Dto;

class DeltaFileHandleData
{
    protected bool $isHotelsFile = false;

    protected bool $isHotelsDone = false;

    protected bool $isReviewsFile = false;

    protected bool $isReviewsDone = false;

    protected int $hotelsFilePosition = 0;

    protected int $hotelsCreated = 0;

    protected int $hotelsUpdated = 0;

    protected int $hotelsDeleted = 0;

    protected int $deltaDoneTimestamp = 0;

    protected bool $deltaInProgress = true;

    public function isHotelsFile(): bool
    {
        return $this->isHotelsFile;
    }

    public function setIsHotelsFile(bool $isHotelsFile): DeltaFileHandleData
    {
        $this->isHotelsFile = $isHotelsFile;

        return $this;
    }

    public function isHotelsDone(): bool
    {
        return $this->isHotelsDone;
    }

    public function setIsHotelsDone(bool $isHotelsDone): DeltaFileHandleData
    {
        $this->isHotelsDone = $isHotelsDone;
        return $this;
    }

    public function isReviewsFile(): bool
    {
        return $this->isReviewsFile;
    }

    public function setIsReviewsFile(bool $isReviewsFile): DeltaFileHandleData
    {
        $this->isReviewsFile = $isReviewsFile;
        return $this;
    }

    public function isReviewsDone(): bool
    {
        return $this->isReviewsDone;
    }

    public function setIsReviewsDone(bool $isReviewsDone): DeltaFileHandleData
    {
        $this->isReviewsDone = $isReviewsDone;
        return $this;
    }

    public function getHotelsFilePosition(): int
    {
        return $this->hotelsFilePosition;
    }

    public function increaseHotelsFilePosition(): DeltaFileHandleData
    {
        $this->hotelsFilePosition++;
        return $this;
    }

    public function getHotelsCreated(): int
    {
        return $this->hotelsCreated;
    }

    public function increaseHotelsCreated(): DeltaFileHandleData
    {
        $this->hotelsCreated++;
        return $this;
    }

    public function getHotelsUpdated(): int
    {
        return $this->hotelsUpdated;
    }

    public function increaseHotelsUpdated(): DeltaFileHandleData
    {
        $this->hotelsUpdated++;
        return $this;
    }

    public function getHotelsDeleted(): int
    {
        return $this->hotelsDeleted;
    }

    public function increaseHotelsDeleted(): DeltaFileHandleData
    {
        $this->hotelsDeleted++;
        return $this;
    }

    public function getDeltaDoneTimestamp(): int
    {
        return $this->deltaDoneTimestamp;
    }

    public function setDeltaDoneTimestamp(int $deltaDoneTimestamp): DeltaFileHandleData
    {
        $this->deltaDoneTimestamp = $deltaDoneTimestamp;
        return $this;
    }

    public function isDeltaInProgress(): bool
    {
        return $this->deltaInProgress;
    }

    public function setDeltaInProgress(bool $deltaInProgress): DeltaFileHandleData
    {
        $this->deltaInProgress = $deltaInProgress;
        return $this;
    }
}
