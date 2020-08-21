<?php declare(strict_types=1);


namespace AppBundle\Controller\Api;

use AppBundle\Entity\Calendar;
use AppBundle\Repository\CalendarRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Statusboard\ControllerHelpers\ApiError\CalendarErrors;
use Statusboard\ControllerHelpers\CalendarHelper;
use Statusboard\ControllerHelpers\ResponseHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CalendarController
 * @Route("/api/calendar")
 *
 * @package AppBundle\Controller\Api
 */
class CalendarController extends ApiController {

    /**
     *
     * @Route("/upcoming", name="api_calendar_upcoming")
     * @Method("GET")
     *
     * @param Request $request
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

    /**
     *
     * @Route("", name="api_calendar_getall")
     * @Method("GET")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getCalendarEvents(Request $request) {
        $format = $request->query->get('format', 'byId');

        try {
            switch ($format) {
                case "byDate":
                    $calendarEvents = CalendarHelper::getCalendarData($this->getDoctrine()->getRepository(Calendar::class)->findAll());
                    return $this->json($calendarEvents, JsonResponse::HTTP_OK);
                    break;
                case "byId":
                default:
                    $calendarEvents = $this->getDoctrine()->getRepository(Calendar::class)->findAll();
                    $result = [];
                    /** @var Calendar $calendar */
                    foreach ($calendarEvents as $calendar) {
                        $result[] = $calendar->toArray();
                    }
                    return $this->json($result, JsonResponse::HTTP_OK);
                    break;
            }
        } catch (\Exception $e) {
            return ResponseHelper::UnknownError($e);
        }
    }

    /**
     * @Route("/event", name="api_calendar_create")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createCalendarEvent(Request $request) {

        $post = $this->parseJsonContent($request);

        $type = $post->get('type_id');
        $description = $post->get('description');
        $eventDate = $post->get('date');

        try {

            if ($type !== null && $eventDate !== null) {
                $calendar = new Calendar;
                $calendar->setType($type);
                $calendar->setDescription($description);
                $calendar->setEventDate(new \DateTime($eventDate));
            } else {
                return $this->json([
                    CalendarErrors::response(CalendarErrors::CALENDAR_MISSING_REQUIRED_PARAMETER, ['required' => ['type_id', 'description', 'event_date']], [$type, $description, $eventDate, $request->request->all()]),
                ], JsonResponse::HTTP_BAD_REQUEST);
            }
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($calendar);
                $em->flush();
            } catch (\Exception $e) {
                return $this->json(
                    CalendarErrors::response(CalendarErrors::CALENDAR_DUPLICATE_TYPE_DATE, ['type_id' => $type, 'description' => $description, 'eventDate' => $eventDate]),
                    JsonResponse::HTTP_BAD_REQUEST);
            }
            return $this->redirectToRoute('api_calendar_getone', ['id' => $calendar->getId()]);
        } catch (\Exception $e) {
            return ResponseHelper::UnknownError($e);
        }
    }

    /**
     * @Route("/event/{id}", name="api_calendar_getone")
     * @Method("GET")
     *
     * @param Request $request
     * @param int     $id
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function getCalendarEvent(Request $request, $id) {
        /** @var CalendarRepository $calendarRepository */
        $calendarRepository = $this->getDoctrine()->getRepository(Calendar::class);
        /** @var Calendar $event */
        $event = $calendarRepository->findOneBy(['id' => $id]);
        return $this->json($event->toArray(), JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/event/{id}", name="api_calendar_delete")
     * @Method("DELETE")
     *
     * @param Calendar $calendar
     *
     * @return JsonResponse
     */
    public function deleteCalendarEvent(Calendar $calendar) {
        $id = $calendar->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($calendar);
        $em->flush();
        return $this->json("Calendar Event: " . $id . " removed", JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/event/{id}", name="api_calendar_update")
     * @Method("PATCH")
     *
     * @param Request  $request
     * @param Calendar $calendar
     *
     * @return Response
     */
    public function updateCalendarEvent(Request $request, Calendar $calendar) {

        $patch = $this->parseJsonContent($request);

        $type = $patch->get('type_id');
        $description = $patch->get('description');
        $eventDate = $patch->get('date');

        try {

            if ($type !== null && $eventDate !== null) {
                $calendar->setType($type);
                $calendar->setDescription($description);
                $calendar->setEventDate(new \DateTime($eventDate));
            } else {
                return $this->json([
                    CalendarErrors::response(CalendarErrors::CALENDAR_MISSING_REQUIRED_PARAMETER, ['required' => ['type_id', 'description', 'date']], $patch->all()),
                ], JsonResponse::HTTP_BAD_REQUEST);
            }
            try {
                $this->getDoctrine()->getManager()->flush();
            } catch (\Exception $e) {
                return $this->json(
                    CalendarErrors::response(CalendarErrors::CALENDAR_DUPLICATE_TYPE_DATE, ['type' => $type, 'description' => $description, 'eventDate' => $eventDate]),
                    JsonResponse::HTTP_BAD_REQUEST);
            }
            return new Response(null, Response::HTTP_OK);
        } catch (\Exception $e) {
            return ResponseHelper::UnknownError($e);
        }
    }

}