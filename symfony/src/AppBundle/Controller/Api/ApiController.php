<?php

namespace AppBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

abstract class ApiController extends Controller {

    /**
     * Parse the request content and return a parameter bag from the content
     *
     * @param Request $request
     *
     * @return ParameterBag
     */
    protected function parseJsonContent(Request $request) {
        $array = [];
        /* the httpfoundation contentType returns a normalized version of the content type */
        if (stripos($request->getContentType(), 'json') !== false) {
            $array = json_decode($request->getContent(), true);
        }
        return new ParameterBag(is_array($array) ? $array : []);
    }


}