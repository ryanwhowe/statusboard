<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class TimeSheetController extends Controller
{
    /**
     * @Route("/timeSheet", name="timeSheet")
     * @param $request Request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function timeSheetAction(Request $request)
    {
        $time = $request->cookies->get('time_sheet_time', '08:00');
        $add_time = $request->cookies->get('time_sheet_add_time', 0);
        return $this->render('AppBundle:TimeSheet:time_sheet.html.twig', array(
            'time' => $time,
            'add_time' => $add_time
        ));
    }

    /**
     * @Route("/utility/timeSheetUpdate", name="timeSheetUpdate")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function timeSheetUpdateAction(Request $request)
    {
        $time = $request->request->get('time');
        $add_time = $request->request->get('add_time');
        $response = new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl('timeSheet'));
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('time_sheet_time', $time, new \DateTime('tomorrow')));
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('time_sheet_add_time', $add_time, new \DateTime('tomorrow')));
        return $response;
    }

}
