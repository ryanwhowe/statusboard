<?php


namespace Statusboard\ControllerHelpers;


use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseHelper {

    /**
     * @param \Exception|null $e
     *
     * @return JsonResponse
     */
    public static function UnknownError(\Exception $e = null) {
        return new JsonResponse(
            [
                "message"    => "Unknown Internal Error has occured",
                "stackTrace" => ($e === null) ? null : $e->getTrace(),
            ],
            JsonResponse::HTTP_SERVICE_UNAVAILABLE);

    }
}