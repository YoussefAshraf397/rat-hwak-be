<?php

namespace App\Handler;

use App\Entity\ReviewImage;
use App\Enum\FileCutType;

class HotelHandler extends BaseHandler
{
    protected const HOTELS_FILE_NAME = 'Hotels';

    protected const HOTELS_CURRENT_FILE_NAME = 'Hotels_current';

    protected const HOTELS_INCREMENTAL_FILE_NAME = 'Hotels_incremental';

    protected function sliceHotelsFile(): void
    {
        $start = microtime(true);
        $this->telegramNotifier->notify('slicing hotels file');
        $this->fileCutter
            ->setFile(static::STORAGE_DIR . DIRECTORY_SEPARATOR . static::HOTELS_FILE_NAME, $this->fileHandleData->getLastHotel(), FileCutType::Hotels)
            ->sliceCurrentFile();

        $this->telegramNotifier->notify('slicing done in ' . (microtime(true) - $start));
        $this->fileHandleData->setNeedToSliceHotels(false);
        $this->fileHandleData->setCurrentHotelIncrement(0);
        $this->saveFileHandleData();
    }

    protected function currentFileIsEmpty(string $fileName): bool
    {
        $this->jsonHandler->setFile(static::STORAGE_DIR . DIRECTORY_SEPARATOR . $fileName);
        if ($this->jsonHandler->getItem() === []) {
            $this->fileHandleData->setHotelsDumpDone(true);
            $this->saveFileHandleData();
        }

        return $this->fileHandleData->isHotelsDumpDone();
    }

    public function handleHotelsDumpFile(): void
    {
//        $this->fileHandleData->setNeedToSliceHotels(true);
        if ($this->fileHandleData->isNeedToSliceHotels()) {
            $this->sliceHotelsFile();
            return;
        }
        if ($this->currentFileIsEmpty(static::HOTELS_CURRENT_FILE_NAME)) {
            $this->telegramNotifier->notify('HOTELS DUMP IS DONE');
            return;
        }
        $fileHandlingStart = microtime(true);
        $this->jsonHandler->setFile(static::STORAGE_DIR . DIRECTORY_SEPARATOR . static::HOTELS_CURRENT_FILE_NAME, $this->fileHandleData->getCurrentHotelIncrement());
        $start = microtime(true);
        $pointerTime = $start - $fileHandlingStart;
        if ($pointerTime < 0.1) {
            $pointerTime = 0;
        }
        $this->telegramNotifier->notify(sprintf('time to move pointer: %s', $pointerTime));
        $i = 0;
        $idx = $this->fileHandleData->getCurrentHotelIncrement();

        $temp = [];
        try {
            while ($hotelData = $this->jsonHandler->getItem()) {
                $temp[] = json_encode($hotelData);
                $this->hotelRepository->insertHotel($hotelData);
                $i++;
                $this->fileHandleData->incrementCurrentHotel();
                var_dump($this->fileHandleData->getCurrentHotelIncrement());

                if ($i === 300) {
                    $this->hotelRepository->flush();
                    $this->hotelRepository->initEntities();
                    $this->saveFileHandleData();
                    $temp = [];

                    $i = 0;
                }
                if (microtime(true) - $fileHandlingStart > 265) {
                    $this->hotelRepository->flush();
                    $temp = [];
                    $this->saveFileHandleData();
                    $done = $this->fileHandleData->getCurrentHotelIncrement() - $idx;
                    $totalIdx = $this->fileHandleData->getCurrentHotelIncrement() + $this->fileHandleData->getLastHotel();
                    $this->telegramNotifier->notify(sprintf('DONE: %s, file offset: %s, total: %s', $done, $this->fileHandleData->getCurrentHotelIncrement(), $totalIdx));
                    throw new \Exception('out of 265 seconds, time ' . (microtime(true) - $fileHandlingStart));
                }
            }
            if ($hotelData === []) {
                $this->telegramNotifier->notify('The split file is end and make new split file');
                $totalIdx = $this->fileHandleData->getCurrentHotelIncrement() + $this->fileHandleData->getLastHotel();
                $lastHotel = $this->fileHandleData->getLastHotel() + $this->fileHandleData->getCurrentHotelIncrement();
                $this->fileHandleData->setLastHotel($lastHotel);
                $this->fileHandleData->setNeedToSliceHotels(true);
                $this->hotelRepository->flush();
                $done = $this->fileHandleData->getCurrentHotelIncrement() - $idx;
                $this->telegramNotifier->notify(sprintf('DONE: %s, file offset: %s, total: %s', $done, $this->fileHandleData->getCurrentHotelIncrement(), $totalIdx));
                $this->saveFileHandleData();
            }
        } catch (\Exception $e) {
            $this->saveFileHandleData();
            var_dump($e->getMessage());
            if (!empty($temp)) {
                file_put_contents(static::STORAGE_DIR . DIRECTORY_SEPARATOR . 'failed', implode(PHP_EOL, $temp) . PHP_EOL, FILE_APPEND);
            }
            $this->telegramNotifier->notify($e->getMessage());
        }
    }

    public function getHotelsDumpFile(): bool
    {
        try {
            $fileName = $this->rateHawkApi->getHotelsDump();
            $resultFileName = static::STORAGE_DIR . DIRECTORY_SEPARATOR . static::HOTELS_FILE_NAME;
            $decompressed = preg_replace('/(.*)\..*/', '$1', $fileName);
            $command = "zstd -d {$fileName}; rm {$fileName};mv {$decompressed} $resultFileName";
            exec($command);


            return true;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return false;
        }
    }


    public function saveAmenities(): void
    {
        if ($this->currentFileIsEmpty(static::HOTELS_FILE_NAME)) {
            $this->telegramNotifier->notify('HOTELS DUMP IS DONE');
            return;
        }
        $fileHandlingStart = microtime(true);
        $this->jsonHandler->setFile(static::STORAGE_DIR . DIRECTORY_SEPARATOR . static::HOTELS_FILE_NAME, $this->fileHandleData->getLastHotel());

        $start = microtime(true);
        $pointerTime = $start - $fileHandlingStart;
        if ($pointerTime < 0.1) {
            $pointerTime = 0;
        }
        $this->telegramNotifier->notify(sprintf('time to move pointer: %s', $pointerTime));
        $idx = $this->fileHandleData->getLastHotel();
        try {
            while ($hotelData = $this->jsonHandler->getItem()) {
                $this->hotelRepository->saveAmenities($hotelData['amenity_groups']);
                $hotelIdx = $this->fileHandleData->getLastHotel();
                $hotelIdx++;
                $this->fileHandleData->setLastHotel($hotelIdx);
                var_dump($this->fileHandleData->getLastHotel());


                if (microtime(true) - $fileHandlingStart > 980) {
                    $this->saveFileHandleData();
                    $done = $this->fileHandleData->getLastHotel() - $idx;
                    $totalIdx = $this->fileHandleData->getLastHotel();
                    $this->telegramNotifier->notify(sprintf('DONE: %s, total: %s', $done, $totalIdx));
                    throw new \Exception('out of 980 seconds, time ' . (microtime(true) - $fileHandlingStart));
                }
            }
            if ($hotelData === []) {
                $totalIdx = $this->fileHandleData->getLastHotel();
                $done = $this->fileHandleData->getLastHotel() - $idx;
                $this->telegramNotifier->notify(sprintf('DONE: %s, total: %s', $done, $totalIdx));
                $this->saveFileHandleData();
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->telegramNotifier->notify($e->getMessage());
        }
    }
}
