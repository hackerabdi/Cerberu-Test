<?php

namespace App\Lib;

interface ProcessFile
{
    public function getData(string $path): Array;
}