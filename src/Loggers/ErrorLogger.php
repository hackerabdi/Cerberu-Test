<?php

namespace App\Logger;

use App\Observer\ImportObservable;
use App\Observer\ImportObserver;
use App\Parser\ReservationsImporter;

class ErrorLogger implements ImportObserver
{
    public function notify(ImportObservable $objSource, string $message): void
    {
        if ($objSource instanceof ReservationsImporter) {
            printf('ERROR -> %s.' . PHP_EOL, $message);
        }
    }
}
