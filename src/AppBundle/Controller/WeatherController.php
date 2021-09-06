<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class WeatherController extends Controller {

    /**
     * @Route("/weather/history", name="weather_history")
     */
    public function weatherHistory(): ?Response {
        return $this->render('AppBundle:Weather:history.html.twig',[
            'baseUrl' => $this->getParameter('api_basepath'),
            'authToken' => $this->getParameter('theaxerant_api_token')
        ]);

    }
}