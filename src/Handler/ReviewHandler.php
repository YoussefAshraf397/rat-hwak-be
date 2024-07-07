<?php

namespace App\Handler;

use JsonMachine\Items;
use JsonMachine\JsonDecoder\ExtJsonDecoder;

class ReviewHandler extends BaseHandler
{
    protected const REVIEWS_FILE_NAME = 'Reviews';

    protected const HOTELS_CURRENT_FILE_NAME = 'Reviews_current';

    public function getReviewsDumpFile(): bool
    {
        try {
            $fileName = $this->rateHawkApi->getReviewsDump();
            $resultFileName = static::STORAGE_DIR . DIRECTORY_SEPARATOR . static::REVIEWS_FILE_NAME;
            $decompressed = preg_replace('/(.*)\..*/', '$1', $fileName);
            $command = "zstd -d {$fileName}; rm {$fileName};mv {$decompressed} $resultFileName";
            exec($command);


            return true;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return false;
        }
    }

    public function handleReviewsFile()
    {
        $this->telegramNotifier->notify('reviews started');
        $start = microtime(true);
        $hotelsData = Items::fromFile(static::STORAGE_DIR . DIRECTORY_SEPARATOR . self::REVIEWS_FILE_NAME, ['decoder' => new ExtJsonDecoder(true)]);
        $lastReviewHotel = $this->fileHandleData->getLastReviewHotelName();
        $scipHotel = true;
        if ($lastReviewHotel === '') {
            $scipHotel = false;
        }

        $done = 0;
        try {
            foreach ($hotelsData as $hotelUri => $reviewData) {
//                $this->telegramNotifier->notify([
//                    'hotel' => $hotelUri,
//                    'last' => $lastReviewHotel,
//                    'scip' => $scipHotel,
//                    'review' => $reviewData
//                ]);

                if ($hotelUri === $lastReviewHotel) {
                    $scipHotel = false;
                }
//                $this->telegramNotifier->notify($scipHotel);
                if ($scipHotel) {
                    continue;
                }
                if ($reviewData === null) {
                    continue;
                }
                $this->reviewRepository->insertReviews($reviewData, $hotelUri);
                $this->reviewRepository->flush();
                $this->fileHandleData->setLastReviewHotelName($hotelUri);

                $done++;

                $totalTime = microtime(true) - $start;
                if ($totalTime > 595) {
                    $this->saveFileHandleData();
                    throw new \Exception(sprintf('out of 595 seconds, time: %s, done: %s', $totalTime, $done));
                }
            }
            throw new \Exception('REVIEWS FILE DONE');
        } catch (\Exception $e) {
            $this->telegramNotifier->notify($e->getMessage());
        }
    }
}
