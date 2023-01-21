<?php
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Routing\AnnotatedRouteControllerLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader;
use Composer\Autoload\ClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;


$loader = require __DIR__ . '/vendor/autoload.php';

// Specify our Twig templates location
$loader = new Twig_Loader_Filesystem(__DIR__.'/templates');

// Instantiate our Twig
$twig = new Twig_Environment($loader);

$loader = new AnnotationDirectoryLoader(
    new FileLocator(__DIR__ . '/src/Controller/'),
    new AnnotatedRouteControllerLoader(
        new AnnotationReader()
    )
);
$routes = $loader->load(__DIR__ . '/src/Controller/');
$context = new RequestContext();
$context->fromRequest(Request::createFromGlobals());
$matcher = new UrlMatcher($routes, $context);
$parameters = $matcher->match($context->getPathInfo());
$controllerInfo = explode('::',$parameters['_controller']);
$controller = new $controllerInfo[0];
$action = $controllerInfo[1];
$controller->$action($twig);