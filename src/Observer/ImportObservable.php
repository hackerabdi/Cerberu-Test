<?php

namespace App\Observer;

interface ImportObservable
{
    public function addObserver(ImportObserver $objObserver, string $eventType): void;

    public function fireEvent(string $eventType, string $message): void;
}
