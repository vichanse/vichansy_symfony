<?php
/**
 * Created by PhpStorm.
 * User: vichanse
 * Date: 22/02/2018
 * Time: 04:39
 */

namespace Vichansy;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;


use Symfony\Component\HttpKernel\HttpKernel;

class Framework extends HttpKernel
{
    protected $dispatcher;
    protected $resolver;
    protected $requestStack;
    private $argumentResolver;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        ControllerResolverInterface $resolver,
        RequestStack $requestStack,
        ArgumentResolverInterface $argumentResolver)
    {
        $this->dispatcher = $dispatcher;
        $this->resolver = $resolver;
        $this->requestStack = $requestStack;
        $this->argumentResolver = $argumentResolver;
        parent::__construct($dispatcher, $resolver, $requestStack, $argumentResolver);


    }

    public function handle(
        Request $request,
        $type = HttpKernelInterface::MASTER_REQUEST,
        $catch = true
    )
    {
        $this->matcher->getContext()->fromRequest($request);

        try {
            $request->attributes->add($this->matcher->match($request->getPathInfo()));

            $controller = $this->controllerResolver->getController($request);
            $arguments = $this->argumentResolver->getArguments($request, $controller);

            $response = call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $e) {
            $response = new Response('Not Found', 404);
        } catch (\Exception $e) {
            $response = new Response('An error occurred', 500);
        }

        // dispatch a response event

        $this->dispatcher->dispatch('response', new ResponseEvent($response, $request));

        return $response;
    }
}