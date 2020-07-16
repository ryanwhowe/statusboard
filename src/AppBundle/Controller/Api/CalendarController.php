<?php declare(strict_types=1);


namespace AppBundle\Controller\Api;

use AppBundle\Entity\Calendar;
use AppBundle\Repository\CalendarRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Statusboard\ControllerHelpers\CalendarHelper;
use Statusboard\ControllerHelpers\ResponseHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CalendarController
 * @Route("/api/calendar")
 *
 * @package AppBundle\Controller\Api
 */
class CalendarController extends Controller {

    /**
     * @param Request $request
     * @Route("/upcoming", name="api_calendar_upcoming")
     * @Method("GET")
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function getUpcoming(Request $request) {
        try {
            /** @var CalendarRepository $calendarRepository */
            $calendarRepository = $this->getDoctrine()->getRepository(Calendar::class);
            $nextEvents = CalendarHelper::getUpcomingEvents($calendarRepository);
            return $this->json($nextEvents, JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return ResponseHelper::UnknownError($e);
        }
    }


}