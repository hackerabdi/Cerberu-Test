<?php

namespace App\Lib;

use App\Lib\ProcessFile;

class ProcessXml implements ProcessFile
{
    private $reservations = [];
    
    public function getData($path): Array
    {
        try{
            $content = file_get_contents($path);
            $data = simplexml_load_string($content,'SimpleXMLElement', LIBXML_NOCDATA);
            $array = json_decode(json_encode((array)$data), TRUE);
            foreach($array['reservation'] as $row => $key)
            {
                array_push($this->reservations, $key);
            } 
        }
        catch(\Exception $e){
            $this->importer->addLog(Importer::SYSTEM_ERROR,"File is corrupt");
        }
        catch (\Throwable $e) {
            $this->importer->addLog(Importer::SYSTEM_ERROR,"File is corrupt");
        }
        
        return $this->reservations;
    }
}