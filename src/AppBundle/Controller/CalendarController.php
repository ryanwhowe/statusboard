<?php declare(strict_types=1);

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Calendar;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class CalendarController extends Controller {

    const TYPE_HOLIDAY = 1;
    const TYPE_PTO = 2;
    const TYPE_SICK = 3;

    /**
     * @Route("/calendar", name="calendar")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $arrival_time = $request->cookies->get('time_sheet_time', '08:00');
        $add_time = $request->cookies->get('time_sheet_add_time', 0);
        $calendarRepository = $this->getDoctrine()->getRepository(Calendar::class);
        $calendarEvents = $calendarRepository->findAll();
        return $this->render('AppBundle:Calendar:index.html.twig', [
            'calendarJson' => $this->formatCalendarEventsJson($calendarEvents),
            'type'         => [
                'pto'     => self::TYPE_PTO,
                'holiday' => self::TYPE_HOLIDAY,
                'sick'    => self::TYPE_SICK,
            ],
            'arrival_time' => $arrival_time,
            'add_time' => $add_time
        ]);
    }

    /**
     * @Route("/utility/calendarUpdate", name="calendarUpdate")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $type = $request->request->get("type");
        $event_date = $request->request->get("event_date");

        try {
            $event = new Calendar();

            $manager = $this->getDoctrine()->getManager();

            $event->setType($type);
            $event_date = \DateTime::createFromFormat('Y-m-d', $event_date);
            $event->setEventDate($event_date);

            $manager->persist($event);
            $manager->flush();

            $this->addFlash('success', 'The event has been added');
        } catch (UniqueConstraintViolationException $e) {
            $this->addFlash('error',
                'The event already exists and could not be added');
        }
        $response = new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl('calendar'));
        return $response;
    }

    /**
     * format the database data into a json object to be consumed by the datepicker
     *
     * @param array $calendarEvents
     * @return false|string
     */
    public static function formatCalendarEventsJson(array $calendarEvents)
    {
        $response = [
            'pto'     => [],
            'holiday' => [],
            'sick'    => []
        ];

        foreach ($calendarEvents as $calendarEvent) {
            switch ($calendarEvent->getType()) {
                case self::TYPE_HOLIDAY:
                    $response['holiday'][] = $calendarEvent->getEventDate()->format("Y-m-d");
                    break;
                case self::TYPE_PTO:
                    $response['pto'][] = $calendarEvent->getEventDate()->format("Y-m-d");
                    break;
                case self::TYPE_SICK:
                    $response['sick'][] = $calendarEvent->getEventDate()->format("Y-m-d");
                    break;
            }
        }

        return \json_encode($response);
    }

}
