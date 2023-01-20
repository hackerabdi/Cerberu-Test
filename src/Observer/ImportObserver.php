<?php

namespace App\Observer;

interface ImportObserver
{
    public function notify(ImportObservable $objSource, string $message): void;
}
