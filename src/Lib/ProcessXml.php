<?php

namespace App\Lib;

use App\Lib\ProcessFile;

class ProcessXml implements ProcessFile
{
    private $reservations = [];
    
    public function getData($path): Array
    {
        $content = file_get_contents($path);
        $data = simplexml_load_string($content,'SimpleXMLElement', LIBXML_NOCDATA);
        $array = json_decode(json_encode((array)$data), TRUE);
        foreach($array['reservation'] as $row => $key)
        {
            array_push($this->reservations, $key);
        } 
        
        return $this->reservations;
    }
}