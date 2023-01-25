<?php

use App\Lib\Importer;
use App\Lib\ProcessData;
use Doctrine\ORM\EntityManager;

require __DIR__ . '/vendor/autoload.php';
require __DIR__.'/config/bootstrap.php';

/**
 * You can start writing code here. Please DO NOT CHANGE ANY SKELETON CLASSES.
 * You can create as many files, folders and classes as you need.
 *
 * You might notice that we are using Composer.
 */

do {
    $cmd = trim(strtolower( readline("\n> Enter Command('exit', 'import', 'getdata'): ") ));
    readline_add_history($cmd);
    switch ($cmd) {
        case 'import': 
            {
                $url = trim(strtolower( readline("\n> Enter url for files: ") ));
                print processLogs(callImport($url,$entityManager));
                break;
            }
        case 'getdata':
            {
                $flag = false;
                while(!$flag){
                    $date = trim(strtolower( readline("\n> Enter initial date(Format-> yyyy-mm-dd): ") ));
                    $flag  = verifyDate($date);
                    if(!$flag)
                    print "Date not valid, enter a valid one \n";
                }                
                print processResultData(callData($date,$entityManager));
                break;
            }
        case 'exit': break;
        default: print "\n -- You entered '$cmd'... say 'exit' to exit program 'import' or 'getdata'.\n";
    }
} while ($cmd!='exit');

function callImport(string $url, EntityManager $entityManager)
    {
    $url = __DIR__.$url;
    $obj = new Importer($url);
    return $obj->entrance($entityManager);
}

function callData(string $date,EntityManager $entityManager)
{
    $startDate = date('Y-m-d', strtotime($date));
    $endDate = date('Y-m-d', strtotime('+1 week', strtotime($startDate)));
    $processData = new ProcessData($entityManager);
    return $processData->process($startDate,$endDate);
}

function processLogs(array $logs)
{
    $str = "";
    foreach($logs as $log){
        $str .= $log['topic']." - ".$log['message']."\n";
    }

    return $str;
}

function processResultData(array $arr)
{
    $str = "Week Summary: \n";
    foreach($arr as $el => $key){
        $str .= "    ".ucfirst($el).": ";
        $arr1 = $arr[$el];
        foreach($arr1 as $sub => $fin ){
            $str .= ucfirst($sub).": ".$fin;
            if (next($arr1)==true) $str .= " - ";
        }
        $str .= " \n";
    }
    return $str;
}

function verifyDate(string $date)
{
    $arr = explode("-", $date);
    return checkdate($arr[1], $arr[2], $arr[0]);
}