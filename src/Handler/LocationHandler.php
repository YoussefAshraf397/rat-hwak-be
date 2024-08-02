<?php

namespace App\Handler;

class LocationHandler extends BaseHandler
{
    protected const LOCATIONS_FILE_NAME = 'Locations';

    public function getLocationsDumpFile()
    {
        try {
            $fileName = $this->rateHawkApi->getRegionDump();
            $resultFileName = static::STORAGE_DIR . DIRECTORY_SEPARATOR . static::LOCATIONS_FILE_NAME;
            $decompressed = preg_replace('/(.*)\..*/', '$1', $fileName);
            $command = "zstd -d {$fileName}; rm {$fileName};mv {$decompressed} $resultFileName";
            exec($command);


            return true;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return false;
        }
    }

    public function handleLocationsDumpFile()
    {
        $start = microtime(true);
//        $this->fileHandleData->setLastRegion(0);
//        $this->telegramNotifier->notify($this->fileHandleData->getLastRegion());

        $this->jsonHandler->setFile(static::STORAGE_DIR . DIRECTORY_SEPARATOR . static::LOCATIONS_FILE_NAME, $this->fileHandleData->getLastRegion());
        $startPos = $this->fileHandleData->getLastRegion();
        try {
            while ($regionData = $this->jsonHandler->getItem()) {
                $this->locationRepository->insertRegion($regionData);
                $this->fileHandleData->incrementLastRegion();
                var_dump($this->fileHandleData->getLastRegion());

                if ($this->fileHandleData->getLastRegion() - $startPos > 30000) {

                    throw new \Exception('');
                }
            }
        } catch (\Exception $e) {

        }
        $this->locationRepository->flush();

        $time = microtime(true) - $start;
        var_dump($time);

        $done = $this->fileHandleData->getLastRegion() - $startPos;
        $this->telegramNotifier->notify('done: ' . $done . ' time: ' . $time);
        $this->saveFileHandleData();
    }
}
