<?php declare(strict_types=1);

namespace AppBundle\Controller;

use \DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
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

        return $this->render('AppBundle:Default:index.html.twig', [
            'arrival_time' => $arrival_time,
            'add_time'     => $add_time,
            //'baseUrl'      => $this->container->get('router')->getContext()->getBaseUrl() . "/"
            'baseUrl'      => $this->getParameter('api_basepath'),
            'authToken' => $this->getParameter('theaxerant_api_token')
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
        return $this->render('AppBundle:Default:calendar.html.twig', [
            'type' => [
                'pto' => 2,
                'holiday' => 1,
                'sick' => 99,
            ],
            'arrival_time' => $arrival_time,
            'add_time' => $add_time,
            'baseUrl'      => $this->getParameter('api_basepath')
        ]);
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
        $response->headers->setCookie(new Cookie('time_sheet_time',
            $time, new DateTime('tomorrow')));
        $response->headers->setCookie(new Cookie('time_sheet_add_time',
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
        $response->headers->setCookie(new Cookie('time_sheet_calendar_mon', $mon, new DateTime('next sunday')));
        $response->headers->setCookie(new Cookie('time_sheet_calendar_tue', $tue, new DateTime('next sunday')));
        $response->headers->setCookie(new Cookie('time_sheet_calendar_wed', $wed, new DateTime('next sunday')));
        $response->headers->setCookie(new Cookie('time_sheet_calendar_thu', $thu, new DateTime('next sunday')));
        return $response;
    }

}
