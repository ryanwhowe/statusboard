<?php


namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Psr\Log\LoggerInterface;
use Statusboard\ControllerHelpers\ApiHelper;
use Statusboard\Mbta\Cache as MbtaCache;
use Statusboard\Mbta\Fetcher as MbtaFetcher;
use Statusboard\Mbta\Transform as Mbta;
use Statusboard\Utility\Environment;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ServerController
 * @Route("/api/mbta")
 *
 * @package AppBundle\Controller\Api
 */
class MbtaController extends ApiController {

    /**
     * @Route("", name="api_mbta_get")
     * @param Request         $request
     * @param LoggerInterface $logger
     *
     * @return JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function mbta(Request $request, LoggerInterface $logger) {
        $cache = new MbtaCache($logger);

        if (Environment::isTesting()) {
            $fetcher = new \Statusboard\Mbta\MockFetcher();
            $api_key = '';
        } else {
            $api_key = $this->getParameter('mbta_api_key');
            $fetcher = new MbtaFetcher();
        }

        [$schedule, $json_response] = ApiHelper::getMbtaData($cache, $fetcher, $api_key);

        if ($json_response === JsonResponse::HTTP_OK) {
            $Response = $this->json(\null, $json_response);
            $output = Mbta::responseProcessor($schedule);
            $Response->setContent(json_encode($output));
            $Response->headers->add(["Access-Control-Allow-Origin" => "*"]);
        } elseif ($json_response === JsonResponse::HTTP_NO_CONTENT) {
            $Response = $this->json(\null, $json_response);
            $Response->setContent(json_encode([]));
        } else {
            $Response = $this->json(\null, $json_response,
                ['Content-Type' => 'text/html', 'Cache-control' => 'must-revalidate']);
            $Response->setContent("<h3>There was an internal error retrieving the schedule from the MBTA server</h3>");
        }

        $Response->prepare($request)->setPrivate();
        return $Response;
    }

    /**
     * @Route("/reset", name="api_mbta_reset")
     * @param Request         $request
     * @param LoggerInterface $logger
     *
     * @return JsonResponse
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function mbta_reset(Request $request, LoggerInterface $logger) {
        $cache = new MbtaCache($logger);
        $cache->deleteCache($cache::CACHE_TYPE_SCHEDULE);
        $Response = $this->json(['MBTA Cache Cleared'], JsonResponse::HTTP_OK,
            ['Cache-control' => 'must-revalidate']);
        $Response->prepare($request)->setPrivate();
        return $Response;
    }

}