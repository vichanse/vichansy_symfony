<?php
/**
 * Created by PhpStorm.
 * User: vichanse
 * Date: 22/02/2018
 * Time: 05:30
 */

namespace Vichansy;


class ContentLengthListener
{
    public function onResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $headers = $response->headers;

        if (!$headers->has('Content-Length') && !$headers->has('Transfer-Encoding')) {
            $headers->set('Content-Length', strlen($response->getContent()));
        }
    }

    public static function getSubscribedEvents()
    {
        return array('response' => array('onResponse', -255));
    }
}