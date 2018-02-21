<?php
/**
 * Created by PhpStorm.
 * User: vichanse
 * Date: 21/02/2018
 * Time: 07:54
 */
use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();
$routes->add('hello', new Routing\Route('/hello/{name}', array('name' => 'World')));
$routes->add('bye', new Routing\Route('/bye'));

return $routes;