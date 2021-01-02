<?php


namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Psr\Log\LoggerInterface;
use Statusboard\ControllerHelpers\ApiHelper;
use Statusboard\Utility\Environment;
use Statusboard\Weather\Accuweather\Cache as WeatherCache;
use Statusboard\Weather\Accuweather\Fetcher as AccuweatherFetcher;
use Statusboard\Weather\Accuweather\MockFetcher;
use Statusboard\Weather\Accuweather\RequestLimitExceededException;
use Statusboard\Weather\Accuweather\Transform as Accuweather;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ServerController
 * @Route("/api/weather")
 *
 * @package AppBundle\Controller\Api
 */
class WeatherController extends ApiController {

    /**
     * @Route("/{postal}", name="api_weather_get")
     * @param Request         $request
     * @param LoggerInterface $logger
     * @param string          $postal
     *
     * @return JsonResponse
     * @throws RequestLimitExceededException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function weather(Request $request, LoggerInterface $logger, string $postal) {
        $cache = new WeatherCache($logger);

        if (Environment::isTesting()) {
            $fetcher = new MockFetcher();
            $api_key = '';
            $default_location = '';
        } else {
            $fetcher = new AccuweatherFetcher();
            $api_key = $this->getParameter('accuweather_api_key');
            $default_location = $this->getParameter('accuweather_api_location');
        }

        [$json_response, $body] = ApiHelper::getAccuweatherData(
            $cache,
            $fetcher,
            $api_key,
            $postal,
            $default_location
        );

        $Response = $this->json(\null, $json_response);

        if ($json_response === JsonResponse::HTTP_OK) {
            $output = Accuweather::responseProcessor($body);
            $Response->setContent(json_encode($output));
        }

        $Response->prepare($request)->setPrivate();

        return $Response;
    }

    /**
     * @Route("/{postal}/reset", name="api_weather_reset")
     * @param Request         $request
     * @param LoggerInterface $logger
     * @param string          $postal
     *
     * @return JsonResponse
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function weather_reset(Request $request, LoggerInterface $logger, string $postal) {
        $cache = new WeatherCache($logger);
        $cache->deleteCache($cache::constructCacheType($cache::CACHE_TYPE_LOCATION, $postal));
        $cache->deleteCache($cache::constructCacheType($cache::CACHE_TYPE_WEATHER, $postal));
        $Response = $this->json(['Weather Cache Cleared For "' . $postal . '"'], JsonResponse::HTTP_OK);
        $Response->prepare($request)->setPrivate();
        return $Response;
    }


}