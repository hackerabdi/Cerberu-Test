<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AppController{   

    /** 
    * @Route("/",name="index") 
    */
    public function index($twig){
        
        echo $twig->render('index.html.twig',array("lolo" => "Texto" ));

    }
}