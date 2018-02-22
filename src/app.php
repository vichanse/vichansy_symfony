<?php
/**
 * Created by PhpStorm.
 * User: vichanse
 * Date: 21/02/2018
 * Time: 07:54
 */
use Symfony\Component\Routing;


$routes = new Routing\RouteCollection();
$routes->add('leap_year', new Routing\Route('/is_leap_year/{year}', array(
    'year' => null,
    '_controller' => 'Calendar\Controller\LeapYearController::indexAction',
)));

return $routes;