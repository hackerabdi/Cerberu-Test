<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;


// bootstrap.php
require_once "vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration
$config = ORMSetup::createAnnotationMetadataConfiguration(
    array(__DIR__."/../src"),
    true
 );

// configuring the database connection
$connection = DriverManager::getConnection([
    'driver'   => 'pdo_mysql',
    'user'     => 'user',
    'password' => 'pass',
    'dbname'   => 'test',
], $config);

// obtaining the entity manager
$entityManager = new EntityManager($connection, $config);