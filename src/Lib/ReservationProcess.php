<?php

namespace App\Lib;

use Doctrine\ORM\EntityManager;
use App\Entity\Reservation;
use App\Lib\Importer;
use App\Lib\MyCustomException;

class ReservationProcess
{    
    private $entityManager;
    private $importer;

    public function __construct(EntityManager $entityManager,Importer $importer)
    {
        $this->entityManager = $entityManager;
        $this->importer = $importer;
    }

    public function process(Array $reservations)
    {
        foreach($reservations as $reservation){
            if($reservation['title'] == "" 
            || $reservation['link'] == "" 
            || !is_string($reservation['title']) 
            || !is_string($reservation['link']) ){
                $this->importer->addLog(Importer::INCORRECT_ITEM,"Item has no title or link");  
                continue;              
            }            
            else{
                try{
                    $reserv = $this->entityManager->getRepository('App\Entity\Reservation')->findOneBy(array("title" => $reservation['title']));
                    if($reserv)
                        throw new MyCustomException($reserv->getTitle()." already in database");
                }
                catch(MyCustomException $e){
                    $this->importer->addLog(Importer::RESERVATION_DUPLICATED,$e->getMessage());
                    continue;
                } 
                catch(\Exception $e){
                    $this->importer->addLog(Importer::SYSTEM_ERROR,$e->getMessage());
                    continue;
                }               
            }
            $this->createReservation($reservation);
        }
    }

    private function createReservation(Array $reservation)
    {
        try{
            $newReservation = new Reservation();
            $newReservation->setTitle( $reservation['title'] );          
            $newReservation->setLink( $reservation['link'] );
            $newReservation->setPax( intval($reservation['pax']) );
            $newReservation->setTotal( floatval($reservation['total']) );
            $newReservation->setPubDate( new \Datetime($reservation['pubDate']) );
            $this->entityManager->persist( $newReservation );
            $this->entityManager->flush();
            $this->importer->addLog(Importer::RESERVATION_IMPORTED,$reservation['title']." imported successfully");
        }
        catch(\Exception $e){
            $this->importer->addLog(Importer::SYSTEM_ERROR,"Adding: ".$reservation['title']." to database");
        }
        catch (\Throwable $e) {
            $this->importer->addLog(Importer::SYSTEM_ERROR,"Adding: ".$reservation['title']." to database");
        }
    }

    
}