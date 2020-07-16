<?php


namespace Statusboard\ControllerHelpers;


use Statusboard\Utility\Environment;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseHelper {

    /**
     * @param \Exception|null $e
     *
     * @return JsonResponse
     */
    public static function UnknownError(\Exception $e = null) {
        $e = (Environment::isDevelopment()) ? $e : null;
        $e = (Environment::isDevelopment()) ? $e : null;
        return new JsonResponse(
            [
                "message"    => "Unknown Internal Error has occurred",
                "stackTrace" => ($e === null) ? 'unavailable' : $e->getTrace(),
            ],
            JsonResponse::HTTP_SERVICE_UNAVAILABLE);

    }
}