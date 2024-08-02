<?php

namespace App\Helper;

use App\Enum\FileCutType;

class FileCutter
{
    protected const PIECE_SIZE = 100000;

    protected int $basicOffset = 0;

    protected string $storageDir;

    protected string $filePath;

    protected JsonHandler $jsonHandler;

    protected \SplFileObject $currentFileHandler;



    public function __construct(string $storageDir, JsonHandler $jsonHandler)
    {
        $this->storageDir = $storageDir;
        $this->jsonHandler = $jsonHandler;
    }

    public function setFile(string $filePath, int $offset, FileCutType $cutType): FileCutter
    {
        $this->jsonHandler->setFile($filePath, $offset);
        $this->basicOffset = $offset;
        $this->filePath = $this->storageDir . DIRECTORY_SEPARATOR . $cutType->name . '_current';

        // Check if the file exists before attempting to delete it
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
//        unlink($this->filePath);

        return $this;
    }

    public function getOffset(): int
    {
        return static::PIECE_SIZE;
    }

    protected function initCurrentFile()
    {
        $this->currentFileHandler = new \SplFileObject($this->filePath, 'w+');
    }

    public function sliceCurrentFile()
    {
        $this->initCurrentFile();
        try {
            while ($item = $this->jsonHandler->getItem(true)) {
                $this->currentFileHandler->fwrite($item);
                $this->currentFileHandler->fflush();
                clearstatcache();
                if ($this->jsonHandler->getOffset() >= (static::PIECE_SIZE + $this->basicOffset)) {
                    return;
                }
            }
        } catch (\Exception $e) {

        }
    }
}
