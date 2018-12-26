<?php declare(strict_types=1);

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\Server;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    const CALENDAR_TYPE_HOLIDAY = 1;
    const CALENDAR_TYPE_PTO = 2;
    const CALENDAR_TYPE_SICK = 3;

    /**
     * @Route("/", name="homepage")
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function indexAction(Request $request)
    {
        $arrival_time = $request->cookies->get('time_sheet_time', '08:00');
        $add_time = $request->cookies->get('time_sheet_add_time', 0);
        /**
         * @var \AppBundle\Repository\CalendarRepository $calendarRepository;
         */
        $calendarRepository = $this->getDoctrine()->getRepository(Calendar::class);
        $calendarEvents = $calendarRepository->findAll();
        $serverRepository = $this->getDoctrine()->getRepository(Server::class);
        $servers = $serverRepository->findAll();

        $nextEvents = [];
        $eventTypes = [self::CALENDAR_TYPE_HOLIDAY, self::CALENDAR_TYPE_PTO, self::CALENDAR_TYPE_SICK];
        foreach ($eventTypes as $eventType){
            switch($eventType){
                case self::CALENDAR_TYPE_SICK:
                    $eventName = 'Sick';
                    break;
                case self::CALENDAR_TYPE_PTO:
                    $eventName = 'PTO';
                    break;
                case self::CALENDAR_TYPE_HOLIDAY:
                    $eventName = 'Holiday';
                    break;
            }

            /**
             * @var Calendar $calendar
             */
            $calendars = $calendarRepository->getNextEvent($eventType);

            foreach ($calendars as $calendar) {
                $days_until = date_diff(new \DateTime('now'),
                    $calendar->getEventDate());

                $nextEvents[$eventName] = [
                    'date'  => $calendar->getEventDate()->format('Y-m-d'),
                    'days' => $days_until->format('%a')
                ];
            }
            if(!count($calendars)){
                $nextEvents[$eventName] = [
                    'date'  => null,
                    'days' => null
                ];
            }

        }

        return $this->render('AppBundle:Default:index.html.twig', [
            'calendarJson' => $this->formatCalendarEventsJson($calendarEvents),
            'type'         => [
                'pto' => self::CALENDAR_TYPE_PTO,
                'holiday' => self::CALENDAR_TYPE_HOLIDAY,
                'sick' => self::CALENDAR_TYPE_SICK,
            ],
            'arrival_time' => $arrival_time,
            'add_time'     => $add_time,
            'servers'      => $servers,
            'events'       => $nextEvents
        ]);
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction()
    {
        return $this->render('AppBundle:Default:admin.html.twig');
    }

    /**
     * @Route("/calendar", name="calendar")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function calendarIndexAction(Request $request)
    {
        $arrival_time = $request->cookies->get('time_sheet_time', '08:00');
        $add_time = $request->cookies->get('time_sheet_add_time', 0);
        $calendarRepository = $this->getDoctrine()->getRepository(Calendar::class);
        $calendarEvents = $calendarRepository->findAll();
        return $this->render('AppBundle:Default:calendar.html.twig', [
            'calendarJson' => $this->formatCalendarEventsJson($calendarEvents),
            'type' => [
                'pto' => self::CALENDAR_TYPE_PTO,
                'holiday' => self::CALENDAR_TYPE_HOLIDAY,
                'sick' => self::CALENDAR_TYPE_SICK,
            ],
            'arrival_time' => $arrival_time,
            'add_time' => $add_time
        ]);
    }

    /**
     * @Route("/utility/calendarUpdate", name="calendarUpdate")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function calendarUpdateAction(Request $request)
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
     *
     * @return false|string
     */
    protected static function formatCalendarEventsJson(array $calendarEvents)
    {
        $response = [
            'pto' => [],
            'holiday' => [],
            'sick' => []
        ];

        foreach ($calendarEvents as $calendarEvent) {
            switch ($calendarEvent->getType()) {
                case self::CALENDAR_TYPE_HOLIDAY:
                    $response['holiday'][] = $calendarEvent->getEventDate()->format("Y-m-d");
                    break;
                case self::CALENDAR_TYPE_PTO:
                    $response['pto'][] = $calendarEvent->getEventDate()->format("Y-m-d");
                    break;
                case self::CALENDAR_TYPE_SICK:
                    $response['sick'][] = $calendarEvent->getEventDate()->format("Y-m-d");
                    break;
            }
        }

        return \json_encode($response);
    }

    /**
     * @Route("/timeSheet", name="timeSheet")
     * @param $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function timeSheetAction(Request $request)
    {
        $time = $request->cookies->get('time_sheet_time', '08:00');
        $add_time = $request->cookies->get('time_sheet_add_time', 0);
        $this_week = [
            'monday'    => [
                'value'      => $request->cookies->filter('time_sheet_calendar_mon', null, \FILTER_SANITIZE_NUMBER_FLOAT,  FILTER_FLAG_ALLOW_FRACTION),
                'is_holiday' => false
            ],
            'tuesday'   => [
                'value'      => $request->cookies->filter('time_sheet_calendar_tue', null, \FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                'is_holiday' => false
            ],
            'wednesday' => [
                'value'      => $request->cookies->filter('time_sheet_calendar_wed', null, \FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                'is_holiday' => false
            ],
            'thursday'  => [
                'value'      => $request->cookies->filter('time_sheet_calendar_thu', null, \FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                'is_holiday' => false
            ],
            'friday'    => [
                'value'      => null,
                'is_holiday' => false
            ]
        ];

        /**
         * @var \AppBundle\Repository\CalendarRepository $calendarRepository
         */
        $calendarRepository = $this->getDoctrine()->getRepository(Calendar::class);
        $calendarEvents = $calendarRepository->findAllThisWeek();

        if(count($calendarEvents)){
            $current_day = date('N');
            $days_from_monday = $current_day - 1;
            $monday = date('Y-m-d', strtotime("- {$days_from_monday} Days"));
            $i = 0;
            foreach ($this_week as $day => &$day_data) {
                $this_date = date('Y-m-d', strtotime("{$monday} + {$i} Days"));
                /**
                 * @var Calendar $calendarEvent
                 */
                foreach ($calendarEvents as $calendarEvent) {
                    if($this_date === $calendarEvent['event_date']){
                        $day_data['is_holiday'] = true;
                        $day_data['value'] = 8.0;
                    }
                }
                $i++;
            }
        }

        return $this->render('AppBundle:Default:time_sheet.html.twig', [
            'time' => $time,
            'add_time' => $add_time,
            'calendar' => $this_week
        ]);
    }

    /**
     * @Route("/utility/timeSheetUpdate", name="timeSheetUpdate")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function timeSheetUpdateAction(Request $request)
    {
        $time = $request->request->get('time');
        $add_time = $request->request->get('add_time');
        $response = new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl('timeSheet'));
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('time_sheet_time',
            $time, new \DateTime('tomorrow')));
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('time_sheet_add_time',
            $add_time, new \DateTime('tomorrow')));
        return $response;
    }

    /**
     * @Route("/utility/timeSheetCalendarUpdate")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function timeSheetCalendarUpdateAction(Request $request){
        $mon = $request->request->filter('mon', null, \FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $tue = $request->request->filter('tue', null, \FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $wed = $request->request->filter('wed', null, \FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $thu = $request->request->filter('thu', null, \FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $response = new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl('timeSheet'));
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('time_sheet_calendar_mon', $mon, new \DateTime('next sunday')));
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('time_sheet_calendar_tue', $tue, new \DateTime('next sunday')));
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('time_sheet_calendar_wed', $wed, new \DateTime('next sunday')));
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('time_sheet_calendar_thu', $thu, new \DateTime('next sunday')));
        return $response;
    }

}
