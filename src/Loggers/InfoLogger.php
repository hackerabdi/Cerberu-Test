<?php

namespace App\Loggers;

use App\Observer\ImportObservable;
use App\Observer\ImportObserver;
use App\Parser\ReservationsImporter;

class InfoLogger implements ImportObserver
{
    public function notify(ImportObservable $objSource, string $message): void
    {
        if ($objSource instanceof ReservationsImporter) {
            printf('INFO -> %s.' . PHP_EOL, $message);
        }
    }
}
