<?php

namespace App\Lib;

use Doctrine\ORM\EntityManager;

class ProcessData
{

    private $startDate;
    private $endDate;
    private $reservations = [];
    private $entityManager;
    private $options = [];

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->options = ['pax','total'];
    }

    public function process(string $startDate, string $endDate)
    {
        $result = array();
        $this->reservations = $this->getData($startDate, $endDate);
        foreach($this->options as $option)
        {
            $this->orderReservations($option,$result);
        }
        return $result;
    }

    private function getData(string $startDate, string $endDate)
    {
        $q = $this->entityManager->createQuery("SELECT u FROM App\Entity\Reservation u WHERE u.pubDate BETWEEN ?1 AND ?2");//WHERE u.pubDate BETWEEN ?1 AND ?2'
        $q->setParameter(1, $startDate);
        $q->setParameter(2, $endDate);
        return  $q->getResult();
    }

    private function orderReservations(string $field, Array &$result)
    {
        $length = count($this->reservations);
        $sum = 0;
        $methodName = "get".ucfirst($field);
        $result[$field]['min'] = 0;
        $result[$field]['avg'] = 0;
        $result[$field]['max'] = 0;
        for( $i = 0; $i < $length -1; $i++ ){            
            for( $j = $i+1; $j < $length; $j++ ){
                if($this->reservations[$i]->$methodName() < $this->reservations[$j]->$methodName()){
                    $temp  = $this->reservations[$i];
                    $this->reservations[$i] = $this->reservations[$j];
                    $this->reservations[$j] = $temp;
                }
            }            
            $sum += $this->reservations[$i]->$methodName();
        }
        if($length != 0){
            $result[$field]['min'] = $this->reservations[$length - 1]->$methodName();
            $result[$field]['avg'] = round(floatval($sum / $length), 2);
            $result[$field]['max'] = $this->reservations[0]->$methodName();
        }
    }
}