<?php

namespace App\Helper;


class JsonHandler
{
    protected \SplFileObject $fileHandler;

    protected int $offset = 0;

    public function setFile(string $filePath, $offset = 0): void
    {
        $this->fileHandler = new \SplFileObject($filePath);
        $this->fileHandler->seek($offset);
        $this->offset = $offset;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getItem(bool $asString = false): array|string
    {
        $item = $this->fileHandler->current();
        if ($this->fileHandler->eof()) {
            return [];
        }

        $this->fileHandler->next();
        $this->offset = $this->fileHandler->key();
        if ($asString) {
            return $item;
        }

        return json_decode($item, true, 512, JSON_THROW_ON_ERROR);
    }
}
