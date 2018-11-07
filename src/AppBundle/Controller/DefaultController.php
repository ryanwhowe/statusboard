<?php declare(strict_types=1);

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Calendar;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $arrival_time = $request->cookies->get('time_sheet_time', '08:00');
        $add_time = $request->cookies->get('time_sheet_add_time', 0);
        $calendarRepository = $this->getDoctrine()->getRepository(Calendar::class);
        $calendarEvents = $calendarRepository->findAll();
        return $this->render('statusboard/index.html.twig', [
            'calendarJson' => CalendarController::formatCalendarEventsJson($calendarEvents),
            'type'         => [
                'pto'     => CalendarController::TYPE_PTO,
                'holiday' => CalendarController::TYPE_HOLIDAY,
                'sick'    => CalendarController::TYPE_SICK,
            ],
            'arrival_time' => $arrival_time,
            'add_time'     => $add_time
        ]);
    }

    /**
     * @Route("/admin", name="admin_main")
     */
    public function adminAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }
}
