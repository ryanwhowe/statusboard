<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Repository\CalendarRepository;
use AppBundle\Repository\ServerRepository;
use \DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\Server;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Statusboard\ControllerHelpers\AdminHelper;
use Statusboard\Utility\Environment;
use Statusboard\Utility\PayDate;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminController
 * @Route("/admin")
 * @package AppBundle\Controller
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin")
     */
    public function indexAction(Request $request)
    {
        return $this->render('AppBundle:Admin:admin.html.twig');
    }

    /**
     * @Route("/server", name="admin_test_server")
     */
    public function serverAction(Request $request)
    {
        return $this->render('AppBundle:Admin:server.html.twig',[
            'baseUrl' => $this->getParameter('api_basepath'),
            'authToken' => $this->getParameter('theaxerant_api_token')
        ]);
    }

    /**
     * @Route("/mbta", name="admin_test_mbta")
     */
    public function mbtaAction(Request $request){
        return $this->render('AppBundle:Admin:mbta.html.twig',[
            'baseUrl' => $this->getParameter('api_basepath'),
            'authToken' => $this->getParameter('theaxerant_api_token')
        ]);
    }

    /**
     * @Route("/weather", name="admin_test_weather")
     */
    public function weatherAction(Request $request){
        return $this->render('AppBundle:Admin:weather.html.twig',[
            'baseUrl' => $this->getParameter('api_basepath'),
            'authToken' => $this->getParameter('theaxerant_api_token')
        ]);
    }

    /**
     * @Route("/weather/history", name="admin_test_weather_history")
     * @param Request $request
     *
     * @return Response|null
     */
    public function weatherHistoryAction(Request $request){
        return $this->render('AppBundle:Admin:weatherhistory.html.twig',[
            'baseUrl' => $this->getParameter('api_basepath'),
            'authToken' => $this->getParameter('theaxerant_api_token')
        ]);
    }

    /**
     * @Route("/testcalendar", name="admin_test_calendar")
     * @throws \Exception
     */
    public function calendarAction(Request $request){
        $arrival_time = $request->cookies->get('time_sheet_time', '09:00');
        $add_time = $request->cookies->get('time_sheet_add_time', 0);

        return $this->render('AppBundle:Admin:calendar.html.twig', [
            'arrival_time' => $arrival_time,
            'add_time'     => $add_time,
            'baseUrl' => $this->getParameter('api_basepath'),
            'authToken' => $this->getParameter('theaxerant_api_token')
        ]);
    }

    /**
     * @Route("/clock", name="admin_test_clock")
     * @param Request $request
     * @return Response
     */
    public function clockAction(Request $request){
        $arrival_time = $request->cookies->get('time_sheet_time', '09:00');
        $add_time = $request->cookies->get('time_sheet_add_time', 0);
        return $this->render('AppBundle:Admin:clock.html.twig',[
            'arrival_time' => $arrival_time,
            'add_time'     => $add_time,
        ]);
    }

    /**
     * @Route("/environment", name="admin_test_environment")
     * @param Request $request
     * @return Response
     */
    public function environmentAction(Request $request){
        $type_test = $request->request->get('type_test');
        $envs = AdminHelper::getEnvironmentTestResults($type_test);
        return $this->render('AppBundle:Admin:environment.html.twig', [
            'environments' => $envs,
        ]);
    }
}
