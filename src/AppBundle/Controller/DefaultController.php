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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    const ONE_DAY_SECONDS = 86400;

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

        $nextEvents = $this->formatNextEvents($calendarRepository);
        //$nextEvents['Pay Day'] = $this->TrueCar_nextPayDate();

        $calendarEvents = json_encode($this->getCalendarData());


        return $this->render('AppBundle:Default:index.html.twig', [
            'calendarJson' => $calendarEvents,
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
        $arrival_time = $request->cookies->get('time_sheet_time', '09:00');
        $add_time = $request->cookies->get('time_sheet_add_time', 0);
        $calendarEvents = json_encode($this->getCalendarData());
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
            $event_date = DateTime::createFromFormat('Y-m-d', $event_date);
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


//    /**
//     * format the database data into a json object to be consumed by the datepicker
//     *
//     * @param array $calendarEvents
//     *
//     * @return false|string
//     */
//    protected static function formatCalendarEventsJson(array $calendarEvents)
//    {
//        $response = [
//            'pto' => [],
//            'holiday' => [],
//            'sick' => []
//        ];
//
//        foreach ($calendarEvents as $calendarEvent) {
//            switch ($calendarEvent->getType()) {
//                case Calendar::TYPE_COMPANY_HOLIDAY:
//                    $response['holiday'][] = $calendarEvent->getEventDate()->format("Y-m-d");
//                    break;
//                case Calendar::TYPE_PTO:
//                    $response['pto'][] = $calendarEvent->getEventDate()->format("Y-m-d");
//                    break;
//                case Calendar::TYPE_SICK:
//                    $response['sick'][] = $calendarEvent->getEventDate()->format("Y-m-d");
//                    break;
//            }
//        }
//
//        return \json_encode($response);
//    }

    /**
     * @Route("/timeSheet", name="timeSheet")
     * @param $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
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
            $time, new DateTime('tomorrow')));
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('time_sheet_add_time',
            $add_time, new DateTime('tomorrow')));
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
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('time_sheet_calendar_mon', $mon, new DateTime('next sunday')));
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('time_sheet_calendar_tue', $tue, new DateTime('next sunday')));
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('time_sheet_calendar_wed', $wed, new DateTime('next sunday')));
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('time_sheet_calendar_thu', $thu, new DateTime('next sunday')));
        return $response;
    }

//    /**
//     * Calculate the next pay date, this is bases on the current cycle of being paid on the friday of even calendar
//     * weeks numbers.
//     *
//     * @return array
//     * @throws \Exception
//     */
//    protected function Ives_nextPayDate(){
//        $pay_friday = strtotime("this friday");
//
//        if(date('W',$pay_friday) % 2 === 0) {
//            $pay_friday = strtotime("+1 week ". date('Y-m-d', $pay_friday));
//        }
//        $days_until = date_diff(
//            new DateTime('now'),
//            new DateTime(date('Y-m-d',$pay_friday))
//        );
//        return [
//            'date' => date('Y-m-d', $pay_friday),
//            'days' => $days_until->format('%a') + 1
//        ];
//    }

//    /**
//     * @return array
//     * @throws \Exception
//     */
//    public function TrueCar_nextPayDate(){
//        $holidays = [];
//        return $this->nextPayDateFourteenthSecondToLast(new Datetime('now'), $holidays);
//    }
//
//
//    public function nextPayDateFourteenthSecondToLast(DateTime $now, array $holidays){
//        $fourteenth = new DateTime ($now->format('Y') . "-" . $now->format('m') . "-14");
//        $fourteenth_pay_day = self::checkPayDate($fourteenth, $holidays);
//
//        if($now->format('d') <= $fourteenth_pay_day->format('d')){
//            $pay_date = $fourteenth_pay_day;
//        } else {
//            $second_to_last_day = new DateTime($now->format('Y') . '-' . $now->format('m' . '-' . $now->format('t')-1));
//            $second_to_last_pay_day = self::checkPayDate($second_to_last_day, $holidays);
//            $pay_date = $second_to_last_pay_day;
//        }
//
//        $days_until = date_diff($now, $pay_date);
//
//        return [
//            'date' => $pay_date->format('Y-m-d'),
//            'days' => $days_until->format('%a') + 1
//        ];
//    }
//
//    public static function checkPayDate(DateTime $pay_date, array $holidays){
//        $moved = false;
//        $new_date = $pay_date;
//        if(in_array(date('w', $pay_date->getTimeStamp()), [0,6])){
//            $new_date = new DateTime(date('Y-m-d',$pay_date->getTimestamp() - self::ONE_DAY_SECONDS));
//            $moved = true;
//        }
//        if(!$moved){
//            if(in_array($pay_date->format('Y-m-d'), $holidays)){
//                $new_date = new DateTime(date('Y-m-d',$pay_date->getTimestamp() - self::ONE_DAY_SECONDS));
//            }
//        }
//        if($new_date === $pay_date) return $new_date;
//        return self::checkPayDate($new_date, $holidays);
//    }

    /**
     * @param CalendarRepository $calendarRepository
     * @return array
     * @throws \Exception
     */
    private function formatNextEvents(CalendarRepository $calendarRepository): array {
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
    public function getCalendarData(){
        $calendars = $this->getDoctrine()->getRepository(Calendar::class)->findAll();
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
