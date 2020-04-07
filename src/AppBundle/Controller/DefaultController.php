<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Repository\CalendarRepository;
use AppBundle\Repository\ServerRepository;
use \DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\Server;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Statusboard\Utility\PayDate;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function indexAction(Request $request)
    {
        $arrival_time = $request->cookies->get('time_sheet_time', '09:00');
        $add_time = $request->cookies->get('time_sheet_add_time', 0);
        /**
         * @var CalendarRepository $calendarRepository;
         */
        $calendarRepository = $this->getDoctrine()->getRepository(Calendar::class);
        /**
         * @var ServerRepository $serverRepository
         */
        $serverRepository = $this->getDoctrine()->getRepository(Server::class);
        $servers = $serverRepository->findAll();

        $nextEvents = self::formatNextEvents($calendarRepository);
        //$nextEvents['Pay Day'] = $this->TrueCar_nextPayDate();

        $calendarEvents = json_encode(self::getCalendarData($this->getDoctrine()->getRepository(Calendar::class)->findAll()));


        return $this->render('AppBundle:Default:index.html.twig', [
            'calendarJson' => $calendarEvents,
            'arrival_time' => $arrival_time,
            'add_time'     => $add_time,
            'servers'      => $servers,
            'events'       => $nextEvents
        ]);
    }

    /**
     * @Route("/calendar", name="calendar")
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function calendarIndexAction(Request $request)
    {
        $arrival_time = $request->cookies->get('time_sheet_time', '09:00');
        $add_time = $request->cookies->get('time_sheet_add_time', 0);
        $calendarEvents = json_encode(self::getCalendarData($this->getDoctrine()->getRepository(Calendar::class)->findAll()));
        return $this->render('AppBundle:Default:calendar.html.twig', [
            'calendarJson' => $calendarEvents,
            'type' => [
                'pto' => Calendar::TYPE_PTO,
                'holiday' => Calendar::TYPE_COMPANY_HOLIDAY,
                'sick' => Calendar::TYPE_SICK,
            ],
            'arrival_time' => $arrival_time,
            'add_time' => $add_time
        ]);
    }

    /**
     * @Route("/utility/calendarUpdate", name="calendarUpdate")
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function calendarUpdateAction(Request $request)
    {
        $type = $request->request->get("type");
        $event_date = $request->request->get("event_date");

        try {
            $event = new Calendar();

            $manager = $this->getDoctrine()->getManager();

            $event->setType($type);
            $event_date = DateTime::createFromFormat('Y-m-d', $event_date);
            $event->setEventDate($event_date);

            $manager->persist($event);
            $manager->flush();

            $this->addFlash('success', 'The event has been added');
        } catch (UniqueConstraintViolationException $e) {
            $this->addFlash('error',
                'The event already exists and could not be added');
        }
        $response = new RedirectResponse($this->generateUrl('calendar'));
        return $response;
    }

    /**
     * @Route("/timeSheet", name="timeSheet")
     * @param $request Request
     *
     * @return Response
     * @throws \Exception
     */
    public function timeSheetAction(Request $request)
    {
        $time = $request->cookies->get('time_sheet_time', '09:00');
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
                    if(
                        $this_date === $calendarEvent['event_date'] &&
                        in_array($calendarEvent['type'],[Calendar::TYPE_COMPANY_HOLIDAY, Calendar::TYPE_PTO, Calendar::TYPE_SICK])
                    ){
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
     * @return RedirectResponse
     * @throws \Exception
     */
    public function timeSheetUpdateAction(Request $request)
    {
        $time = $request->request->get('time');
        $add_time = $request->request->get('add_time');
        $response = new RedirectResponse($this->generateUrl('timeSheet'));
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('time_sheet_time',
            $time, new DateTime('tomorrow')));
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('time_sheet_add_time',
            $add_time, new DateTime('tomorrow')));
        return $response;
    }

    /**
     * @Route("/utility/timeSheetCalendarUpdate")
     * @param Request $request
     *
     * @return RedirectResponse
     * @throws \Exception
     */
    public function timeSheetCalendarUpdateAction(Request $request){
        $mon = $request->request->filter('mon', null, \FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $tue = $request->request->filter('tue', null, \FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $wed = $request->request->filter('wed', null, \FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $thu = $request->request->filter('thu', null, \FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $response = new RedirectResponse($this->generateUrl('timeSheet'));
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('time_sheet_calendar_mon', $mon, new DateTime('next sunday')));
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('time_sheet_calendar_tue', $tue, new DateTime('next sunday')));
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('time_sheet_calendar_wed', $wed, new DateTime('next sunday')));
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('time_sheet_calendar_thu', $thu, new DateTime('next sunday')));
        return $response;
    }

    /**
     * @param CalendarRepository $calendarRepository
     * @return array
     * @throws \Exception
     */
    public static function formatNextEvents(CalendarRepository $calendarRepository): array {
        $eventTypes = [
            Calendar::TYPE_NATIONAL_HOLIDAY => 'Holiday',
            Calendar::TYPE_PTO => 'PTO',
            Calendar::TYPE_PAY_DATE => Calendar::translateTypeDescription(new Calendar(['eventDate' => new DateTime(), 'type' => Calendar::TYPE_PAY_DATE]))
        ];
        $return = [];
        foreach ($eventTypes as $eventType => $eventName) {
            /**
             * @var Calendar $calendar
             */
            $calendars = $calendarRepository->getNextEvent($eventType);

            foreach ($calendars as $calendar) {
                $days_until = date_diff(new DateTime('now'),
                    $calendar->getEventDate());

                $return[$eventName] = [
                    'date' => $calendar->getEventDate()->format('Y-m-d'),
                    'days' => $days_until->format('%a') + 1
                ];
            }
            if (!count($calendars)) {
                $return[$eventName] = [
                    'date' => null,
                    'days' => null
                ];
            }

        }
        return $return;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function getCalendarData($calendars){
        $calendar_data = [];

        /**
         * @var Calendar $calendar
         */
        foreach ($calendars as $calendar) {
            $event_date = $calendar->getEventDate()->format('Y-m-d');
            if(isset($calendar_data[$event_date])){
                $calendar_data[$event_date]['events'][] = ['type' => $calendar->getType(), 'description' => Calendar::translateTypeDescription($calendar)];
            } else {
                $calendar_data[$event_date] = ['events' => [['type' => $calendar->getType(), 'description' => Calendar::translateTypeDescription($calendar)]]];
            }
        }
        return $calendar_data;
    }

}
