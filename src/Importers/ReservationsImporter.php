<?php

namespace App\Importers;

use App\Observer\ImportObservable;
use App\Observer\ImportObserver;

abstract class ReservationsImporter implements ImportObservable
{
    const EVENT_ITEM_INFO = 1;
    const EVENT_ITEM_ERROR = 2;

    protected $filePath;
    protected $observers = [];

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function addObserver(ImportObserver $objLogger, string $eventType): void
    {
        $this->observers[$eventType][] = $objLogger;
    }

    public function fireEvent(string $eventType, string $message): void
    {
        if (isset($this->observers[$eventType]) && is_array($this->observers[$eventType])) {
            foreach ($this->observers[$eventType] as $objObserver) {
                $objObserver->notify($this, $message);
            }
        }
    }

    abstract function import(): void;
}
