<?php
/**
 * Created by PhpStorm.
 * User: vichanse
 * Date: 21/02/2018
 * Time: 07:10
 */
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;

$request = Request::createFromGlobals();
$requestStack = new RequestStack();
$routes = include __DIR__.'/../src/app.php';

$context = new Routing\RequestContext();
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

$controllerResolver = new HttpKernel\Controller\ControllerResolver();
$argumentResolver = new HttpKernel\Controller\ArgumentResolver();

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new HttpKernel\EventListener\RouterListener($matcher, $requestStack));

$errorHandler = function (Symfony\Component\Debug\Exception\FlattenException $exception) {
    $msg = 'Something went wrong! ('.$exception->getMessage().')';

    return new Response($msg, $exception->getStatusCode());
};
$dispatcher->addSubscriber(new HttpKernel\EventListener\ExceptionListener($errorHandler));

$listener = new HttpKernel\EventListener\ExceptionListener(
    'Calendar\Controller\ErrorController::exceptionAction'
);
$dispatcher->addSubscriber($listener);

$dispatcher->addSubscriber(new Vichansy\StringResponseListener());

$framework = new Vichansy\Framework($dispatcher, $controllerResolver, $requestStack, $argumentResolver);

$response = $framework->handle($request);
$response->send();