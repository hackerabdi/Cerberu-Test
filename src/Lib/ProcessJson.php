<?php

namespace App\Lib;

use App\Lib\ProcessFile;

class ProcessJson implements ProcessFile
{
    private $reservations = [];

    public function getData($path): Array
    {
        try{
            $json_data = file_get_contents($path);
            $data = json_decode($json_data, true);
            foreach($data as $row)
            {
                array_push($this->reservations,$row);
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