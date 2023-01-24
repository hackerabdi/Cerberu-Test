<?php

namespace App\Lib;

use App\Importers\ReservationsImporter;
use App\Loggers\ErrorLogger;
use App\Loggers\InfoLogger;
use App\Lib\ProcessJson;
use App\Lib\ProcessXml;
use Doctrine\ORM\EntityManager;
use App\Lib\ReservationProcess;

class Importer extends ReservationsImporter
{
    const START_IMPORTING = "Start importing";
    const END_IMPORTING = "End importing";
    const RESERVATION_IMPORTED = "Imported reservation";
    const RESERVATION_DUPLICATED = "Duplicated reservation";
    const FILE_NOT_FOUND = "File not found";
    const FILE_EMPTY = "Empty file";
    const INCORRECT_ITEM = "Incorrect item/reservation";
    const SYSTEM_ERROR = "System Error";

    private $errorLogger;
    private $infoLogger;
    private $reservations = [];
    private $options = [];
    private $logs = [];
    private $reservationProcess;
    private $entityManager;


    public function __construct(string $filePath)
    {        
        parent::__construct($filePath);
        $this->errorLogger = new ErrorLogger();
        $this->infoLogger = new InfoLogger();
        $this->options = array('json','xml');
        $this->addObservers();
    } 
    
    public function entrance(EntityManager $entityManager): Array
    {
        $this->entityManager = $entityManager;
        $this->reservationProcess = new ReservationProcess($this->entityManager,$this);
        $this->import();
        return $this->logs;
    }

    public function getLogs()
    {
        return $this->logs;
    }

    public function import(): void
    {
        
        foreach($this->options as $option){
            $this->addLog(self::START_IMPORTING, $option." importation");
            $path = $this->getFilePath()."reservations.".$option;
            if(!filesize($path)){ 
                $this->addLog(self::FILE_NOT_FOUND, "reservations.".$option." has not been found");
                break;
            }
            elseif(filesize($path) == 0){
                $this->addLog(self::FILE_EMPTY, "reservations.".$option." exists, but is empty");
                break; 
            }
            else{
                $res = [];
                if($option == "json")
                    $obj = new ProcessJson();                
                else
                    $obj = new ProcessXml();

                $this->reservationProcess->process($obj->getData($path));
                
            }
            $this->addLog(self::END_IMPORTING, $option." importation");
        }
    }

    public function addLog(string $event_type,string $message): void
    {
        $this->fireEvent($event_type, $message);
        array_push($this->logs,array("topic" => $event_type,"message" => $message));
    }

    private function addObservers()
    {
        $this->addObserver($this->infoLogger, self::START_IMPORTING);
        $this->addObserver($this->infoLogger, self::END_IMPORTING);
        $this->addObserver($this->infoLogger, self::RESERVATION_IMPORTED);
        $this->addObserver($this->errorLogger, self::RESERVATION_DUPLICATED);
        $this->addObserver($this->errorLogger, self::FILE_NOT_FOUND);
        $this->addObserver($this->errorLogger, self::INCORRECT_ITEM);
        $this->addObserver($this->errorLogger, self::FILE_EMPTY);
    }

}