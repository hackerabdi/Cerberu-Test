<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Twig\Environment;

use App\Lib\Importer;
use App\Lib\ProcessData;

class AppController {   

    /** 
    * @Route("/",name="index") 
    */
    public function index(EntityManager $entityManager,Environment $twig, Request $request): void
    {        
        echo $twig->render('index.html.twig');
    }

    /**
     * @Route("/import",name="import")
     */
    public function import(EntityManager $entityManager,Environment $twig, Request $request)
    {
        $obj  = new Importer(dirname(__DIR__, 2)."/files/");//Url can be sent from the UI
        $logs = $obj->entrance($entityManager); 
        echo json_encode($logs);  
    }

    /**
     * @Route("/filter_data",name="filter_data")
     */
    public function filterData(EntityManager $entityManager,Environment $twig, Request $request)
    {
        $req  = $request->request;//get parameters
        $processData = new ProcessData($entityManager);
        $res = $processData->process($req->get('startDate'),$req->get('endDate'));
        echo json_encode($res);
    }

}